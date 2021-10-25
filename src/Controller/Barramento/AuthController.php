<?php

namespace App\Controller\Barramento;

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

        $this->body = $this->Request->getBody();
        $this->API_ROOT = env('USER_ENDPOINT');
        $this->loadModel('Pacientes');
        $this->loadModel('AuthIntegrations');
    }


    public function login()
    {
        $result = [];

        $body = $this->body;

        $checkAuth = $this->AuthIntegrations->find('all', [
            'conditions' => [
                'user' => $body['user'],
                'password' => md5($body['password'])
                ]
        ])->first();

        if (!$checkAuth) {
            throw new Exception('usúario inválido', 400);
        }

        //atualiza token user
        $payload = [
            'id' => $checkAuth->id,
            'user' => $checkAuth->user,
            'cliente_id' => $checkAuth->cliente_id,
            'exp' => '300',
        ];

        $token = \Rest\Utility\JwtToken::generate($payload);


        $result['token'] = $token;
        $result['user'] = $checkAuth->user;


        $this->set(compact('result'));
    }
}
