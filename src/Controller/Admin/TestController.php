<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * UserTypes Controller
 *
 * @property \App\Model\Table\UserTypesTable $UserTypes
 *
 * @method \App\Model\Entity\UserType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TestController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->API_ROOT = env('USER_ENDPOINT');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {


        $ch = curl_init();

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL,$this->API_ROOT."getpaciente");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"");

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        debug($server_output);

        curl_close ($ch);

        die;
    }

}
