<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
/**
 * Exames Controller
 *
 * @property \App\Model\Table\ExamesTable $Exames
 *
 * @method \App\Model\Entity\Exame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExamesController extends AppController
{


    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Exames');
    }


    public function import()
    {
         if ($this->request->is('post')) {

            try {

                if(!empty($this->request->getData('file'))){
                    $files = $this->request->getData('file');

                    foreach ($files as $key => $file) {
                        if($file['size'] > 0){
                             $exame = [
                                'paciente_id' => 1,
                                'amostra_id' => 1,
                                'created_by' => $this->Auth->user('id'),
                             ];

                             $exame_save = $this->Exames->newEntity();
                             $exame_save = $this->Exames->patchEntity($exame_save, $exame);

                             $this->Exames->save($exame_save);
                        }

                    }

                }
             } catch (Exception $e) {
                  $this->Flash->error(__('Tivemos algum problema ao enviar os Exames'));
            }
         }
    }

    public function relatorio()
    {

        $action = 'Relatório';
        $title = 'Amostras';

         if ($this->request->is('post')) {


            return $this->redirect(['controller' => 'amostras', 'action' => 'index', 'amostra_id' => $this->request->getData('amostra_id'),'data_init' => $this->request->getData('data_init'), 'data_fim' => $this->request->getData('data_fim'), 'lote' =>  $this->request->getData('lote') ]);

            // $settings = [];

            // if($this->Auth->user('user_type_id') == 3){
            //     $settings['Exames.created_by'] = $this->Auth->user('id');
            // }

            // if($this->Auth->user('user_type_id') == 2){
            //     $settings['Users.cliente_id'] = $this->Auth->user('cliente_id');
            // }

            //  if (!empty($this->request->getData('data_init'])){
            //         $data_de = $this->request->getData('data_init'];
            //         $settings['cast(Exames.created as date) >='] = $data_de;
            //  }

            // if (!empty($this->request->getData('data_fim'])){
            //         $data_ate = $this->request->getData('data_fim'];
            //         $settings['cast(Exames.created as date) >='] = $data_ate;
            //  }

            // if (!empty($this->request->getData('amostra_id'])){
            //      $settings['Exames.amostra_id'] = $this->request->getData('amostra_id'];
            // }

            // $exames = $this->Exames->find('all', [
            //         'conditions' => $settings ,
            //         'contain' => ['Amostras','Users.Clientes']])->toList();

            // $html = '<table border="1">';
            // $html .= '<tr>';
            // $html .= '<td>Data</td>';
            // $html .= '<td>ID Amostra</td>';
            // $html .= '<td>Técnico</td>';
            // $html .= '<td>UF</td>';
            // $html .= '<td>Idade</td>';
            // $html .= '<td>Sexo</td>';
            // $html .= '<td>Cliente</td>';
            // $html .= '<td>Resultado</td>';
            // $html .= '</tr>';

            // foreach ($exames as $key => $exame) {

            //     $html .= '<tr>';
            //     $html .= '<td>' . date_format($exame['created'], 'd-m-Y') . '</td>';
            //     $html .= '<td>' . $exame['amostra_id'] . '</td>';
            //     $html .= '<td>' . $exame['user']['nome_completo'] . '</td>';
            //     $html .= '<td>' . $exame['amostra']['uf'] . '</td>';
            //     $html .= '<td>' . $exame['amostra']['idade'] . '</td>';
            //     $html .= '<td>' . $exame['amostra']['sexo'] . '</td>';
            //     $html .= '<td>' . $exame['user']['cliente']['nome'] . '</td>';
            //     $html .= '<td>' . $exame['resultado'] . '</td>';
            //     $html .= '</tr>';

            // }

            // $html .= '</table>';

            // $arquivo = 'relatorios_amostras_'.date('Y-m-d-H-i-s');

            // header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            // header("Content-Disposition: attachment; filename=$arquivo.xls");  //File name extension was wrong
            // header("Expires: 0");
            // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header("Cache-Control: private",false);

            // echo utf8_decode($html);
            // exit;


         }

        $this->set(compact('action','title'));

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pacientes', 'Amostras'],
        ];
        $exames = $this->paginate($this->Exames);

        $this->set(compact('exames'));
    }

    /**
     * View method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => ['Pacientes', 'Amostras'],
        ]);

        $this->set('exame', $exame);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exame = $this->Exames->newEntity();
        if ($this->request->is('post')) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $pacientes = $this->Exames->Pacientes->find('list', ['limit' => 200]);
        $amostras = $this->Exames->Amostras->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'pacientes', 'amostras'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $pacientes = $this->Exames->Pacientes->find('list', ['limit' => 200]);
        $amostras = $this->Exames->Amostras->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'pacientes', 'amostras'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exame = $this->Exames->get($id);
        if ($this->Exames->delete($exame)) {
            $this->Flash->success(__('The exame has been deleted.'));
        } else {
            $this->Flash->error(__('The exame could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
