<?php

namespace App\Controller\Api;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Exception;
use Firebase\JWT\JWT;
use Cake\Routing\Router;


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
        $this->loadComponent('ExamesData');
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
            'contain' => ['Exames', 'Anamneses.Pacientes', 'Clientes', 'EntradaExames'],
            'conditions' => ['Anamneses.paciente_id' => $payload->id]
        ])->toList();

        $handle = [];

        foreach ($pedidos as $key => $pedido) {

            if (!empty($pedido->exame)) {
                $pedido->exame = $this->ExamesData->getExamesResult($pedido->exame);
            }

            $handle[] = [
                'clinica_nome' => $pedido->cliente->nome_fantasia ?? '-',
                'exame_nome' => $pedido->entrada_exame->nome ?? '-',
                'id' => $pedido['id'],
                'codigo' => $pedido['codigo_pedido'],
                'status' => $pedido['status'],
                'amostra_tipo' => $pedido['exame']['amostra_tipo'] ?? '-',
                'resultado' => $pedido['exame']['resultado'] ?? '-',
                'url_exame' => Router::url('/admin/pedidos/laudo-viwer/' . $pedido['id'], true)
            ];
        }

        $result['pedidos'] = $handle;

        $this->set(compact('result'));
    }
}
