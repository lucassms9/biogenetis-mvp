<?php echo $this->element('admin/home/index');?>


<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"><?= $this->Paginator->sort('codigo_pedido', 'Código do Pedido') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('status', 'Status') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('nome', 'Nome do Paciente') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('cpf','CPF do Paciente') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('celular', 'Celular do Paciente') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('created', 'Criação do Pedido') ?></th>
                                    <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php
                                      foreach ($pedidos as $pedido): ?>
                                        <tr>
                                            <td><?= h($pedido->codigo_pedido) ?></td>
                                            <td><?= h($pedido->status) ?></td>
                                            <td><?= h($pedido->anamnese->paciente->nome) ?></td>
                                            <td><?= h($pedido->anamnese->paciente->cpf) ?></td>
                                            <td><?= h($pedido->anamnese->paciente->celular) ?></td>
                                            <td><?= h($pedido->created) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'showpedido', $pedido->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $pedido->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $pedido->id)]) ?>
                                                <?php if($pedido->status === 'Finalizado'): ?>
                                                    <?= $this->Html->link(__('<i class="mdi mdi-file-document-box-check"></i>'), ['action' => 'laudo', $pedido->id], ['escape' => false]) ?>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>


                         <div class="mt-4">
                            <div class="paginator">
                                <ul class="pagination pagination pagination-rounded justify-content-center mb-0">
                                    <?= $this->Paginator->prev('<i class="mdi mdi-chevron-left"></i>',['escape' => false,'']) ?>

                                    <?= $this->Paginator->numbers() ?>
                                    <?= $this->Paginator->next('<i class="mdi mdi-chevron-right"></i>',['escape' => false,'']) ?>
                                </ul>
                                <p><?= $this->Paginator->counter(['format' => __('Pagina {{page}} of {{pages}}, Listando {{current}} registro(s) de {{count}} total')]) ?></p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
</div>
