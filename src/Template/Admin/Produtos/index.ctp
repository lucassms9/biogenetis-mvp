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
                                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('nome') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('descricao') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('valor_lab_conveniado') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('valor_lab_nao_conveniado') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('tipo_exame') ?></th>
                                    <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($produtos as $produto): ?>
                                        <tr>
                                        <td><?= $this->Number->format($produto->id) ?></td>
                                        <td><?= h($produto->nome) ?></td>
                                        <td><?= h($produto->descricao) ?></td>
                                        <td><?= $this->Number->format($produto->valor_lab_conveniado) ?></td>
                                        <td><?= $this->Number->format($produto->valor_lab_nao_conveniado) ?></td>
                                        <td><?= h($produto->tipo_exame) ?></td>
                                        <td class="actions">
                                        <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $produto->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $produto->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $produto->id)]) ?>
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
