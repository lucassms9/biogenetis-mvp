<?php
    namespace App\Controller\Admin;

    use App\Controller\AppController;

    class AtendimentosController extends AppController
    {

        public function initialize()
        {
            parent::initialize();


            $this->sexos = [
                'M' => 'M',
                'F' => 'F'
            ];

            $this->loadComponent('Helpers');
            $this->loadModel('Anamneses');
            $this->loadModel('Pedidos');
            $this->loadModel('Vouchers');
            $this->loadModel('ExtratoSaldo');
            $this->loadModel('EntradaExames');

        }


    }
