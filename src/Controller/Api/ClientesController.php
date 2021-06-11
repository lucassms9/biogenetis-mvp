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

        $this->API_GOOGLE = 'AIzaSyBWskOntbUJJcH94vl8jlL1uogbMo4oc9o';
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

    private function posicaoToEnderecoTo($lat, $long)
    {
        try {
            $latlng = $lat . "," . $long;

            $key_google_maps = $this->API_GOOGLE;

            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latlng}&sensor=true&key={$key_google_maps}";

            $res = $this->client->get($url);
            $res = json_decode($res->body, true);



            $endereco = $res['results'][0]['formatted_address'];

            if (count(explode(' ', $endereco)) <= 2) {
                throw new Exception('error');
            }
            $endereco = str_replace(" ", "+", $endereco);
            return $endereco;
        } catch (\Exception $th) {
            return 'error';
        }
    }

    private function getDistance($enderecoUser, $enderecoLab)
    {
        // $endereco_completo = $endereco . '+' . $cidade . '+' . $estado;

        $key_google_maps = $this->API_GOOGLE;


        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$enderecoUser}&destinations={$enderecoLab}&mode=driving&sensor=false&key={$key_google_maps}";

        $res = $this->client->get($url);
        $res = json_decode($res->body, true);

        $distanciaFind = $res['rows'][0]['elements'][0]['distance']['value'];
        return $distanciaFind;
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

        if (!empty($lat)) {
            $enderecoUser = $this->posicaoToEnderecoTo($lat, $long);

            if ($enderecoUser === 'error') {
                $getDistance = false;
            }
        }


        $clientes = $this->Clientes->find('all', [
            'conditions' => $conditions
        ])->toList();

        $handle = [];

        foreach ($clientes as $key => $cliente) {
            $endereco_lab = $cliente['endereco'] . '+' . $cliente['cidade'] . '+' . $cliente['uf'];
            $handle_distance = '';
            if (!empty($lat) && $getDistance) {
                $handle_distance = $this->getDistance($enderecoUser, $endereco_lab);

                $distancia_label = '';

                if ($handle_distance > 1000) {
                    $distancia_label = intval($handle_distance / 1000) . ' km';
                } else {
                    $distancia_label = $handle_distance . ' m';
                }
            }
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

            if (!empty($lat) && $getDistance) {
                $data['distance'] = $this->getDistance($enderecoUser, $endereco_lab);

                $data['distancia_label'] = $distancia_label;
            } else {
                $data['distance'] = 0;

                $data['distancia_label'] = '';
            }

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

        if (!empty($lat)) {
            $enderecoUser = $this->posicaoToEnderecoTo($lat, $long);

            if ($enderecoUser === 'error') {
                $getDistance = false;
            }
        }

        foreach ($clientes as $key => $cliente) {
            $endereco_lab = $cliente['endereco'] . '+' . $cliente['cidade'] . '+' . $cliente['uf'];
            $handle_distance = '';

            if (!empty($lat) && $getDistance) {
                $handle_distance = $this->getDistance($enderecoUser, $endereco_lab);

                $distancia_label = '';

                if ($handle_distance > 1000) {
                    $distancia_label = intval($handle_distance / 1000) . ' km';
                } else {
                    $distancia_label = $handle_distance . ' m';
                }
            }
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

            if (!empty($lat)  && $getDistance) {
                $data['distance'] = $this->getDistance($enderecoUser, $endereco_lab);

                $data['distancia_label'] = $distancia_label;
            } else {
                $data['distance'] = 0;

                $data['distancia_label'] = '';
            }

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
