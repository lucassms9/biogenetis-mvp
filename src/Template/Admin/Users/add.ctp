<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($user,[ 'type' => 'file' ]) ?>

                        <?php
                            echo $this->Form->control('nome_completo',['class' => 'form-control']);
                            echo $this->Form->control('cpf',['class' => 'form-control','label' => 'CPF']);
                            echo $this->Form->control('telefone',['class' => 'form-control']);
                            echo $this->Form->control('email',['class' => 'form-control']);
                            echo $this->Form->control('senha',['class' => 'form-control', 'type' => 'password','value'=>'']);
                            echo $this->Form->control('user_type_id',['label' =>'Perfil', 'class' => 'form-control']);
                            echo $this->Form->control('cliente_id',['class' => 'form-control']);
                        ?>

                        <div style="display: none;" id="showInputs" class="row">
                            <div class="col-md-6">
                                <?php echo $this->Form->control('numero_crbio',['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-6">
                                <?php echo $this->Form->control('foto_assinatura_digital',['class' => 'form-control','type' => 'file']); ?>
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

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/users/add.js"></script>
