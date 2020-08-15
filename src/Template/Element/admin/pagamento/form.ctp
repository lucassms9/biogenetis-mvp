<?= $this->Form->create($pagamento,['url' => ['action' => 'pagamento']]) ?>
<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('entrada_exame_id',['class'=> 'form-control','label' => 'Nome do Exame', 'disabled' => !empty($pedido->voucher_id) ? 'true' : 'false', 'options' => $exames_tipos, 'empty' => 'Escolha', 'default' => $pedido->entrada_exame_id]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('tipo_exame',['class'=> 'form-control','label' => 'Tipo Exame', 'disabled' => $disabled]); ?>
    </div>
    <div class="col-md-3">
        <?php echo $this->Form->control('valor_laboratorio_conveniado',['class'=> 'form-control','type' => 'text', 'label' => 'Valor do Exame', 'value' => $pedido->entrada_exame_id ? number_format($pagamento->valor_laboratorio_conveniado,2,",",".") : '','disabled' => $disabled]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('pedido_id',['class'=> 'form-control','type' => 'hidden', 'value' => $pedido->id]); ?>

        <?php echo $this->Form->control('voucher_cod',['class'=> 'form-control','label' => empty($pedido->voucher_id) ? 'Inserir Voucher' : 'Voucher', 'value' => !empty($pedido->voucher_id) ? number_format($pedido->voucher->valor,2,",",".") : '', 'disabled' => !empty($pedido->voucher_id) ? 'true' : 'false']); ?>
    </div>
</div>

<?php if(empty($pedido->voucher_id)):?>
<div style="margin-top: 10px" class="row">
    <div class="col-md-1">
        <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
    </div>
</div>
<?php endif; ?>
<?= $this->Form->end() ?>
