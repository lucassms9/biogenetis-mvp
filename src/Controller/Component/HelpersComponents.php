<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class HelpersComponents extends Component
{
    public function stringToNumber($string)
    {
    	return preg_replace('/\D/', '', $string);
    }
}