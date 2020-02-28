<?php

use App\Models\Moviment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: GDOOR
 * Date: 15/04/2016
 * Time: 11:43
 */

function LIMPANUMERO ($nro)
    {
        $aux ='';
        for ($i=0;$i <= strlen($nro);$i++)
        {
            if ((substr($nro,$i,1)=='0')or(substr($nro,$i,1)=='1')or
                (substr($nro,$i,1)=='2')or(substr($nro,$i,1)=='3')or(substr($nro,$i,1)=='4')or
                (substr($nro,$i,1)=='5')or(substr($nro,$i,1)=='6')or(substr($nro,$i,1)=='7')or
                (substr($nro,$i,1)=='8')or(substr($nro,$i,1)=='9'))
            {
                $aux.=substr($nro,$i,1);
            }
        }
        return $aux;
    }

function StrZero($str,$qtd){
    $res = str_pad($str,$qtd,'0',STR_PAD_LEFT);
    return $res;
}

///// no lugar da função mod que retorna o resto da divisao usamos o %, ex: 6 mod 5 fica 6 % 5;
Function VerificaCpfCgc($pP1)
{
    $pP1 = TRIM( ( LIMPANUMERO( $pP1 ) ));
    if (strlen($pP1)>0)
    {
        $DIGITO = 0;
        $MULT = '543298765432';
        ///Se for CNPJ
        if (strlen($pP1)==14)
        {
            $DIGITOS = substr($pP1,12,2); /// digitos informados
            $MULT = '543298765432';
            $CONTROLE = '';
            ///Loop de verificação
            $J=1;
            For ($J;$J<=2;$J++)
            {
                $SOMA = 0;
                $I=1;
                For ($I;$I<=12;$I++)
                {
                    $SOMA = $SOMA + (substr($pP1,$I-1,1)*substr($MULT,$I-1,1));
                }
                if ($J==2)
                {
                    $SOMA = $SOMA + (2*$DIGITO);
                }
                $DIGITO = ($SOMA*10) % 11;
                If ($DIGITO==10)
                {
                    $DIGITO = 0;
                }
                $CONTROLE = $CONTROLE.$DIGITO;
                $MULT= '654329876543';
            } /// fim for J
            ////compara os dígitos calculados(CONTROLE) com os dígitos informados (DIGITOS)
            if ($CONTROLE<>$DIGITOS)
            {
                return FALSE;
            }else
            {
                return TRUE;
            }
        }else //// FIM Se FOR CNPJ
            if (strlen($pP1)==11) /// se FOR CPF
            {
                $DIGITOS = substr($pP1,9,2); /// digitos informados
                $MULT = '100908070605040302';
                $CONTROLE = '';
                ///Loop de verificação
                $J=1;
                For ($J;$J<=2;$J++)
                {
                    $SOMA = 0;
                    //
                    $K=0;
                    //
                    $I=1;
                    //
                    For ($I;$I<=9;$I++)
                    {
                        if ($I == 1)
                        {
                            $K=1;
                        }else
                        {
                            $K=$K+2;
                        }
                        $SOMA = $SOMA + (substr($pP1,$I-1,1)*substr($MULT,$K-1,2));
                    }
                    //
                    if ($J==2)
                    {
                        $SOMA = $SOMA + (2*$DIGITO);
                    }
                    //
                    $DIGITO = ($SOMA*10) % 11;
                    //
                    If ($DIGITO==10)
                    {
                        $DIGITO = 0;
                    }
                    //
                    $CONTROLE = $CONTROLE.$DIGITO;
                    $MULT= '111009080706050403';
                } /// fim for J
                ////compara os dígitos calculados(CONTROLE) com os dígitos informados (DIGITOS)
                if ($CONTROLE<>$DIGITOS)
                {
                    return False;
                }else
                {
                    return True;
                }

            }else //// FIM Se FOR CPF
            {
                return False;
            }
    }else // se a variavel informada for vazia
    {
        return False;;
    }

}
/////Fim CPF CNPJ


///Formata o CGC
function FormataCpfCnpj($pP1)
    {
        $result = $pP1;
        $pP1 = LIMPANUMERO($pP1);
        if (strlen($pP1)==14)
        {
            $result=substr($pP1,0,2).'.'.
                substr($pP1,2,3).'.'.
                substr($pP1,5,3).'/'.
                substr($pP1,8,4).'-'.
                substr($pP1,12,2);
        }elseif (strlen($pP1)==11)
        {
            $result=substr($pP1,0,3).'.'.
                substr($pP1,3,3).'.'.
                substr($pP1,6,3).'-'.
                substr($pP1,9,2);
        }
        return $result;
    }



