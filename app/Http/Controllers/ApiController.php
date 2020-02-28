<?php

namespace App\Http\Controllers;

use App\Models\ClubIndicado;
use App\Models\UserApp;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use Exception;

class ApiController extends Controller
{

    public function cities(Request $request){
        $city = $request['search'];

        $lista = DB::table('cities')
            ->selectRaw("id, concat(name,' / ',uf)".' "text" ')
            ->where('name','like',$city.'%')
            ->orderBy('name')
            ->get();
        return ['items'=>$lista];
    }

    public function city($id){
        $lista = DB::table('cities')
            ->selectRaw("id, concat(name,' / ',uf)".' "text" ')
            ->where('id',$id)
            ->first();
        return  ["id"=>$lista->id,"text"=>$lista->text];
    }

    public function cnpj_gdoor($cnpj){
        $opts = array(
            "http" => array(
                "method" => "GET",
//                "user_agent" => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36"
            )
        );
        $context  = stream_context_create($opts);

        $cnpj = LIMPANUMERO($cnpj);
        $resultado = @file_get_contents("http://painel.gdoor.com.br/api/cnpj/".$cnpj, false, $context);
        $resultado = json_decode($resultado, true);
        return $resultado;
    }

    /**
     * Consulta o CNPJ no site https://www.receitaws.com.br
     */
    public function cnpj($cnpj)
    {
        /*** Aceitar todas as URL de outros sites */
//        header('Access-Control-Allow-Origin: *');

        $cnpj = LIMPANUMERO($cnpj);

        $data = array();
        $dados = array();
        $endereco = array();

        $data['status'] = 'ERROR';
        $data['message'] = 'sem retorno';
        $data['server1'] = '';
        $data['server2'] = '';


        $dados['nome'] = '';
        $dados['fantasia'] = '';
        $dados['responsavel'] = '';
        $dados['tipo'] = '';
        $dados['situacao'] = '';
        $dados['cnpj'] = '';
        $dados['ie'] = '';
        $dados['telefone'] = '';
        $dados['email'] ='';

        $endereco['cep'] = '';
        $endereco['municipio'] = '';
        $endereco['municipio_cod'] = '';
        $endereco['uf'] = '';
        $endereco['uf_cod'] = '';
        $endereco['logradouro'] = '';
        $endereco['numero'] = '';
        $endereco['complemento'] = '';
        $endereco['bairro'] = '';

        $contribuinte = array();
        $contribuinte['ie'] = '';
        $contribuinte['ie_tipo'] = '';
        $contribuinte['ie_status'] ='';
        $contribuinte['ie_status_data'] = '';
        $contribuinte['regime_tributacao_icms'] = '';
        $contribuinte['informa_ie_dest'] = '';
        $contribuinte['porte_empresa'] = '';
        $contribuinte['proprietarios'] = [];

        $atividade = array();
        $atividade['atividade_data_inicio'] = '';
        $atividade['atividade_data_fim'] = '';
        $atividade['atividade_principal_CNAE'] = '';
        $atividade['CNAE_principal'] = [];
        $atividade['CNAE_secundarias'] = [];



        if (!VerificaCpfCgc($cnpj)){
            return ['status'=>"ERROR",'message'=>"CPF/CNPJ inválido"];
        }

        /*** Se for CPF ***/
        if (strlen($cnpj)==11) {
            //
            $data['message'] ='CPF não tem consulta';

        }else
            /*** Se for CNPJ ***/
            if (strlen($cnpj)==14) {

                $cod_uf = '0';
                $data['message'] = '';

                /*** Consulta o site receitaws para trazer os dados ***/
                try{
                    $ctx = stream_context_create(array('http'=>
                        array(
                            'timeout' => 5,  //1200 Seconds is 20 Minutes
                        )
                    ));

                    $obj = file_get_contents("https://www.receitaws.com.br/v1/cnpj/" . $cnpj, false, $ctx);

                    $obj = json_decode($obj, true);
                    if ($obj['status'] == 'ERROR') {
                        return $obj;
                    }
                    $data['server1'] ='OK';

                    $dados['nome'] = $obj['nome'];
                    $dados['fantasia'] = $obj['fantasia'];
                    $dados['tipo'] = $obj['tipo'];
                    $dados['situacao'] = $obj['situacao'];
                    $dados['cnpj'] = LIMPANUMERO( $obj['cnpj'] );
                    $dados['ie'] = '';
                    $dados['telefone'] = $obj['telefone'];
                    $dados['email'] = $obj['email'];

                    $endereco['cep'] = FormatarCEP( $obj['cep']);
                    $endereco['municipio'] = $obj['municipio'];
                    $endereco['uf'] = $obj['uf'];
                    $cod_uf = $this->CodUF($obj['uf']);
                    $endereco['uf_cod'] = $cod_uf;
                    $endereco['logradouro'] = $obj['logradouro'];
                    $endereco['numero'] = $obj['numero'];
                    $endereco['complemento'] = $obj['complemento'];
                    $endereco['bairro'] = $obj['bairro'];

                    $atividade['atividade_principal_CNAE'] = LIMPANUMERO( $obj["atividade_principal"][0]['code'] );
                    $atividade['CNAE_principal'] = $obj["atividade_principal"];
                    $atividade['CNAE_secundarias'] = $obj["atividades_secundarias"];

                    $contribuinte['proprietarios'] = $obj["qsa"];

                    foreach ($obj["qsa"] as $item){
                        $dados['responsavel'] = $item['nome'];
                        if ($item['qual']=='49-Sócio-Administrador')
                            break;
                    }

                }catch (Exception $e) {
                    $data['server1'] = $e->getMessage();
                    //return ['status'=>"ERROR",'message1'=>];
                }


                /**** Consultar o servidor do CCC ****/
                $dadosCCC = $this->CCC($cnpj,$cod_uf);
                if ($dadosCCC['status']=='OK') {

                    $data['server2'] ='OK';

                    $dados['ie'] = $dadosCCC['contribuinte']['ie'];
                    if ($dados['fantasia']=='') $dados['fantasia'] = $dadosCCC['dados']['fantasia'];

                    $endereco['municipio_cod'] = $dadosCCC['endereco']['municipio_cod'];
                    $endereco['municipio'] = $dadosCCC['endereco']['municipio'];

                    $contribuinte['ie'] = $dadosCCC['contribuinte']['ie'];
                    $contribuinte['ie_tipo'] = $dadosCCC['contribuinte']['ie_tipo'];
                    $contribuinte['ie_status'] = $dadosCCC['contribuinte']['ie_status'];
                    $contribuinte['ie_status_data'] = $dadosCCC['contribuinte']['ie_status_data'];
                    $contribuinte['regime_tributacao_icms'] = $dadosCCC['contribuinte']['regime_tributacao_icms'];
                    $contribuinte['informa_ie_dest'] = $dadosCCC['contribuinte']['informa_ie_dest'];
                    $contribuinte['porte_empresa'] = $dadosCCC['contribuinte']['porte_empresa'];

                    $atividade['atividade_data_inicio'] = $dadosCCC['contribuinte']['atividade_data_inicio'];
                    $atividade['atividade_data_fim'] = $dadosCCC['contribuinte']['atividade_data_fim'];

                    //Se não tem RECEITAWS carrega os dados vindos do CCC
                    if ($data['server1']<>'OK'){
                        $dados['nome'] = $dadosCCC['dados']['nome'];
                        $dados['fantasia'] = $dadosCCC['dados']['fantasia'];
                        $dados['cnpj'] = LIMPANUMERO( $cnpj );
                        $dados['situacao'] = 'ATIVA';

                        $endereco['cep'] = FormatarCEP( $dadosCCC['endereco']['cep'] );

                        $endereco['uf'] = $dadosCCC['endereco']['uf'];
                        $cod_uf = $this->CodUF($endereco['uf']);
                        $endereco['uf_cod'] = $cod_uf;

                        $endereco['logradouro'] = $dadosCCC['endereco']['logradouro'];
                        $endereco['numero'] = $dadosCCC['endereco']['numero'];
                        $endereco['complemento'] = $dadosCCC['endereco']['complemento'];
                        $endereco['bairro'] = $dadosCCC['endereco']['bairro'];

                        $atividade['atividade_principal_CNAE'] = $dadosCCC['contribuinte']['atividade_principal_CNAE'];
                    }

                }else{
                    $data['server2'] = $dadosCCC['message'];

                    if ($cod_uf=='0'){
                        return ['status' => "ERROR"
                            ,'server1' => $data['server1']
                            ,'server2' => $data['server2']
                            ,'message' => $dadosCCC['message']
                        ];
                    }

                }


            }

        $data['status'] ='OK';
        $data['message'] ='Concluido';
        $data['dados'] = $dados;
        $data['endereco'] = $endereco;
        $data['contribuinte'] = $contribuinte;
        $data['atividade'] = $atividade;

        return $data;

    }
    public function CodUF($uf){
        $cad = DB::table('cities')->select('id')->whereuf($uf)->first();
        if ($cad)
            return substr($cad->id,0,2);
        return "0";
    }
    public function CCC($cnpj,$cod_uf)
    {
        /*** Consulta o site receitaws para trazer os dados ***/
        try{
            $ctx = stream_context_create(array('http'=>
                array(
                    'timeout' => 10,  //1200 Seconds is 20 Minutes
                )
            ));
            $pag = file_get_contents("https://www.sefaz.rs.gov.br/NFE/NFE-CCC.aspx",false,$ctx);
        }catch (Exception $e) {
            return ['status'=>"ERROR",'message'=>'CCC1: '.$e->getMessage()];
        }

        if (!str_contains($pag,'&key='))
            return ['status'=>'ERROR','message'=>'Key não encontrada'];

        /*** extrai a chave do site ***/
        $ini = strpos($pag,'&key=')+5;
        $key = trim(substr($pag,$ini,44));
        if (strlen($key)<>44)
            return ['status'=>'ERROR','message'=>'Key inválida'];

        /******* Enviar POST ao CCC para Consultar as IE *****/
        try{
            $cURL = curl_init();
            curl_setopt($cURL,CURLOPT_URL,"https://www.sefaz.rs.gov.br/NFE/NFE-CCC_DO.aspx");
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            $post = array(
                'iCodUf' => $cod_uf,
                'key' => $key,
                'lCnpj' => $cnpj,
                'pAmbiente' => 1,
            );
            curl_setopt($cURL, CURLOPT_POST, true);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $post);
            $tabela = curl_exec($cURL);
            curl_close($cURL); #Fechamos o cURL
        }catch (Exception $e) {
            return ['status'=>"ERROR",'message'=>'CCC2: '.$e->getMessage()];
        }

