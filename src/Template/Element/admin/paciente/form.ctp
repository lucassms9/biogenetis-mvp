<?php if ($useForm):?>
<?= $this->Form->create($paciente,['id' => 'formnovopaciente']) ?>
<?php endif;?>
<div class="row mt20">
    <div class="col-md-3">
        <?php echo $this->Form->control('cpf',['id' => 'cpf','data-value' => 'new', 'class'=> 'form-control cpf','label' => 'CPF', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('nome',['class' => 'form-control', 'title' => 'teste','disabled' => $disabled]); ?>
    </div>
    
    <div class="col-md-3">
        <?php echo $this->Form->control('rg',['class'=> 'form-control','label' => 'RG', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('sexo',['class'=> 'form-control','options' => $sexos, 'empty' => 'Escolha', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-3">
        <?php echo $this->Form->control('email',['class'=> 'form-control','label' => 'E-mail', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('celular',['class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('telefone',['class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('data_nascimento',['class'=> 'form-control','type' => 'text', 'disabled' => $disabled]); ?>
    </div>
</div>

<div class="row mt20">
    <div class="col-md-2">
        <?php echo $this->Form->control('cep',['class'=> 'form-control','label' => 'CEP', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('endereco',['class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('bairro',['class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
        <?php echo $this->Form->control('cidade',['class'=> 'form-control', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-2">
    <?php echo $this->Form->control('uf',['class'=> 'form-control','label' => 'UF', 'disabled' => $disabled]); ?>
    </div>
</div>
<div class="row mt20">
    <div class="col-md-4">
        <?php echo $this->Form->control('nome_da_mae',['class'=> 'form-control', 'disabled' => $disabled]);?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('nacionalidade',['class'=> 'form-control', 'disabled' => $disabled]);?>
    </div>
    <div class="col-md-4">
        <?php echo $this->Form->control('pais_residencia',['class'=> 'form-control', 'disabled' => $disabled]);?>
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
