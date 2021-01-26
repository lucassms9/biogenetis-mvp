<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;
use App\Component\HelpersComponent;
class PacientesDataComponent extends Component
{
    public $components = ['Helpers'];
    public function getPacientes($string){   
        $API_ROOT = env('USER_ENDPOINT');
        $http = new Client(); 
        $response = $http->post($API_ROOT . 'paciente/listbyhash',  $string, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen( $string)] ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->body);
    	return  $resp_decoded;
    }
    public function getCheckCPF($string){   
        $API_ROOT = env('USER_ENDPOINT');
        $cpf = array("cpf" => $string);
        $body = json_encode($cpf);
        $http = new Client(); 
        $response = $http->post($API_ROOT . 'paciente/check',  $body , [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)] ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->body);
    	return  $resp_decoded;
    }
    public function getByHash($string){   
        $API_ROOT = env('USER_ENDPOINT');
        $hash = array("hash" => $string);
        $body = json_encode($hash);
        $http = new Client(); 
        $response = $http->post($API_ROOT . 'paciente/getbyhash',  $body , [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)] ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->body);
    	return  $resp_decoded;
    }
    public function getByFilter($nome,$cpf){   
        $API_ROOT = env('USER_ENDPOINT');
        $hash = array("nome" => $nome, "cpf" => $cpf);
        $body = json_encode($hash);
        $http = new Client(); 
        $response = $http->post($API_ROOT . 'paciente/filter',  $body , [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)] ]);
        $resp_decoded = $this->Helpers->doDecrypt($response->body);
    	return  $resp_decoded;
    }
    public function returnPaciente($hash,$pacientes){
        for($i =0;$i < sizeof($pacientes);$i++){
            if($pacientes[$i]['hash'] == $hash){
                return $pacientes[$i];
                exit;
            }
        }
    }
}