        /*** verifica se encontrou na base do CCC  ***/
        if (str_contains($tabela,'Nenhum estabelecimento encontrado.'))
            return ['status' => 'ERROR', 'message' => 'Nenhum estabelecimento encontrado com cnpj '.$cnpj];

        /**** pega a url com todos os dados ****/
        if (!str_contains($tabela,'NFE-CCC-ESTAB.aspx?'))
            return ['status' => 'ERROR', 'message' => 'Link não encontrado para cnpj '.$cnpj.' na uf '.$cod_uf];

        $ini = strpos($tabela,'NFE-CCC-ESTAB.aspx?');
        $link = trim(substr($tabela,$ini,500));
        $fim = strpos($link,'\');">');
        $link = trim(substr($link,0,$fim));




        /*** Carregar todos os dados do SEFAZ ***/
        try{
            $pag2 = file_get_contents("https://www.sefaz.rs.gov.br/NFE/".$link);
        }catch (Exception $e) {
            return ['status'=>"ERROR",'message'=>'CCC3: '.$e->getMessage()];
        }

        try{
            //Carregar a pagina HTML
            $doc = new DomDocument;
            $doc->validateOnParse = true;
            $doc->loadHtml($pag2);

            $data1 = array();
            $data1['nome'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_nomeEmpresa')->textContent );
            $data1['fantasia'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txNomeFantasia')->textContent );
            if ($data1['fantasia']=='Não informado') $data1['fantasia'] = '';
            $data1['cnpj'] = $doc->getElementById('ctl00_cphConteudo_txCNPJ')->textContent;
            $data1['cnpj_status'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txSitCNPJ')->textContent );

            $data2 = array();
            $data2['ie'] = $doc->getElementById('ctl00_cphConteudo_txIE')->textContent;
            $data2['ie_tipo'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_TipoIe')->textContent );
            $data2['ie_status'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_CodSitContrib')->textContent );
            $data2['ie_status_data'] = $doc->getElementById('ctl00_cphConteudo_txDtSitContrib')->textContent;


            $data2['atividade_data_inicio'] = $doc->getElementById('ctl00_cphConteudo_txDtIniAtiv')->textContent;
            $data2['atividade_data_fim'] = $doc->getElementById('ctl00_cphConteudo_txDtFimAtiv')->textContent;
            $data2['atividade_principal_CNAE'] = $doc->getElementById('ctl00_cphConteudo_txCnae')->textContent;

            $data2['regime_tributacao_icms'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txRegimeIcms')->textContent );
            $data2['informa_ie_dest'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txInfIeDestinatario')->textContent);
            $data2['porte_empresa'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txPorteEmpresa')->textContent );


            $data3 = array();
            $data3['uf'] = trim( substr($doc->getElementById('ctl00_cphConteudo_txUfLocal')->textContent,5,2));
            $data3['municipio'] = trim( substr(utf8_decode( $doc->getElementById('ctl00_cphConteudo_txMunIBGE')->textContent ),3,60) );
            $data3['municipio_cod'] = $doc->getElementById('ctl00_cphConteudo_txCodMunIBGE')->textContent;
            $data3['logradouro'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txLogradouro')->textContent );
            $data3['numero'] = $doc->getElementById('ctl00_cphConteudo_txNro')->textContent;
            $data3['complemento'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txComplemento')->textContent);
            $data3['bairro'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txBairro')->textContent);
            $data3['cep'] = utf8_decode( $doc->getElementById('ctl00_cphConteudo_txCEP')->textContent);


            $data = array();
            $data['status'] = 'OK';
            $data['dados'] = $data1;
            $data['contribuinte'] = $data2;
            $data['endereco'] = $data3;
        }catch (Exception $e) {
            return ['status'=>"ERROR",'message'=>'CCC4: '.$e->getMessage()];
        }

        return $data;
    }
}
