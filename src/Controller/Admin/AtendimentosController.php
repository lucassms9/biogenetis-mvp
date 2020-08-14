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
                'contain' => ['Anamneses.Pacientes','EntradaExames','Vouchers'],
            ]);

            $paciente = $pedido->anamnese->paciente;
            $anamnese = $pedido->anamnese;

            $pagamento = $pedido->entrada_exame;

            $sexos = $this->sexos;

            $this->set(compact('action','title','pedido','tab_current','sexos','paciente','anamnese','pagamento'));

        }

        public function pagamento($pedido_id = null)
        {
            $req = $this->request->getData();
            // debug($req);
            // die;
            if(!empty($req['voucher_cod']) && !empty($req['pedido_id']) ){

                $id_valid = $this->Vouchers->find('all',[
                    'conditions' => ['codigo' => $req['voucher_cod'], 'used' => 0]
                ])->first();

                if(!empty($id_valid)){
                    $pedido = $this->Pedidos->get($req['pedido_id'],[
                        'contain' => ['EntradaExames']
                    ]);
                    $pedido->voucher_id = $id_valid->id;
                    $this->Pedidos->save($pedido);

                    $save_extrato = $this->ExtratoSaldo->newEntity([
                        'voucher_id' => $id_valid->id,
                        'type' => 'D',
                        'valor' => $pedido->entrada_exame->valor_laboratorio_conveniado,
                        'created_by' => $this->Auth->user()->id,
                    ]);
                    $save_extrato = $this->ExtratoSaldo->save($save_extrato);

                    $this->Flash->success(__('Voucher Inserido com sucesso!'));
                } else {
                    $this->Flash->error(__('Voucher Inválido!'));
                }
            } else {
                $this->Flash->error(__('Voucher Inválido!'));

            }
            return $this->redirect(['action' => 'showpedido/'.$req['pedido_id'].'/pagamento']);
        }


    }
