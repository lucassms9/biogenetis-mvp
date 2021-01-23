<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Pedido $pedido
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Pedido'), ['action' => 'edit', $pedido->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Pedido'), ['action' => 'delete', $pedido->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pedido->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Pedidos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Amostras'), ['controller' => 'Amostras', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Amostra'), ['controller' => 'Amostras', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pedidos view large-9 medium-8 columns content">
    <h3><?= h($pedido->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Amostra') ?></th>
            <td><?= $pedido->has('amostra') ? $this->Html->link($pedido->amostra->id, ['controller' => 'Amostras', 'action' => 'view', $pedido->amostra->code_amostra]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cliente') ?></th>
            <td><?= $pedido->has('cliente') ? $this->Html->link($pedido->cliente->nome_fantasia, ['controller' => 'Clientes', 'action' => 'view', $pedido->cliente->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Forma Pagamento') ?></th>
            <td><?= h($pedido->forma_pagamento) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($pedido->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Anamnese Id') ?></th>
            <td><?= $this->Number->format($pedido->anamnese_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= $this->Number->format($pedido->created_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($pedido->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($pedido->modified) ?></td>
        </tr>
    </table>
</div>
