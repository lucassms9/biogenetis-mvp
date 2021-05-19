<?php echo $this->element('admin/home/index'); ?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                        <?= $this->Form->create(null, ['id' => 'formFiles', 'enctype' => 'multipart/form-data', 'class' => 'dropzone']) ?>

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
                        <h3>Preencha os campos abaixo:</h3>
                        <hr>
                        <div class="row">
                            <div style="margin-bottom:10px;" class="col-md-3">
                                <label>Assintomático ou Sintomático:</label>
                                <select id="config-assinto" name="config_assintomaticos" class="form-control">
                                    <option value="ambos">Ambos</option>
                                    <option value="assintomatico">Assintomático</option>
                                    <option value="nao_assintomatico">Sintomático</option>
                                </select>
                            </div>
                        </div>
                        <?= $this->Form->create(null, ['id' => 'sendData', 'url' => ['action' => 'sendData']]) ?>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="head-table" scope="col">ID amostra</th>
                                        <th class="head-table" scope="col">Assintomático</th>
                                        <!-- <th class="head-table" scope="col">UF *</th>
                                        <th class="head-table" scope="col">Idade *</th>
                                        <th class="head-table" scope="col">Sexo *</th> -->
                                    </tr>
                                </thead>
                                <tbody id="input-files">
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-4">
                            <input class="form-control" type="hidden" name="totalFiles" id="total-files" value="0" />
                            <input class="form-control" type="hidden" name="filesRemoved" id="files-removed" value="0" />
                            <?= $this->Form->button(__('Enviar'), ['type' => 'button', 'id' => 'buttonSend', 'onClick' => 'submitForm()', 'class' => 'btn btn-primary waves-effect waves-light']) ?>
                        </div>

                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- dropzone js -->
<script src="<?= $this->Url->build('/', true); ?>assets/libs/dropzone/min/dropzone.min.js"></script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/amostras/import.js"></script>
