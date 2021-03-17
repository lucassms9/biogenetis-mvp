<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($tecnicoPerito) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('nome',['class' => 'form-control']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('cpf',['class' => 'form-control','label' => 'CPF']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('email',['class' => 'form-control','label' => 'E-mail']);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('celular',['class' => 'form-control']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('numero_crbio',['class' => 'form-control','label' => 'Número de Conselho']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('certificado_digital',['class' => 'form-control']);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('foto_assinatura_digital',['class' => 'form-control', 'type' => 'file', 'label' => 'Foto Assinatura Digital']);?>
                            </div>
                        </div>

                        <div style="margin-top: 20px" class="row">
                            <div class="col-md-1">

                                <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                            </div>
                            <div class="col-md-1">
                                <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
