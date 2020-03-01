<?php

namespace App\Http\Controllers\Poker;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pay\PaymentController;
use App\Models\Poker\License;
use App\Models\Poker\LicenseCard;
use App\Models\Poker\LicensePay;
use App\Models\Poker\LicenseStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class LicenseController extends Controller
{

    public function MudarStatus($id,$new_status,$obs='',$days='1 year'){
        $lic = License::find($id);
        $old = $lic->status;

        //Salvar novo status
        $lic->status = $new_status;
        if ( ($new_status==1) and (!$lic->due_date)){
            //Se for ativação e não tem data
            $lic->start_date = now();
            $lic->due_date = date('Y-m-d H:i:s', strtotime($lic->start_date. ' + '.$days));
        }
        $lic->save();

        //Guardar o status
        $cad = new LicenseStatus();
        $cad->license_id = $lic->id;
        $cad->old = $old;
        $cad->new = $new_status;
        $cad->text = $obs;
        if (Auth::check())
            $cad->user_id = Auth::user()->id;
        //
        $cad->save();

        //se for ativação de uma licença anual, bloqueio a de 30 dias
        if ( ($lic->type==1) and ($new_status==1) ){
            $aux = License::wherestatus(1)
                ->wheretype(2)
                ->whereclub_id($lic->club_id)
                ->first();
            if ($aux)
                $this->MudarStatus($aux->id,9,'Bloqueio por ativação de Licença anual');
        }
    }

    public function getLicenses($type){
        if ($type==0)
            $type = 'type>0';
        else
            $type = 'type='.$type;

        return License::whereRaw($type)
            //->where('club_id','<>','3')
            //->where('club_id','<>','356')
            //->where('club_id','<>','350')
            ->orderby('id','desc')
            ->get();
    }

    public function list_premium(){
        $lista = $this->getLicenses(0);
        $title = 'Licenças Premiuns';
        return view('poker.licenses.list',compact('lista','title'));
    }


    public function LicensesClub($club_id){
        //licenças
        $lista = License::whereclub_id($club_id)->orderby('id')->get();

        //montar o html
        $html = View::make('poker.licenses.ofclub', compact('lista'))->render();

        //Clube tipo de licença
        $cad = License::whereclub_id($club_id)
            ->wherestatus(1)
            ->first();
        if (!$cad)
            $premium ='Free';
        else
            $premium ='Premium';

        //retorno
        return [
            'result' => 'S'
            , 'html' => $html
            , 'premium' => $premium
        ];
    }


    public function store(Request $request)
    {
        /*** Não permite duas vezes 30 dias gratis ****/
        if ($request['lic_type']==2) {
            $cad = License::whereclub_id($request['club'])
                ->wheretype(2)
                ->first();
            if ($cad) {
                return [
                    'result' => 'N'
                    , 'message' => 'Este clube já usou os 30 dias grátis'
                ];
            }
        }

        /*** Não permite duas licenças anuais ****/
        $cad = License::whereclub_id($request['club'])
            ->wheretype(1)
            ->where('status','<',2)
            ->first();
        if ($cad)
            if ($cad->dias()>30) {
                return [
                    'result' => 'N'
                    , 'message' => 'Este clube já possui uma licença Anual ativada'
                ];
            }


        /**** Cadastra uma nova licença ****/
        $lic = new License();
        $lic->club_id = $request['club'];
        $lic->type = $request['lic_type'];
        //
        if ($lic->type==2)
            $lic->value =0; //se for 30 dias gratis coloca valor zero
        else
            $lic->value = 1188;
        //
        $lic->user_id = Auth::user()->id;
        $lic->save();


        /*** Se for type 2- 30dias *****/
        if ($lic->type==2)
            $this->MudarStatus($lic->id,1,'Liberação automatica devido a ser 30 dias gratis','30 days');

        return [
            'result' => 'S'
            , 'message' => 'Licença Adicionada'
        ];
    }


    public function destroy(License $lic)
    {
        Auditoria('DELETE', 'LICENSE', $lic->id);

        //Excluir
        $lic->delete();

        //retorno
        return [
            'result' => 'S'
            , 'message' => 'Licença excluida'
        ];
    }

    public function licpay(License $cad){
        $lic = $cad;

        //Se não tem pagamento, gera um novo
        if (!$cad->payment) {
            $club = $lic->club;
            try {
                $post = array(
                    'app' => 1, //cod do poker
                    'app_id' => $lic->id, //id da lioença
                    'valor' => $lic->value,
                    'return_path' => UrlPoker().'/lic',
                    'callback_url' => UrlPoker().'/api/callback/lic',
                    'produto' => 'Licença Anual PokerClubs',
                    'comprador' => $club->name,
                    'cpf' => $club->doc1,
                    'fone' => $club->phone,
                    'email' => $club->email,
                    'cep' => $club->zipcode,
                    'uf' => $club->cidade()->uf,
                    'cidade' => $club->cidade()->name,
                    'endereco' => $club->address,
                    'nro' => $club->number,
                    'bairro' => $club->district,
                );

                $request = new Request();
                foreach($post as $key => $value) {
                    $request[$key] = $value;
                }

                $retorno = PaymentController::create($request);
                //Se não houve retorno
                if (!$retorno)
                    return ['result'=>'N',"message"=>'Não houve retorno ao criar o pagamento!'];
            } catch (\Exception $e) {
                Log::warning('Create payment: '.$e->getMessage());
                $retorno = ['result'=>'N',"message"=>$e->getMessage()];
            }
            if ($retorno)
                if ($retorno['result'] == 'S') {
                    //Gravar o hash na licença
                    $lic->payment = $retorno['hash'];
                    $lic->save();
                }else
                    return ($retorno);
        }

        //Retorna o hash gerado
        $hash = $lic->payment;

        if (!$hash)
            return ['result'=>'N',"message"=>'Não foi encontrado pagamento hash!'];

        //Se tem retorna
        return ['result'=>'S','url'=> url('/pay/'.$hash) ];
    }

}
