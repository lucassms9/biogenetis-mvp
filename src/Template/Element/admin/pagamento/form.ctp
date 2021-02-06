<?= $this->Form->create($pagamento, ['url' => ['action' => 'pagamento']]) ?>
<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('entrada_exame_id', ['class' => 'form-control', 'label' => 'Nome do Exame', 'readonly' => isset($pedido->valor_exame) ? 'true' : 'false', 'options' => $exames_tipos, 'empty' => 'Escolha', 'default' => $pedido->entrada_exame_id]); ?>
    </div>

    <div class="col-md-3">
        <?php echo $this->Form->control('valor_exame', ['class' => 'form-control money', 'type' => 'text', 'label' => 'Valor do Exame', 'value' => $pedido->valor_exame ? number_format($pedido->valor_exame, 2, ",", ".") : '', 'disabled' => $pedido->valor_exame ? true : false]); ?>
    </div>
</div>

<?php echo $this->Form->control('pedido_id', ['class' => 'form-control', 'type' => 'hidden', 'value' => $pedido->id]); ?>

<div class="row">
    <div class="col-md-3">
        <?php echo $this->Form->control('formas_pagamento', ['class' => 'form-control', 'label' => 'Forma de Pagamento', 'disabled' => !empty($pedido->forma_pagamento) ? 'true' : 'false', 'options' => $formas_pagamento, 'default' => @$pedido->forma_pagamento, 'empty' => 'Escolha']); ?>
    </div>
</div>

<?php if (empty($pedido->forma_pagamento)) : ?>
    <div style="margin-top: 10px" class="row">
        <div class="col-md-1">
            <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
        </div>
    </div>
<?php endif; ?>
<?= $this->Form->end() ?>
