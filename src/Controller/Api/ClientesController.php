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

        $this->client = new Client();
    }

    public function listAll()
    {
        $result = [];

        $body = $this->body;
        $payload = $this->payload;

        $resPaciente  = $this->PacientesData->getByHash($payload->hash);
        if (!$resPaciente) {
            throw new Exception('Paciente não encontrado', 400);
        }

        $resPaciente = json_decode($resPaciente);
        $conditions = ['ativo' => 1];
        if (!empty($this->request->getQuery('cep'))) {
            $conditions['cep'] = $this->request->getQuery('cep');
        }

        $lat = $this->request->getQuery('lat');
        $long = $this->request->getQuery('long');

        $getDistance = true;

        $clientes = $this->Clientes->find('all', [
            'conditions' => $conditions
        ])->toList();

        $handle = [];

        foreach ($clientes as $key => $cliente) {
            $data = [
                'id' => $cliente['id'],
                'nome' => $cliente['nome_fantasia'],
                'endereco' => $cliente['endereco'],
                'bairro' => $cliente['bairro'],
                'cidade' => $cliente['cidade'],
                'uf' => $cliente['uf'],
                'cep' => $cliente['cep'],
                'handleCity' => $cliente['cidade'] . '-' . $cliente['uf'],
                'responsavel_telefone' => $cliente['telefone_contato_app'],
            ];

            $handle[] = $data;
        }

        $result['clientes'] = $handle;

        $this->set(compact('result'));
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

        $conditions = ['ativo' => 1];
        if (!empty($this->request->getQuery('cep'))) {
            $conditions['cep'] = $this->request->getQuery('cep');
        } else {
            $conditions['uf'] = $resPaciente->uf;
        }

        $clientes = $this->Clientes->find('all', [
            'conditions' => $conditions
        ])->toList();

        $handle = [];

        $lat = $this->request->getQuery('lat');
        $long = $this->request->getQuery('long');
        $getDistance = true;

        foreach ($clientes as $key => $cliente) {

            $data = [
                'id' => $cliente['id'],
                'nome' => $cliente['nome_fantasia'],
                'endereco' => $cliente['endereco'],
                'bairro' => $cliente['bairro'],
                'cidade' => $cliente['cidade'],
                'uf' => $cliente['uf'],
                'cep' => $cliente['cep'],
                'handleCity' => $cliente['cidade'] . '-' . $cliente['uf'],
                'responsavel_telefone' => $cliente['telefone_contato_app'],
            ];

            $handle[] = $data;
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
