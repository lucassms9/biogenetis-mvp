<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Client;

class GeoNamesComponent extends Component
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

        $handles = json_decode($response->getStringBody());

        $states = [];

        foreach ($handles->geonames as $key => $item) {

            $states[] = [
                'sigla' => $item->name,
                'id' => $item->geonameId
            ];
        }
        return $states;
    }

    public function getCity($uf)
    {

        $response = $this->client->get('http://www.geonames.org/childrenJSON?geonameId='.$uf, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);
        $handles = json_decode($response->getStringBody());

        $cites = [];

        foreach ($handles->geonames as $key => $item) {
            $cites[] = [
                'id' => $item->name,
                'nome' => $item->name,
            ];
        }

        return $cites;
    }

    public function getStateById($id)
    {

        $response = $this->client->get('http://www.geonames.org/childrenJSON?geonameId='.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);
        $handles = json_decode($response->getStringBody());

        return $handles->geonames[0]->adminCodes1->ISO3166_2;
    }
    public function getCityById($id)
    {

        $response = $this->client->get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios/'.$id, [
            'headers' => ['Content-Type' => 'application/json', ]
        ]);

        return json_decode($response->getStringBody());
    }
}
