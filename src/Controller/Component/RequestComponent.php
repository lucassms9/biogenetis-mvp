<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class RequestComponent extends Component
{


    public function getBody()
    {
        $json = file_get_contents('php://input');
        $values = json_decode($json, true);
        return $values;
    }
}
