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
                                    <th scope="col"><?= $this->Paginator->sort('foto_url') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('qtde_posi_placa') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('tipo_exame_recomendado') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('tipo_equipament_recomendado') ?></th>
                                    <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($croquis as $croqui): ?>
                                        <tr>
                                        <td><?= $this->Number->format($croqui->id) ?></td>
                                        <td><?= h($croqui->foto_url) ?></td>
                                        <td><?= $this->Number->format($croqui->qtde_posi_placa) ?></td>
                                        <td><?= h($croqui->tipo_exame_recomendado) ?></td>
                                        <td><?= $croqui->tipo_equipament_recomendado?></td>
                                        <td class="actions">
                                        <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $croqui->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $croqui->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $croqui->id)]) ?>
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
