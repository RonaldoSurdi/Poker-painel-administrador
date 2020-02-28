<?php

namespace App\Http\Controllers\Poker;

use App\Http\Controllers\Controller;
use App\Models\Poker\Club;
use App\Models\Poker\ClubUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClubUserController extends Controller
{


    public function store(Request $request)
    {
        /*** validação dos campos ***/
        $validator = Validator::make($request->all(), [
            'club' => 'required',
            'lic_user' => 'required|email',
            'lic_pass' => 'required',
        ],
            [
                'club.required'=>"Informe o Club",
                'lic_user.required'=>"Informe um e-mail",
                'lic_user.email'=>"Informe um e-mail válido!",
                'lic_pass.required'=>"Informe uma senha!",
            ]
        );
        if ($validator->fails()) {
            /***se tem algum erro de campo***/
            foreach ($validator->errors()->all() as $message){
                return ["result"=>"N","message"=>$message];
            }
        }


        $club = Club::find($request['club']);

        /*** localizar o usuario do club ***/
        $cad = ClubUser::whereclub_id($request['club'])->first();
        if (!$cad){ //Ainda não tem cadastro
            $cad = new ClubUser();
            $cad->club_id = $request['club'];
            $user_id = 0;
            $message = 'Usuário criado!';
        }else{/// ja tem usuario
            $user_id = $cad->id;
            $message = 'Usuário Atualizado!';
        }

        /*** verifica se ja existe o e-mail ***/
        $log = ClubUser::whereemail($request['lic_user'])->where('id','<>',$user_id)->first();
        if ($log){
            return [
                'result' => 'N'
                , 'message'=>'E-mail de usuário ja está em uso no clube:<br>'.$log->club->name
            ];
        }

        /*** salva os novos dados ***/
        $cad->name = $club->name;
        $cad->email = $request['lic_user'];
        $cad->password = Hash::make( $request['lic_pass'] );
        $cad->save();

        Auditoria('UPDATE','CLUB_USER',$cad->id,
            $message.' '
            .chr(13).'club: '.$cad->club_id
            .chr(13).'email: '.$cad->email
            .chr(13).'pass: '.$cad->pass
        );

        return [
            'result' => 'S'
            , 'message'=>$message
        ];
    }

    public function destroy(Request $request){
        /*** localizar o usuario do club ***/
        $cad = ClubUser::whereclub_id($request['club'])->first();
        if (!$cad) { //Ainda não tem cadastro
            return [
                'result' => 'N'
                , 'message'=>'O Clube não tem usuário cadastrado.'
            ];
        }


        Auditoria('DELETE','CLUB_USER',$cad->id,
            'club: '.$cad->club_id
            .chr(13).'email: '.$cad->email
            .chr(13).'pass: '.$cad->pass
        );

        $cad->delete();

        return [
            'result' => 'S'
            , 'message'=>"Usuário Excluido"
        ];
    }
}
