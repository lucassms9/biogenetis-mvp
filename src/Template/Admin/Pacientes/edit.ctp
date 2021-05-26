<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($paciente) ?>
                        <div class="row">
                             <div class="col-md-3">
                                <?php echo $this->Form->control('cpf',['class'=> 'form-control','label' => 'CPF', 'disabled' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('nome',['class' => 'form-control']); ?>
                            </div>

                            <div class="col-md-3">
                                <?php echo $this->Form->control('rg',['class'=> 'form-control','label' => 'RG', 'disabled' => true]); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('sexo',['class'=> 'form-control','options' => $sexos, 'disabled' => true]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <?php echo $this->Form->control('email',['class'=> 'form-control','label' => 'E-mail']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('numero_cartao_nacional_saude',['class'=> 'form-control','label' => 'Número Cartão Sus']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('celular',['class'=> 'form-control']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('telefone',['class'=> 'form-control']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('data_nascimento',['class'=> 'form-control','type' => 'text', 'disabled' => true]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->control('cep',['class'=> 'form-control','label' => 'CEP']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('endereco',['class'=> 'form-control']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('bairro',['class'=> 'form-control']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('cidade',['class'=> 'form-control']); ?>
                            </div>
                            <div class="col-md-2">
                            <?php echo $this->Form->control('uf',['class'=> 'form-control','label' => 'UF']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('nome_da_mae',['class'=> 'form-control', 'disabled' => true]);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('nacionalidade',['class'=> 'form-control', 'disabled' => true]);?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('pais_residencia',['class'=> 'form-control']);?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                echo $this->Form->control('foto_perfil_url',['class'=> 'form-control','type' => 'file', 'Foto do Paciente']);
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                echo $this->Form->control('foto_doc_url',['class'=> 'form-control','type' => 'file', 'Foto do Documento']);
                                ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px" class="row">
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
