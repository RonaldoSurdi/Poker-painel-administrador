<?php

namespace App\Http\Controllers\Poker;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClubRequest;
use App\Models\Poker\Club;
use App\Models\Poker\ClubIndicado;
use App\Models\Poker\ClubObs;
use App\Models\Poker\ClubUser;
use App\Models\Poker\License;
use App\Models\Poker\UserApp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $indicados = ClubIndicado::wherestatus(0)->count();
        //
        $qtd_clubs = Club::count();
        //
        $qtd_clubs_premium = License::select('club_id')
            //->where('club_id','<>','3') //Tadeu
            //->where('club_id','<>','356') //Daniel
            //->where('club_id','<>','350') //Saintec
            ->wherestatus(1)->groupby('club_id')->get();
        $qtd_clubs_premium = $qtd_clubs_premium->count();
        //
        $usuarios = UserApp::count();

        //Lista de estados
        $estados = Club::selectRaw('cities.uf,count(clubs.id)"qtd"')
            ->join('cities','cities.id','clubs.city_id')
            ->groupby('cities.uf')
            ->orderby('cities.uf')
            ->get();

        $crms = ClubObs::whereRaw('(club_obs.status=0) AND (club_obs.notify_admin=1)')
            ->join('clubs','club_obs.club_id','clubs.id')
            ->select('club_obs.id','club_obs.club_id','clubs.name','club_obs.obs','club_obs.notify_at')
            ->orderby('club_obs.notify_at')
            ->get();

        return view('poker.clubs.home',compact('indicados','qtd_clubs','qtd_clubs_premium','usuarios','estados', 'crms'));
    }

    public function find($id)
    {
        //
        $lista = Club::whereid($id)->get();
        $busca = 'Código '.$id;
        return view('poker.clubs.list',compact('lista','busca'));
    }

    public function all()
    {
        $lista = Club::orderby('id','desc')->get();
        return view('poker.clubs.list',compact('lista'));
    }

    public function search(Request $request)
    {
        $busca = $request['busca'];
        $nro = LIMPANUMERO($busca);
        if (!$nro) $nro = $busca;
        $lista = Club::Where(function ($query) use ($busca,$nro) {
            $query->where('name', 'like', '%'.$busca.'%')
                ->orWhere('doc1', 'like', '%'.$nro.'%')
                ->orWhere('doc2', 'like', '%'.$busca.'%')
                ->orWhere('doc2', 'like', '%'.$nro.'%')
                ->orWhere('responsible', 'like', '%'.$busca.'%')
                ->orWhere('phone', 'like', '%'.$nro.'%')
                ->orWhere('whats', 'like', '%'.$nro.'%')
                ->orWhere('email', 'like', '%'.$busca.'%')
                ->orWhere('site', 'like', '%'.$busca.'%')
                ->orWhere('zipcode', 'like', '%'.$nro.'%')
                ->orWhere('city_id', 'like', '%'.$busca.'%')
                ->orWhere('address', 'like', '%'.$busca.'%')
                ->orWhere('district', 'like', '%'.$busca.'%')
                ->orWhere('complement', 'like', '%'.$busca.'%')
            ;
        })->get();

        return view('poker.clubs.list',compact('lista','busca'));
    }

    public function state($uf)
    {
        if (\Illuminate\Support\Facades\Auth::user()->id === 1) {
            $lista = Club::select('clubs.*')
                ->join('cities', 'cities.id', 'clubs.city_id')
                ->where('cities.uf', $uf)
                ->get();
        } else {
            $lista = Club::select('clubs.*')
                ->join('cities', 'cities.id', 'clubs.city_id')
                ->where('cities.uf', $uf)
                ->get();
        }
        return view('poker.clubs.list',compact('lista'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cad = new Club();
        $cad->id = 0;

        $cities = DB::table('cities')->select('cities.*')
            ->orderBy('name')
            ->get();

        return view('poker.clubs.edit',compact('cad','cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(ClubRequest $request)
    {
        //se existe
        if ($request['id'] > 0) {
            $cad = Club::find($request['id']);
            if ($cad->id <> $request['id']) {
                Session::put('Serro', 'Cadastro não encontrado');
                return redirect()->route('poker.clubs');
            }
            $action = 'EDIT';
        } else {
            $cad = new Club();
            $cad->status = 1;
            $action = 'NEW';
        }

        //dados
        $cad->name = $request['name'];
        $cad->doc1 = LIMPANUMERO($request['doc1']);
        $cad->doc2 = $request['doc2'];
        $cad->responsible = $request['responsible'];
        $cad->phone = LIMPANUMERO($request['phone']);
        $cad->whats = LIMPANUMERO($request['whats']);
        $cad->email = $request['email'];
        $cad->site = $request['site'];
        $cad->zipcode = LIMPANUMERO($request['zipcode']);
        $cad->city_id = $request['city'];
        $cad->address = $request['address'];
        $cad->number = $request['number'];
        $cad->district = $request['district'];
        $cad->complement = $request['complement'];
        $cad->lat = $request['lat'];
        $cad->lng = $request['lng'];
        $cad->save();

//        //Observação
//        $Obs = ClubObs::whereclub_id($cad->id)->first();
//        if ($request['obs']<>''){
//            if (!$Obs) {
//                $Obs = new ClubObs();
//                $Obs->club_id = $cad->id;
//            }
//            $Obs->obs = $request['obs'];
//            $Obs->save();
//        }elseif ($Obs)
//            $Obs->delete();

        //indicação
        if ($request->has('ind_id')){
            $ind = ClubIndicado::find($request['ind_id']);
            $ind->status = 1;
            $ind->club_id = $cad->id;
            $ind->save();
        }

        Auditoria($action,'CLUB',$cad->id,json_encode($cad));

        //
        Session::put('Sok', 'Cadastro Salvo');
        if ($request['id']>0)
            return redirect()->route('poker.club.show',['id'=>$cad->id]);
        else
            return redirect()->route('poker.club.find',['id'=>$cad->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $cad)
    {
        //licenças
        $licenses = License::whereclub_id($cad->id)->orderby('id')->get();

        //Contatos

        return view('poker.clubs.show',compact('cad','licenses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $cad)
    {
        return view('poker.clubs.edit',compact('cad'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $cad)
    {
        Auditoria('DELETE','CLUB',$cad->id, json_encode($cad));
        //Deletar o usuário do clube
        ClubUser::where('club_id',$cad->id)->delete();
        //
        $cad->delete();
        //
        Session::put('Sok','Cadastro Excluido');
        return redirect()->route('poker.club.all');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Administrator  $cad
     * @return \Illuminate\Http\Response
     */
    public function active(Club $cad)
    {
        //
        if ($cad->status==1) {
            Auditoria('BLOCKED','CLUB',$cad->id);
            $cad->status = 0;
            Session::put('Sok','Cadastro Bloqueado');
        }else {
            Auditoria('ACTIVE','CLUB',$cad->id);
            $cad->status = 1;
            Session::put('Sok','Cadastro Liberado');
        }
        $cad->save();

        return redirect()->route('poker.club.all');

        //return redirect()->route('poker.club.show',['id'=>$cad->id]);
    }

    public function saveUser(Request $request)
    {
        $cad = ClubUser::whereclub_id($request['club'])->first();
        if (!$cad){ //Ainda não tem cadastro
            $cad = new ClubUser();
            $cad->club_id = $request['club'];
            $id = 0;
        }else{
            $id = $cad->id;
        }

        /*** verifica se ja existe o e-mail ***/
        $log = ClubUser::whereemail($request['lic_user'])->first();
        if ($log){
            return [
                'result' => 'N'
                , 'message'=>'Usuário em uso no clube '.$log->club->name
            ];
        }

        /*** salva os novos dados ***/
        $cad->email = $request['lic_user'];
        $cad->password = $request['lic_pass'];
        $cad->save();

        return [
            'result' => 'S'
            , 'message'=>'Usuário Salvo'
        ];
    }

}
