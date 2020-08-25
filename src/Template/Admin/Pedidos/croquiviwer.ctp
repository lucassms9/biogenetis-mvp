<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?php echo $this->element('admin/croqui/view', [
                            'disabled' => true,
                            'pedido' => $croqui->pedido
                        ]);?>

                       <input type="hidden" id="croqui-pedido-id" value="<?= $croqui->id; ?>" />

                       <div class="row">
                        <div style="margin-top: 15px;" class="col-md-12">
                                <h1>Pedidos</h1>

                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Codigo do Pedido</th>
                                                <th>Nome do Paciente</th>
                                                <th>CPF do Paciente</th>
                                                <th>Criação do Pedido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($croquis_pedidos as $key => $croquis_pedido):?>
                                                <tr>
                                                    <td><?= $croquis_pedido->pedido->codigo_pedido; ?></td>
                                                    <td><?= $croquis_pedido->pedido->anamnese->paciente->nome; ?></td>
                                                    <td><?= $croquis_pedido->pedido->anamnese->paciente->cpf; ?></td>
                                                    <td><?= $croquis_pedido->pedido->created; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/croqui/croquiviwer.js"></script>
