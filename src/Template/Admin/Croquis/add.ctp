<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($croqui) ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    echo $this->Form->control('nome',['class' => 'form-control']);
                                    echo $this->Form->control('descricao',['class' => 'form-control']);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('qtde_posi_placa',['class' => 'form-control','label'=>'Quantidade de Posições por Placa']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('tipo_exame_recomendado',['class' => 'form-control','label'=>'Tipo de Exame recomandado', 'options' => $exame_tipos, 'empty' => 'Escolha']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('tipo_equipament_recomendado',['class' => 'form-control','label'=>'Tipo de Equipamento Recomendado','options' => $equipamento_tipos, 'empty' => 'Escolha']);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('linhas',['class' => 'form-control','label'=>'Quantidade de Linhas']);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('colunas',['class' => 'form-control','label'=>'Quantidade de Colunas']);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('foto_url',['class' => 'form-control', 'type' => 'file', 'label' => 'Foto Croquis']);?>
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
