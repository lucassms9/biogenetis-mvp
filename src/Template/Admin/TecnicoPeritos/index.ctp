<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

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
                                        <th scope="col"><?= $this->Paginator->sort('cpf') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('celular') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('numero_crbio') ?></th>
                                        <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($tecnicoPeritos as $tecnicoPerito): ?>
                                        <tr>
                                            <td><?= $this->Number->format($tecnicoPerito->id) ?></td>
                                            <td><?= h($tecnicoPerito->nome) ?></td>
                                            <td><?= h($tecnicoPerito->cpf) ?></td>
                                            <td><?= h($tecnicoPerito->email) ?></td>
                                            <td><?= h($tecnicoPerito->celular) ?></td>
                                            <td><?= h($tecnicoPerito->numero_crbio) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $tecnicoPerito->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $tecnicoPerito->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $tecnicoPerito->id)]) ?>

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
