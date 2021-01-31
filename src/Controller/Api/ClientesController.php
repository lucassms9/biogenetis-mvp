<?php

namespace App\Controller\Api;

use Rest\Controller\RestController;
use Cake\Http\Client;
use Exception;

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
    }

    public function index()
    {
        $result = [];

        $body = $this->body;
        $clientes = $this->Clientes->find('all', [
            'conditions' => ['ativo' => 1]
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
                'responsavel_telefone' => $cliente['responsavel_telefone'],
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
