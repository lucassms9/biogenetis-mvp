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

        $response = $this->client->get('http://www.geonames.org/childrenJSON?geonameId=3469034', [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }

    public function getCity($uf)
    {

        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$uf.'/municipios?orderBy=nome', [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }

    public function getStateById($id)
    {

        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }
    public function getCityById($id)
    {

        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios/'.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }
}
