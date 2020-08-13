<?= $this->Form->create(null,['url' => ['action' => 'pagamento']]) ?>
<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('exame_name',['class'=> 'form-control','label' => 'Nome do Exame']); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('exame_name',['class'=> 'form-control','label' => 'Valor do Exame']); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('voucher_cod',['class'=> 'form-control','label' => 'Inserir Voucher']); ?>
    </div>
</div>


<div style="margin-top: 10px" class="row">
    <div class="col-md-1">
        <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
    </div>
</div>
<?= $this->Form->end() ?>
