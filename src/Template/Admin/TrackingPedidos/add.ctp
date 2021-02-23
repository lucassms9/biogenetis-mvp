<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TrackingPedido $trackingPedido
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Tracking Pedidos'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="trackingPedidos form large-9 medium-8 columns content">
    <?= $this->Form->create($trackingPedido) ?>
    <fieldset>
        <legend><?= __('Add Tracking Pedido') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
            echo $this->Form->control('codigo_pedido');
            echo $this->Form->control('status_anterior');
            echo $this->Form->control('status_atual');
            echo $this->Form->control('amostra_url');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
