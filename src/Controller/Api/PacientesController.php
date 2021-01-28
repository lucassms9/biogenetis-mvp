<?php

namespace App\Controller\Api;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Exception;
use Firebase\JWT\JWT;

/**
 * Foo Controller
 *
 */
class PacientesController extends RestController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Request');
        $this->loadComponent('PacientesData');
        $this->loadComponent('Email');

        $this->body = $this->Request->getBody();
        $this->API_ROOT = env('USER_ENDPOINT');
        $this->loadModel('Pacientes');

        $authorization = $this->request->getHeaderLine('Authorization');
        $authorization = explode(' ', $authorization);
        $token = $authorization[1];
        $config = include ROOT . DS . 'config' . DS . 'rest.php';
        $payload = JWT::decode($token, $config['Rest']['jwt']['key'], [$config['Rest']['jwt']['algorithm']]);

        $this->payload = $payload;
    }

    public function edit()
    {
        $result = [];

        $body = $this->body;

        $payload = $this->payload;

        $resPaciente  = $this->PacientesData->getByHash($payload->hash);

        if (!$resPaciente) {
            throw new Exception('Paciente não encontrado', 400);
        }
        $res = json_decode($resPaciente);

        if ($this->request->is('post')) {

            $body['hash'] = $payload->hash;

            $body["nacionalidade"] = "BRASILEIRA";
            $body["pais_residencia"] = "BRASIL";

            $json = json_encode($body);
            $http = new Client();
            $response = $http->post($this->API_ROOT . 'paciente/update', $json, [
                'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($json)]
            ]);
            $jst_r  = json_decode($response->getStringBody());



            if (!empty($body['senha'])) {
                $paciente = $this->Pacientes->find('all', [
                    'conditions' => ['hash' => $payload->hash]
                ])->first();

                $paciente->senha = md5($body['senha']);
                $paciente = $this->Pacientes->save($paciente);
            }
        }

        $result['paciente'] = $res;

        $this->set(compact('result'));
    }
}
