<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;
use App\Component\HelpersComponent;

class PacientesDataComponent extends Component
{
    public $components = ['Helpers'];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->API_ROOT = env('USER_ENDPOINT');
    }

    public function getPacientes($string)
    {
        // $API_ROOT = env('USER_ENDPOINT');
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/listbyhash',  $string, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($string)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }
    
    
    public function getByCpfOrNameCroqui($hashs,$cpf,$nome)
    {
        
        $req = array("hashs" => $hashs, "cpf" => $this->Helpers->doEncrypt($cpf), 'nome' => $this->Helpers->doEncrypt($nome));
      
        $body = json_encode($req);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/cpforemail',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }


    

    public function getCheckCPF($string){
        $string =  $this->Helpers->stringToNumber($string);
        $cpf = array("cpf" => $this->Helpers->doEncrypt($string));
        $body = json_encode($cpf);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/check',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }
    public function getCheckCPFOrEmail($cpf, $email)
    {
        $req = array("cpf" => $this->Helpers->doEncrypt($cpf), 'email' => $this->Helpers->doEncrypt($email));

        $body = json_encode($req);

        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/checkEmailOrCpf/',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }
    public function getByHash($string)
    {
        $hash = array("hash" => $string);
        $body = json_encode($hash);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/getbyhash',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }
    public function getByFilter($nome, $cpf)
    {
        $cpf_tratado =  $this->Helpers->stringToNumber($cpf);
        $hash = array("nome" => $this->Helpers->doEncrypt($nome), "cpf" => $this->Helpers->doEncrypt($cpf_tratado));
        $body = json_encode($hash);
        $http = new Client();
        $response = $http->post($this->API_ROOT  . 'paciente/filter',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->getStringBody());
        return  $resp_decoded;
    }
    public function returnPaciente($hash, $pacientes)
    {
        for ($i = 0; $i < sizeof($pacientes); $i++) {
            if ($pacientes[$i]['hash'] == $hash) {
                return $pacientes[$i];
                exit;
            }
        }
    }
}
