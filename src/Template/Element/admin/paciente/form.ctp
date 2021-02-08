<?php if ($useForm):?>
<?= $this->Form->create($paciente,['id' => 'formnovopaciente']) ?>
<?php endif;?>
<div class="row mt20">
    <div class="col-md-3">
        <?php echo $this->Form->control('cpf',['id' => 'cpf','data-value' => 'new', 'class'=> 'form-control cpf','label' => 'CPF', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('nome',['id' => 'nome_paciente', 'class' => 'form-control', 'title' => 'nome','disabled' => $disabled]); ?>
    </div>
    
    <div class="col-md-3">
        <?php echo $this->Form->control('rg',['id' => 'rg_paciente','class'=> 'form-control','label' => 'RG', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('sexo',['id' => 'sexo_paciente','class'=> 'form-control','options' => $sexos, 'empty' => 'Escolha', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-3">
        <?php echo $this->Form->control('email',['id' => 'email_paciente', 'class'=> 'form-control','label' => 'E-mail', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('celular',[ 'id' =>'celular_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('telefone',[ 'id' => 'telefone_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('data_nascimento',['id' => 'data_nascimento' ,'class'=> 'form-control','type' => 'text', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-2"> 
        <?php echo $this->Form->control('cep',['id' => 'cep_paciente' ,'class'=> 'form-control','label' => 'CEP', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('endereco',['id' => 'endereco_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('bairro',['id' => 'bairro_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('cidade',['id' => 'cidade_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
    <?php echo $this->Form->control('uf',['id' => 'uf_paciente' ,'class'=> 'form-control','label' => 'UF', 'disabled' => $disabled]); ?>
    </div>
</div>
<div class="row mt20">
    <div class="col-md-4">
        <?php echo $this->Form->control('nome_da_mae',['id' => 'nome_da_mae_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]);?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('nacionalidade',['id' => 'nacionalidade_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]);?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('pais_residencia',['id' => 'pais_residencia_paciente' ,'class'=> 'form-control', 'disabled' => $disabled]);?>
    </div>
</div>
<div class="row mt20">
    <div class="col-md-3">
        <?php
        echo $this->Form->control('foto_perfil_url',['class'=> 'form-control','type' => 'file', 'label' => 'Foto do Paciente', 'disabled' => $disabled]);
        ?>
    </div>
    <div class="col-md-3">
        <?php
        echo $this->Form->control('foto_doc_url',['class'=> 'form-control','type' => 'file', 'label' => 'Foto do Documento', 'disabled' => $disabled]);
        ?>
    </div>
</div>

<?php if ($useForm):?>
    <?= $this->Form->end() ?>
<?php endif;?>
