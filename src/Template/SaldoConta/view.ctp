<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SaldoContum $saldoContum
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Saldo Contum'), ['action' => 'edit', $saldoContum->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Saldo Contum'), ['action' => 'delete', $saldoContum->id], ['confirm' => __('Are you sure you want to delete # {0}?', $saldoContum->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Saldo Conta'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Saldo Contum'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="saldoConta view large-9 medium-8 columns content">
    <h3><?= h($saldoContum->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Cliente') ?></th>
            <td><?= $saldoContum->has('cliente') ? $this->Html->link($saldoContum->cliente->nome_fantasia, ['controller' => 'Clientes', 'action' => 'view', $saldoContum->cliente->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($saldoContum->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Saldo') ?></th>
            <td><?= $this->Number->format($saldoContum->saldo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($saldoContum->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($saldoContum->modified) ?></td>
        </tr>
    </table>
</div>
