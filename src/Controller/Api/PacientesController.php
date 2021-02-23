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
        $this->loadModel('Anamneses');
        $this->loadModel('Clientes');
        $this->loadModel('Pedidos');

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



    public function createAnamnese()
    {
        $result = [];

        $body = $this->body;
        $payload = $this->payload;

        $cliente = $this->Clientes->find('All', [
            'conditions' => ['id' => $body['cliente_id']]
        ])->first();

        $body['paciente_id'] = $payload->id;
        $body['status'] = 'completed';
        $body['cliente_id'] = $cliente->id;

        $anamnese = $this->Anamneses->newEntity();
        $anamnese = $this->Anamneses->patchEntity($anamnese, $body);
        $anamnese = $this->Anamneses->save($anamnese);

        if (empty($anamnese)) {
            throw new Exception('Erro ao criar anamnese');
        }

        //criacao de pedido
        $dadaos_pedido = [
            'anamnese_id' => $anamnese->id,
            'cliente_id' => $cliente->id,
            'tipo_pagamento' => $cliente->tipo_cobranca,
            'created_by' => $body['paciente_id'],
            'entrada_exame_id' => $body['entrada_exame_id'],
            'status' => 'EmAtendimento',
        ];



        $pedido = $this->Pedidos->newEntity();
        $pedido = $this->Pedidos->patchEntity($pedido, $dadaos_pedido);
        $pedido = $this->Pedidos->save($pedido);

        $log = [
            'codigo_pedido' => $pedido->codigo_pedido,
            'user_id' => $this->Auth->user('id'),
            'status_anterior' =>  '',
            'status_atual' => 'EmAtendimento',
        ];
        $this->TrackingPedidos->createLog($log);

        if (empty($pedido)) {
            throw new Exception('Erro ao criar pedido');
        }
        $result['pedido'] = $pedido;

        $this->set(compact('result'));
    }


}
