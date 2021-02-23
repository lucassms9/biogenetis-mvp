<?php echo $this->element('admin/home/index'); ?>


<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create(null, ['type' => 'get']) ?>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="example-text-input" class="col-form-label">Nome Usuário</label>
                                <input class="form-control" name="user_name" value="<?= @$this->request->getQuery('user_name') ?>" type="text">
                            </div>

                            <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Código do Pedido</label>
                                <input class="form-control" type="text" name="pedido_code" value="<?= @$this->request->getQuery('pedido_code') ?>">
                            </div>

                            <div style="margin-top: 37px;margin-bottom:20px" class="col-md-4">
                                <?= $this->Form->button(__('Filtrar'), ['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>

                        </div>

                        <?= $this->Form->end() ?>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">
                                        <?= $this->Paginator->sort('user_id','Nome Usuário') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('codigo_pedido','Código do Pedido') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('status_anterior') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('status_atual') ?></th>

                                        <th scope="col"><?= $this->Paginator->sort('created','Criado em') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('amostra_url','Baixar Amostra') ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($trackingPedidos as $trackingPedido) : ?>
                                        <tr>

                                            <td><?= $trackingPedido->user->nome_completo ?></td>
                                            <td><?= h($trackingPedido->codigo_pedido) ?></td>
                                            <td><?= h($trackingPedido->status_anterior) ?></td>
                                            <td><?= h($trackingPedido->status_atual) ?></td>
                                            <td><?= h($trackingPedido->created) ?></td>
                                            <td>
                                            <?php if(!empty($trackingPedido->amostra_url)):?>
                                                <?= $this->Html->link(__('Download'), ['action' => 'download/'.$trackingPedido->id,],['target' => '_blank', 'escape' => false]) ?>
                                            <?php endif;?>
                                           </td>

                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
</div>
