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
class ClientesController extends RestController
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
        $this->loadModel('Clientes');
        $this->loadModel('EntradaExames');
        $authorization = $this->request->getHeaderLine('Authorization');
        $authorization = explode(' ', $authorization);

        if (!empty($authorization[0])) {
            $token = $authorization[1];
            $config = include ROOT . DS . 'config' . DS . 'rest.php';
            $payload = JWT::decode($token, $config['Rest']['jwt']['key'], [$config['Rest']['jwt']['algorithm']]);
            $this->payload = $payload;
        }
    }

    public function index()
    {
        $result = [];

        $body = $this->body;
        $payload = $this->payload;

        $resPaciente  = $this->PacientesData->getByHash($payload->hash);
        if (!$resPaciente) {
            throw new Exception('Paciente não encontrado', 400);
        }

        $resPaciente = json_decode($resPaciente);

        $clientes = $this->Clientes->find('all', [
            'conditions' => ['ativo' => 1, 'uf' => $resPaciente->uf]
        ])->toList();

        $handle = [];

        foreach ($clientes as $key => $cliente) {
            $handle[] = [
                'id' => $cliente['id'],
                'nome' => $cliente['nome_fantasia'],
                'endereco' => $cliente['endereco'],
                'bairro' => $cliente['bairro'],
                'cidade' => $cliente['cidade'],
                'uf' => $cliente['uf'],
                'handleCity' => $cliente['cidade'] . '-' . $cliente['uf'],
                'responsavel_telefone' => $cliente['telefone_contato_app'],
            ];
        }

        $result['clientes'] = $handle;

        $this->set(compact('result'));
    }

    public function getAllServices()
    {
        $exams = $this->EntradaExames->find('all', [
            'conditions' => []
        ])->toList();

        $result['servicos'] = $exams;

        $this->set(compact('result'));
    }
}
