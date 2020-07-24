<style>
    .no-border{
        border-top: none !important;
    }
</style>
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
                                        <th scope="col"><?= $this->Paginator->sort('nome_origem') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('url_request') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('ativo') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('equip_tipo') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('amostra_tipo') ?></th>
                                        <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    <?php foreach ($origens as $origen): ?>
                                        <tr>
                                            <td><?= $this->Number->format($origen->id) ?></td>
                                            <td><?= h($origen->nome_origem) ?></td>
                                            <td><?= h($origen->url_request) ?></td>
                                            <td><?= ($origen->ativo) ? 'SIM' : 'NÃO' ?></td>
                                            <td><?= h($origen->equip_tipo) ?></td>
                                            <td><?= h($origen->amostra_tipo) ?></td>
                                            <td style="width: 100px;" class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-link-variant-plus"></i>'), ['action' => 'encadeamentos', $origen->id], ['escape' => false]) ?>
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $origen->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $origen->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $origen->id)]) ?>

                                            </td>


                                        </tr>

                                            <?php if(count($origen->encadeamentos) > 0):?>
                                                <?php foreach ($origen->encadeamentos as $encadeamento): ?>
                                                    <tr>
                                                        <td class="no-border"></td>
                                                        <td class="no-border"><?= h($encadeamento->origen->nome_origem) ?></td>
                                                        <td class="no-border"><?= h($encadeamento->origen->url_request) ?></td>
                                                        <td class="no-border"></td>
                                                        <td class="no-border"><?= h($encadeamento->origen->equip_tipo) ?></td>
                                                        <td class="no-border"><?= h($encadeamento->origen->amostra_tipo) ?></td>
                                                        <td class="no-border">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
