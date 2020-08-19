<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($croqui) ?>
                        <?php echo $this->element('admin/croqui/form', [
                            'disabled' => true,
                        ]); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <h1>Pedidos</h1>

                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 20px;">Selecione</th>
                                                <th>Codigo do Pedido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pedidos_triagem as $key => $pedido):?>
                                                <tr>
                                                    <th scope="row">
                                                       <input type="checkbox" name="pedidos[]" value="<?=$pedido->id?>" />
                                                    </th>
                                                    <td><?= $pedido->codigo_pedido; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div style="margin-top: 10px" class="row">
                        <div class="col-md-1">
                            <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                        </div>
                    </div>

                    </div>



                </div>



                <?= $this->Form->end() ?>


            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/croqui/gerador.js"></script>
