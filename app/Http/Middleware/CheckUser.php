<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()){
            Session::put('url', URL::full());
            //Session::flash('Saviso', 'Para acessar esta página você precisa ser cadastrado no site, informe seus dados de acesso!.');
            return redirect()->to('/login');
        }

        if (Auth::user()->status==2){
            Session::flash('Saviso', 'Seu usário esta bloqueado! Entre em contato com o Administrador!');
            return redirect()->to('/off');
        }elseif (Auth::user()->status==0){
            Auth::logout();
            Session::flash('Saviso', 'Seu usário esta aguardando liberação do Administrador!');
            return redirect()->to('/off');
        }

        //Se tem redirecionamento
        if (Session::has('url') ) {
            $url = Session::pull('url', URL::full() );
            return redirect()->to($url);
        }else
            return $next($request);
    }
}
