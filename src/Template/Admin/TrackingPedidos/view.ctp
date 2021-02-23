<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TrackingPedido $trackingPedido
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Tracking Pedido'), ['action' => 'edit', $trackingPedido->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Tracking Pedido'), ['action' => 'delete', $trackingPedido->id], ['confirm' => __('Are you sure you want to delete # {0}?', $trackingPedido->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Tracking Pedidos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Tracking Pedido'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="trackingPedidos view large-9 medium-8 columns content">
    <h3><?= h($trackingPedido->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $trackingPedido->has('user') ? $this->Html->link($trackingPedido->user->id, ['controller' => 'Users', 'action' => 'view', $trackingPedido->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Codigo Pedido') ?></th>
            <td><?= h($trackingPedido->codigo_pedido) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status Anterior') ?></th>
            <td><?= h($trackingPedido->status_anterior) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status Atual') ?></th>
            <td><?= h($trackingPedido->status_atual) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amostra Url') ?></th>
            <td><?= h($trackingPedido->amostra_url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($trackingPedido->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($trackingPedido->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($trackingPedido->modified) ?></td>
        </tr>
    </table>
</div>
