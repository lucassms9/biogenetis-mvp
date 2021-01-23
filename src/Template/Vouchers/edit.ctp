<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $voucher->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $voucher->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Vouchers'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pedidos'), ['controller' => 'Pedidos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pedido'), ['controller' => 'Pedidos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vouchers form large-9 medium-8 columns content">
    <?= $this->Form->create($voucher) ?>
    <fieldset>
        <legend><?= __('Edit Voucher') ?></legend>
        <?php
            echo $this->Form->control('codigo');
            echo $this->Form->control('valor');
            echo $this->Form->control('cliente_id', ['options' => $clientes, 'empty' => true]);
            echo $this->Form->control('used');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
