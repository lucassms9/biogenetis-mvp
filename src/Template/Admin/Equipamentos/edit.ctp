<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($equipamento) ?>

                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    echo $this->Form->control('nome',['class' => 'form-control']);
                                    echo $this->Form->control('descricao',['class' => 'form-control']);
                                    echo $this->Form->control('tipo_exame',['class' => 'form-control','options' => $exame_tipos, 'empty' => 'Escolha']);
                                    echo $this->Form->control('croqui_id', ['class' => 'form-control','options' => $croquis, 'empty' => 'Escolha']);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <?php
                                  echo $this->Form->control('foto_url',['class' => 'form-control','type' => 'file', 'label' => 'Foto do Equipamento']);
                                  ?>
                            </div>
                        </div>
                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-2">

                                <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                            </div>
                            <div class="col-md-4">
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
