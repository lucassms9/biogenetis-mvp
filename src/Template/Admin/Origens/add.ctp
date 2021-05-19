<style>
    .custom-style-check{
        margin-right: 10px;
    }
</style>

<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($origen) ?>

                         <div class="row">
                            <div class="col-md-12">
                                <?php echo $this->Form->control('nome_origem',['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php echo $this->Form->control('url_request',['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php echo $this->Form->control('equip_tipo',['class' => 'form-control', 'options' => $equip_tipos, 'empty' => 'Escolha']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php  echo $this->Form->control('amostra_tipo',['class' => 'form-control', 'options' => $amostra_tipos, 'empty' => 'Escolha']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php  echo $this->Form->control('IAModelType',['class' => 'form-control', 'options' => $iAModelTypes, 'empty' => 'Escolha']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php echo $this->Form->control('IAModelName',['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px;" class="row">
                            <div class="col-md-12">
                                <?php echo $this->Form->control('DataScience',['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->control('assintomatico',['class' => 'custom-style-check', 'label' => 'Assintomático',]); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('nao_assintomatico',['class' => 'custom-style-check', 'label' =>'Sintomático']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->control('ativo',['class' => 'custom-style-check']); ?>
                            </div>
                        </div>


                        <div style="margin-top: 10px" class="row">
                            <div style="display: flex;">
                                <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                                         <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light','style' => 'margin-left: 5px;']) ?>
                            </div>

                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

