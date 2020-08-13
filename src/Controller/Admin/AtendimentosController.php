<?php
    namespace App\Controller\Admin;

    use App\Controller\AppController;

    class AtendimentosController extends AppController
    {

        public function initialize()
        {
            parent::initialize();

            $this->loadComponent('Helpers');
            $this->loadModel('Anamneses');
            $this->loadModel('Pedidos');

        }

        public function index()
        {
            $action = 'Ver Todos';
            $title = 'Atendimento';

            $query = $this->request->getQuery();
            $rows = [];

            $pedidos = $this->paginate($this->Pedidos,[
                'contain' => ['Anamneses.Pacientes'],
                'conditions' => ['Pedidos.status' => 'EmAtendimento']
            ]);

            $this->set(compact('action','title','pedidos'));
        }


        public function showpedido($id, $tab_current = 'paciente')
        {
            $action = 'Detalhe';
            $title = 'Pedido';

            $pedido = $this->Pedidos->get($id,[
                'contain' => ['Anamneses.Pacientes'],
            ]);

            $this->set(compact('action','title','pedido','tab_current'));

        }


    }
