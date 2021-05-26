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
                                    <th scope="col"><?= $this->Paginator->sort('cns_profissional_rnds') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('cnes_rnds') ?></th>
                                    <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($configuracoes as $config): ?>
                                        <tr>
                                        <td><?= $this->Number->format($config->id) ?></td>
                                        <td><?= h($config->cns_profissional_rnds) ?></td>

                                        <td><?= h($config->cnes_rnds) ?></td>

                                        <td class="actions">
                                        <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $config->id], ['escape' => false]) ?>
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
