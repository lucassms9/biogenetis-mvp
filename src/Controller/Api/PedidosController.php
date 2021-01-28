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
class PedidosController extends RestController
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
        $this->loadModel('Pedidos');
        $this->loadModel('Exames');

        $authorization = $this->request->getHeaderLine('Authorization');
        $authorization = explode(' ', $authorization);
        $token = $authorization[1];
        $config = include ROOT . DS . 'config' . DS . 'rest.php';
        $payload = JWT::decode($token, $config['Rest']['jwt']['key'], [$config['Rest']['jwt']['algorithm']]);

        $this->payload = $payload;
    }

    public function index()
    {
        $result = [];

        $body = $this->body;
        $token = $this->token;

        $payload = $this->payload;

        $pedidos = $this->Pedidos->find('all', [
            'contain' => ['Exames', 'Anamneses.Pacientes'],
            'conditions' => ['Anamneses.paciente_id' => $payload->id]
        ])->toList();

        $handle = [];

        foreach ($pedidos as $key => $pedido) {
            $handle[] = [
                'id' => $pedido['id'],
                'codigo' => $pedido['codigo_pedido'],
                'status' => $pedido['status'],
                'amostra_tipo' => $pedido['exame']['amostra_tipo'] ?? '-',
                'resultado' => $pedido['exame']['resultado'] ?? '-',
            ];
        }

        $result['pedidos'] = $handle;

        $this->set(compact('result'));
    }
}
