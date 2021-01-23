<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExtratoSaldo $extratoSaldo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Extrato Saldo'), ['action' => 'edit', $extratoSaldo->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Extrato Saldo'), ['action' => 'delete', $extratoSaldo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $extratoSaldo->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Extrato Saldo'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Extrato Saldo'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Vouchers'), ['controller' => 'Vouchers', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Voucher'), ['controller' => 'Vouchers', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="extratoSaldo view large-9 medium-8 columns content">
    <h3><?= h($extratoSaldo->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Voucher') ?></th>
            <td><?= $extratoSaldo->has('voucher') ? $this->Html->link($extratoSaldo->voucher->id, ['controller' => 'Vouchers', 'action' => 'view', $extratoSaldo->voucher->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Type') ?></th>
            <td><?= h($extratoSaldo->type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($extratoSaldo->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valor') ?></th>
            <td><?= $this->Number->format($extratoSaldo->valor) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($extratoSaldo->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= h($extratoSaldo->created_by) ?></td>
        </tr>
    </table>
</div>
