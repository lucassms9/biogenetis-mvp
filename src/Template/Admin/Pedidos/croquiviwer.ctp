<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
    <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                    <?= $this->Form->create(null,['id' => 'formFiles','enctype' => 'multipart/form-data','class' => 'dropzone']) ?>

                     <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                        </div>

                          <div class="dz-message needsclick">
                            <div class="mb-3">
                                <i class="display-4 text-muted mdi mdi-cloud-upload-outline"></i>
                            </div>

                            <h4>Arraste ou escolha os arquivos</h4>

                        </div>

                        <?= $this->Form->end() ?>
                        <hr>
                        <?= $this->Form->create(null, ['id' => 'sendData', 'url' => ['controller' => 'Amostras', 'action' => 'sendData'] ]) ?>
                        <div style="display: none;" class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="head-table" scope="col">ID amostra</th>
                                        <th class="head-table" scope="col">UF *</th>
                                        <th class="head-table" scope="col">Idade *</th>
                                        <th class="head-table" scope="col">Sexo *</th>
                                    </tr>
                                </thead>
                                <tbody id="input-files">
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                                <input class="form-control" type="hidden" name="totalFiles" id="total-files" value="0" />
                                <input class="form-control" type="hidden" name="filesRemoved" id="files-removed" value="0" />
                                <?= $this->Form->button(__('Enviar'),['type' => 'button', 'id' => 'buttonSend', 'onClick' => 'submitForm()', 'class' => 'btn btn-primary waves-effect waves-light']) ?>
                            </div>

                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>

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
<!-- dropzone js -->
<script src="<?= $this->Url->build('/', true);?>assets/libs/dropzone/min/dropzone.min.js"></script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/amostras/import_pedidos.js"></script>
