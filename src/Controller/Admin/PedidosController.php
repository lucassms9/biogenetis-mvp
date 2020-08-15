<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Pedidos Controller
 *
 * @property \App\Model\Table\PedidosTable $Pedidos
 *
 * @method \App\Model\Entity\Pedido[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidosController extends AppController
{

    public function checkBarCode($pedido_id = null)
    {
        $pedido = $this->Pedidos->get($pedido_id, [
            'contain' => ['Anamneses.Pacientes','EntradaExames'],
        ]);

        $retorno = [
            'error' => 1,
            'barcode' => ''
        ];

        if($pedido && $pedido->entrada_exame && $pedido->anamnese){
            $barcode = [
                'tipo_exame' => $pedido->entrada_exame->tipo_exame,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'paciente_nome' => $pedido->anamnese->paciente->nome,
                'paciente_data_nasc' => $pedido->anamnese->paciente->data_nascimento->i18nFormat('dd/MM/yyyy'),
                'data_sistema' => $pedido->created->i18nFormat('dd/MM/yyyy'),
                'codigo_pedido' => $pedido->codigo_pedido,
            ];

            $retorno['error'] = 1;
            $retorno['barcode'] = $barcode;
        }

        echo json_encode($retorno);
        exit;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Anamnese', 'Amostras', 'Clientes'],
        ];
        $pedidos = $this->paginate($this->Pedidos);

        $this->set(compact('pedidos'));
    }

    /**
     * View method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => ['Anamnese', 'Amostras', 'Clientes'],
        ]);

        $this->set('pedido', $pedido);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $pedido = $this->Pedidos->newEntity();
        if ($this->request->is('post')) {
            $pedido = $this->Pedidos->patchEntity($pedido, $this->request->getData());
            if ($this->Pedidos->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $anamnese = $this->Pedidos->Anamnese->find('list', ['limit' => 200]);
        $amostras = $this->Pedidos->Amostras->find('list', ['limit' => 200]);
        $clientes = $this->Pedidos->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'anamnese', 'amostras', 'clientes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pedido = $this->Pedidos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pedido = $this->Pedidos->patchEntity($pedido, $this->request->getData());
            if ($this->Pedidos->save($pedido)) {
                $this->Flash->success(__('The pedido has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The pedido could not be saved. Please, try again.'));
        }
        $anamnese = $this->Pedidos->Anamnese->find('list', ['limit' => 200]);
        $amostras = $this->Pedidos->Amostras->find('list', ['limit' => 200]);
        $clientes = $this->Pedidos->Clientes->find('list', ['limit' => 200]);
        $this->set(compact('pedido', 'anamnese', 'amostras', 'clientes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pedido id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pedido = $this->Pedidos->get($id);
        if ($this->Pedidos->delete($pedido)) {
            $this->Flash->success(__('The pedido has been deleted.'));
        } else {
            $this->Flash->error(__('The pedido could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
