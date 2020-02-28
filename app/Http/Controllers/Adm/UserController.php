<?php

namespace App\Http\Controllers\Adm;

use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserPassRequest;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = User::all();
        return view('app.config.user.list',compact('lista'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $busca = $request['busca'];
        $lista = User::
            Where(function ($query) use ($busca) {
                $query->where('name', 'like', '%'.$busca.'%')
                ->orWhere('email', 'like', '%'.$busca.'%');
            })->get();
        return view('app.config.user.list',compact('lista','busca'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $cad
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cad = new User();
        $cad->id = 0;
        //
        return view('app.config.user.edit',compact('cad'));
    }

    public function edit($id)
    {
        $cad = User::find($id);
        //
        return view('app.config.user.edit',compact('cad'));
    }

    public function show(User $cad)
    {
        return view('app.config.user.show',compact('cad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(UserRequest $request)
    {
        $id = $request['id'];
        //só vai ter cadastrar, não vai ter editar
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        Session::put('Sok','Cadastro Salvo');
        return redirect()->route('config.user');
    }
    public function update(UserEditRequest $request)
    {
        $id = $request['id'];
        $cad = null;

        if ($id>0)
            $cad = User::find($id);

        if (!$cad) {
            Session::put('Sok', 'Usuário não encontrado!');
            return redirect()->route('adm.cad.user');
        }

        $cad->name = $request['name'];
        $cad->email = $request['email'];
        $cad->save();

        Session::put('Sok','Cadastro Salvo');
        return redirect()->route('config.user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $cad
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $cad)
    {
        Auditoria('DELETE','USER',$cad->id,'Name:'.$cad->name.chr(13).'Email:'.$cad->email);
        //
        $cad->delete();
        Session::put('Sok','Cadastro Excluido');
        return redirect()->route('config.user');
    }


    public function pass($id)
    {
        $cad = User::find($id);
        //
        return view('adm.user.pass',compact('cad'));
    }
    public function pass_save(UserPassRequest $request)
    {
        $id = $request['id'];
        $cad = null;

        if ($id>0)
            $cad = User::find($id);

        if (!$cad) {
            Session::put('Sok', 'Usuário não encontrado!');
            return redirect()->route('adm.cad.user');
        }

        $cad->password = bcrypt($request['password']);
        $cad->save();

        Session::put('Sok','Senha Salva');
        return redirect()->route('adm.cad.user');
    }

    public function active(User $cad)
    {
        //
        if ($cad->status==1) {
            Auditoria('BLOCKED','USER',$cad->id);
            $cad->status = 0;
            Session::put('Sok','Usuário Bloqueado');
        }else {
            Auditoria('ACTIVE','USER',$cad->id);
            $cad->status = 1;
            Session::put('Sok','Usuário Liberado');
        }
        $cad->save();

        return redirect()->route('config.user');
    }
}
