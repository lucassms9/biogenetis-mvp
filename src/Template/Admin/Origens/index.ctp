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
                                        <th scope="col"><?= $this->Paginator->sort('IAModelType') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('IAModelName') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('DataScience') ?></th>
                                        <th scope="col" class="actions"><?= __('Actions') ?></th>
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
                                            <td><?= h($origen->IAModelType) ?></td>
                                            <td><?= h($origen->IAModelName) ?></td>
                                            <td><?= h($origen->DataScience) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $origen->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $origen->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $origen->id)]) ?>

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
