
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra $amostra
 */
?>
<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Amostras</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Amostras</a></li>
            <li class="breadcrumb-item active">Importar</li>
            </ol>
        </div>
        <div class="col-md-4">

        </div>
    </div>

</div>
</div>
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

                        <?= $this->Form->create(null, ['id' => 'sendData', 'url' => ['action' => 'sendData'] ]) ?>
                        
                         <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID amostra</th>
                                        <th>UF</th>
                                        <th>Idade</th>
                                        <th>Sexo</th>
                                    </tr>
                                </thead>
                                <tbody id="input-files">
                                </tbody>
                            </table>
                        </div>
                         <div class="text-center mt-4">
                                <input class="form-control" type="hidden" name="totalFiles" id="total-files" value="0" />
                                <?= $this->Form->button(__('Enviar'),['type' => 'submit', 'id' => 'buttonSend', 'class' => 'btn btn-primary waves-effect waves-light']) ?>
                            </div>
                        
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- dropzone js -->
<script src="<?= $this->Url->build('/', true);?>assets/libs/dropzone/min/dropzone.min.js"></script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/amostras/import.js"></script>
