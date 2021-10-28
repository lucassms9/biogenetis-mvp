<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;
use App\Component\HelpersComponent;

class ExamesDataComponent extends Component
{
    public $components = ['Helpers'];
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->API_ROOT = env('EXAME_ENDPOINT');
    }

    public function save($hash, $content){
        $data = array("hash" => $hash, "content" => $content);
        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exame/save',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        return $response;
    }

    public function get($hash)
    {
        $data = array("hashs" => $hash);
        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exame/list',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $data_own = $this->Helpers->doDecrypt($response->body);
        return $data_own;
    }


    public function saveEncadeamento($hash, $content)
    {
        $data = array("hash" => $hash, "content" => $content);
        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exameencadeamento/save',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $data_own = $this->Helpers->doDecrypt($response->body);
        return $data_own;
    }

    public function getEncadeamento($encadeamentos)
    {
        $arr_hashs = [];
        if (is_array($encadeamentos)) {
            for ($i = 0; $i < sizeof($encadeamentos); $i++) {
                array_push($arr_hashs, $encadeamentos[$i]->hash);

            }
        } else {
            array_push($arr_hashs, $encadeamentos->hash);
        }
        $data = array("hashs" => $arr_hashs);
        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exameencadeamento/list',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);


        $result_hashs = json_decode($this->Helpers->doDecrypt($response->body));
        if (is_array($result_hashs)) {
            if (is_array($encadeamentos)) {
                for ($i = 0; $i < sizeof($encadeamentos); $i++) {
                    for ($z = 0; $z < sizeof($result_hashs); $z++) {
                        if ($result_hashs[$z]->hash == $encadeamentos[$i]->hash) {
                            $encadeamentos[$i]->resultado = $result_hashs[$z]->body;
                            array_splice($result_hashs, $z, 1);
                            break 1;
                        }
                    }
                }
            } else {
                for ($z = 0; $z < sizeof($result_hashs); $z++) {
                    if ($result_hashs[$z]->hash == $encadeamentos->hash) {
                        $encadeamentos->resultado = $result_hashs[$z]->body;
                        array_splice($result_hashs, $z, 1);
                        break 1;
                    }
                }
            }
        }
        return $encadeamentos;
    }

    public function getExamesResult($exames)
    {
        $arr_hashs = [];
        if (is_array($exames)) {
            for ($i = 0; $i < sizeof($exames); $i++) {
                array_push($arr_hashs, $exames[$i]->hash);
            }
        } else {
            array_push($arr_hashs, $exames->hash);
        }

        $data = array("hashs" => $arr_hashs);

        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exame/list',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        $result_hashs  = json_decode($this->Helpers->doDecrypt($response->body));


        if (is_array($result_hashs)) {
            if (is_array($exames)) {
                for ($i = 0; $i < sizeof($exames); $i++) {
                    for ($z = 0; $z < sizeof($result_hashs); $z++) {
                        if ($result_hashs[$z]->hash == $exames[$i]->hash) {
                            $exames[$i]->resultado = $result_hashs[$z]->body;
                            array_splice($result_hashs, $z, 1);
                            break 1;
                        }
                    }
                }
            } else {
                for ($z = 0; $z < sizeof($result_hashs); $z++) {
                    if ($result_hashs[$z]->hash == $exames->hash) {
                        $exames->resultado = $result_hashs[$z]->body;
                        array_splice($result_hashs, $z, 1);
                        break 1;
                    }
                }
            }
        }
        return $exames;
    }

    public function saveOrigem($hash, $content)
    {
        $data = array("hash" => $hash, "content" => $content);
        $body = json_encode($data);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'exame/save',  $body, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($body)]
        ]);
        return  '';
    }
}
