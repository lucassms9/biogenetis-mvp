<?php

namespace App\Controller\Api;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Cake\I18n\Number;
use Exception;

/**
 * Foo Controller
 *
 */
class AuthController extends RestController
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
        $this->loadComponent('PushNotification');

        $this->body = $this->Request->getBody();
        $this->API_ROOT = env('USER_ENDPOINT');
        $this->loadModel('Pacientes');
    }


    public function login()
    {
        $result = [];

        $body = $this->body;

        $resPaciente  = $this->PacientesData->getCheckCPFOrEmail(
            '',
            $body['email']
        );

        if (!$resPaciente) {
            throw new Exception('E-mail não encontrado', 400);
        }

        $res = json_decode($resPaciente);

        $paciente = $this->Pacientes->find('all', [
            'conditions' => ['hash' => $res->hash, 'senha' => md5($body['senha'])]
        ])->first();

        if (!$paciente) {
            throw new Exception('Senha inválida', 400);
        }

        //atualiza token user
        $payload = [
            'id' => $paciente->id,
            'hash' => $paciente->hash
        ];

        $token = \Rest\Utility\JwtToken::generate($payload);
        $paciente->token_mobile = $token;
        $this->Pacientes->save($paciente);

        $result['token'] = $token;
        $result['paciente'] = ['nome' => $res->nome,'id' => $paciente->id];


        $this->set(compact('result'));
    }

    public function sendPush(){


        $push = [
            'paciente_id' => 113,
            'title' => 'Você tem exame concluído!',
            'body' => 'Verifique o resultado do seu exame!'
        ];
        $this->PushNotification->send($push);

    }

    public function saveToken(){
        $body = $this->body;
        $payload = $this->payload;
        $result = [];

        $paciente = $this->Pacientes->get($body['userId']);

        if(!empty($paciente)){
            $paciente->token_push = $body['token'];
            $this->Pacientes->save($paciente);
            $this->PushNotification->subscribe($paciente->id,$body['token']);
        }

        $this->set(compact('result'));
    }

    public function add()
    {
        $result = [];

        $body = $this->body;


        $resPaciente  = $this->PacientesData->getCheckCPFOrEmail(
            $body['cpf'],
            $body['email']
        );


        if (!empty($resPaciente)) {
            throw new Exception('CPF ou E-mail já cadastrado', 400);
        }


        $body["nacionalidade"] = "BRASILEIRA";
        $body["pais_residencia"] = "BRASIL";

        $json = json_encode($body);
        $http = new Client();
        $response = $http->post($this->API_ROOT . 'paciente/create', $json, [
            'headers' => ['Content-Type' => 'application/json', 'Content-Length' => strlen($json)]
        ]);
        $resp_code = $response->getStatusCode();
        $jst_r  = json_decode($response->getStringBody());

        $data_user['hash'] = $jst_r->hash;
        $data_user['senha'] = md5($body['senha']);


        $paciente = $this->Pacientes->newEntity();
        $paciente = $this->Pacientes->patchEntity($paciente, $data_user);
        $paciente = $this->Pacientes->save($paciente);

        //atualiza token user
        $payload = [
            'id' => $paciente->id,
            'hash' => $paciente->hash
        ];

        $token = \Rest\Utility\JwtToken::generate($payload);
        $paciente->token_mobile = $token;
        $this->Pacientes->save($paciente);

        $result['token'] = $token;
        $result['paciente'] = ['nome' => $body['nome'],'id' => $paciente->id,];

        $this->set(compact('result'));
    }

    public function recover()
    {
        $result = [];

        $body = $this->body;

        $resPaciente  = $this->PacientesData->getCheckCPFOrEmail(
            '',
            $body['email']
        );

        if (!$resPaciente) {
            throw new Exception('E-mail não encontrado', 400);
        }

        $res = json_decode($resPaciente);

        $paciente = $this->Pacientes->find('all', [
            'conditions' => ['hash' => $res->hash]
        ])->first();

        $senha_temp = rand(100000, 999999);
        $paciente->senha = md5($senha_temp);

        $this->Pacientes->save($paciente);

        $dadosEmail = array();
        $dadosEmail['from'] = ['contato@testecovidexpress.com.br' => 'Covid Express'];
        $dadosEmail['to'] = $res->email;
        // $dadosEmail['cc'] = 'lucas.santos@dedtechsolutions.com.br';
        $dadosEmail['subject'] = 'Recuperar Senha';

        $dadosEmail['message'] = 'sua senha temporária é: ' . $senha_temp;

        $this->Email->sendEmail($dadosEmail);

        $result['message'] = 'Enviamos uma senha temporário no seu E-mail.';
        $this->set(compact('result'));
    }
}