if (!function_exists('helpBlock')) {
    /**
     * Retorna mensagem de ajuda para erros de validação
     *
     * @param Illuminate\Support\ViewErrorBag $errors
     * @param string $key
     * @return string
     */
    function helpBlock($errors, $key)
    {
        if ($errors->has($key)) {
            return "<span class='help-block text-danger'><strong>{$errors->first($key)}</strong></span>";
        }

        return null;
    }
}

/**
 * Retorna classe de erro de validação para Views
 *
 * @param Illuminate\Support\ViewErrorBag $errors
 * @param string $key
 * @return string
 */
function hasErrorClass($errors, $key, $class = 'has-error', $dd = false)
{
    if ($dd) {
        if (!$errors->has($key)) dd($errors);
    }
    return $errors->has($key) ? ' ' . $class : '';
}



/**
 * Retorna os estados UF em Options
 *
 */
function So1Nome($nome)
{
    $pos = strpos($nome,' ');
    if ($pos>0) $nome=substr($nome,0,$pos);
    return $nome;
}



/**
 * Retorna os estados UF em Options
 *
 */
function Auditoria($acao,$model,$id,$info='')
{
    if (Auth::check()) {
        $user_id = Auth::user()->id;
    }else {
        $user_id = null;
    }

    DB::table('audits')->insert(
        ['user_id' => $user_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'action' => $acao,
            'model' => $model,
            'reg_id' => $id,
            'info' => $info
        ]
    );
}



function FormatarCEP($CEP){
    $CEP = LIMPANUMERO($CEP);
    if (strlen($CEP)>=8)
        $CEP = substr($CEP,0,5).'-'.substr($CEP,5,3);
    return $CEP;
}


function TratarEndereco($rua){
    if (!str_contains($rua,' - de '))
        return $rua;

    $ini = 0;
    $fim = strpos($rua,' - de ');
    $msg = substr($rua,$ini,$fim);

    return $msg;
}

function MyTextMaterial($errors,$name,$label,$value,$att=''){
    $value = old($name) ? old($name) : $value;

    $msg = '<div class="form-group '.hasErrorClass($errors,$name).'">
        <input type="text" class="form-control" id="'.$name.'" value="'.$value.'" '.$att.'>
        <span class="bar"></span>
        <label id="lbl_'.$name.'" for="'.$name.'">'.$label.'</label>
    </div>
    '.helpBlock($errors,$name)
    ;
    return $msg;
}

function MyTextField($errors,$name,$label,$value,$att=''){
    return MyField('text',$errors,$name,$label,$value,$att);
}

function MyField($type,$errors,$name,$label,$value,$att=''){
    $value = old($name) ? old($name) : $value;

    $msg = '<div class="form-group '.hasErrorClass($errors,$name).'">';

    if ($label<>'') $msg.=' <label id="lbl_'.$name.'" for="'.$name.'">'.$label.'</label>';

    $msg.='<input type="'.$type.'" class="form-control" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$att.'>
           '.helpBlock($errors,$name)
    ;
    $msg.='</div>';
    return $msg;
}

function MyTextBtn($errors,$name,$label,$value,$att=''){
    $value = old($name) ? old($name) : $value;

    $msg = '<div class="form-group '.hasErrorClass($errors,$name).'">';

    if ($label<>'') $msg.=' <label id="lbl_'.$name.'" for="'.$name.'">'.$label.'</label>';

    $msg.='<div class="input-group">
                <input type="text" class="form-control" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$att.'>
                <span class="input-group-btn">
                  <button class="btn btn-info" type="button" id="btn_'.$name.'"><i id="ico_'.$name.'" class="fa fa-search"></i> </button>
                </span>                
            </div>
            '.helpBlock($errors,$name).'
            ';
    $msg.='</div>';
    return $msg;
}

function MylabelView($label,$value){
    $msg = '<div class="form-group m-t-5 ">
        <input type="text" class="form-control" value="'.$value.'" disabled style="background-color: #FFF">
        <span class="bar"></span>
        <label>'.$label.'</label>
    </div>';
    return $msg;
}

function UrlPoker(){
    if (env('APP_ENV','local')=='local')
        return "http://localhost:8001";
    else
        return "https://pokerclubsapp.com.br";
}

function FieldSize($texto,$size){
    return trim( substr($texto,0,$size));
}