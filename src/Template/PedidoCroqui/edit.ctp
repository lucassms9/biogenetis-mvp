<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PedidoCroqui $pedidoCroqui
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $pedidoCroqui->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $pedidoCroqui->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Pedido Croqui'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Pedidos'), ['controller' => 'Pedidos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pedido'), ['controller' => 'Pedidos', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pedido Croqui Valores'), ['controller' => 'PedidoCroquiValores', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pedido Croqui Valore'), ['controller' => 'PedidoCroquiValores', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pedidoCroqui form large-9 medium-8 columns content">
    <?= $this->Form->create($pedidoCroqui) ?>
    <fieldset>
        <legend><?= __('Edit Pedido Croqui') ?></legend>
        <?php
            echo $this->Form->control('croqui_tipo_id');
            echo $this->Form->control('pedido_id', ['options' => $pedidos, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
