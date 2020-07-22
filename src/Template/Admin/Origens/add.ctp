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

                        <?php
                            echo $this->Form->control('nome_origem',['class' => 'form-control']);
                            echo $this->Form->control('url_request',['class' => 'form-control']);
                            echo $this->Form->control('equip_tipo',['class' => 'form-control', 'options' => $equip_tipos, 'empty' => 'Escolha']);
                            echo $this->Form->control('amostra_tipo',['class' => 'form-control', 'options' => $amostra_tipos, 'empty' => 'Escolha']);
                            echo $this->Form->control('IAModelType',['class' => 'form-control', 'options' => $iAModelTypes, 'empty' => 'Escolha']);
                            echo $this->Form->control('IAModelName',['class' => 'form-control']);
                            echo $this->Form->control('DataScience',['class' => 'form-control']);

                          
                        ?> 
                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->control('ativo',['class' => 'custom-style-check']); ?>
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

