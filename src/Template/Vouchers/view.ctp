<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher $voucher
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Voucher'), ['action' => 'edit', $voucher->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Voucher'), ['action' => 'delete', $voucher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $voucher->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Vouchers'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Voucher'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Pedidos'), ['controller' => 'Pedidos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido'), ['controller' => 'Pedidos', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="vouchers view large-9 medium-8 columns content">
    <h3><?= h($voucher->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Codigo') ?></th>
            <td><?= h($voucher->codigo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cliente') ?></th>
            <td><?= $voucher->has('cliente') ? $this->Html->link($voucher->cliente->nome_fantasia, ['controller' => 'Clientes', 'action' => 'view', $voucher->cliente->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($voucher->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valor') ?></th>
            <td><?= $this->Number->format($voucher->valor) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($voucher->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Used') ?></th>
            <td><?= $voucher->used ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Extrato Saldo') ?></h4>
        <?php if (!empty($voucher->extrato_saldo)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Voucher Id') ?></th>
                <th scope="col"><?= __('Type') ?></th>
                <th scope="col"><?= __('Valor') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Created By') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($voucher->extrato_saldo as $extratoSaldo): ?>
            <tr>
                <td><?= h($extratoSaldo->id) ?></td>
                <td><?= h($extratoSaldo->voucher_id) ?></td>
                <td><?= h($extratoSaldo->type) ?></td>
                <td><?= h($extratoSaldo->valor) ?></td>
                <td><?= h($extratoSaldo->created) ?></td>
                <td><?= h($extratoSaldo->created_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'ExtratoSaldo', 'action' => 'view', $extratoSaldo->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'ExtratoSaldo', 'action' => 'edit', $extratoSaldo->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'ExtratoSaldo', 'action' => 'delete', $extratoSaldo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $extratoSaldo->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Pedidos') ?></h4>
        <?php if (!empty($voucher->pedidos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Anamnese Id') ?></th>
                <th scope="col"><?= __('Amostra Id') ?></th>
                <th scope="col"><?= __('Cliente Id') ?></th>
                <th scope="col"><?= __('Forma Pagamento') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Created By') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Entrada Exame Id') ?></th>
                <th scope="col"><?= __('Voucher Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($voucher->pedidos as $pedidos): ?>
            <tr>
                <td><?= h($pedidos->id) ?></td>
                <td><?= h($pedidos->anamnese_id) ?></td>
                <td><?= h($pedidos->amostra_id) ?></td>
                <td><?= h($pedidos->cliente_id) ?></td>
                <td><?= h($pedidos->forma_pagamento) ?></td>
                <td><?= h($pedidos->created) ?></td>
                <td><?= h($pedidos->modified) ?></td>
                <td><?= h($pedidos->created_by) ?></td>
                <td><?= h($pedidos->status) ?></td>
                <td><?= h($pedidos->entrada_exame_id) ?></td>
                <td><?= h($pedidos->voucher_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Pedidos', 'action' => 'view', $pedidos->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Pedidos', 'action' => 'edit', $pedidos->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Pedidos', 'action' => 'delete', $pedidos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pedidos->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
