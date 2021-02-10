<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;

class IbgeComponent extends Component
{

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->client = new Client();
    }

    public function getStates()
    {
        return [];
        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }

    public function getCity($uf)
    {
        return [];
        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$uf.'/municipios?orderBy=nome', [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }

    public function getStateById($id)
    {
        return [];
        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }
    public function getCityById($id)
    {
        return [];
        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios/'.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }
}