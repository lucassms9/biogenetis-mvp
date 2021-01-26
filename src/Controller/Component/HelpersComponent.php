<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class HelpersComponent extends Component
{


    public function stringToNumber($string)
    {
    	return preg_replace('/\D/', '', $string);
    }
    public function doEncrypt($string){   
        $encryptionMethod = env('CRYPT_METHOD');
        $secret = env('CRYPT_SECRET');
        $iv = env('CRYPT_IV');
        $encryptedMessage = openssl_encrypt($string, $encryptionMethod, $secret,0,$iv);
    	return $encryptedMessage;
    }

    public function doDecrypt($string){
        $encryptionMethod = env('CRYPT_METHOD');
        $secret = env('CRYPT_SECRET');
        $iv = env('CRYPT_IV');
        $decryptedMessage = openssl_decrypt($string, $encryptionMethod, $secret,0,$iv);
    	return $decryptedMessage;
    }
}
