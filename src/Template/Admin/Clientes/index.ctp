<?php echo $this->element('admin/home/index'); ?>


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
                                        <th scope="col"><?= $this->Paginator->sort('nome_fantasia') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('razao_social') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('cnpj_cpf', 'CNPJ/CPF') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('cidade') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('uf') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('ativo') ?></th>
                                        <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientes as $cliente) : ?>
                                        <tr>
                                            <td><?= $this->Number->format($cliente->id) ?></td>
                                            <td><?= h($cliente->nome_fantasia) ?></td>
                                            <td><?= h($cliente->razao_social) ?></td>
                                            <td><?= h($cliente->cnpj_cpf) ?></td>
                                            <td><?= h($cliente->cidade) ?></td>
                                            <td><?= h($cliente->uf) ?></td>
                                            <td><?= h($cliente->ativo) ?></td>
                                            <td class="actions">
                                            <?php if($_SESSION['Auth']['User']['user_type_id'] === 1):?>
                                                <?= $this->Html->link(__('<i class="mdi mdi-cogs"></i>'), ['action' => 'saldos', $cliente->id], ['escape' => false]) ?>
                                                <?php endif;?>
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $cliente->id], ['escape' => false]) ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-4">
                            <div class="paginator">
                                <ul class="pagination pagination pagination-rounded justify-content-center mb-0">
                                    <?= $this->Paginator->prev('<i class="mdi mdi-chevron-left"></i>', ['escape' => false, '']) ?>

                                    <?= $this->Paginator->numbers() ?>
                                    <?= $this->Paginator->next('<i class="mdi mdi-chevron-right"></i>', ['escape' => false, '']) ?>
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
