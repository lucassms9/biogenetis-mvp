<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Voucher[]|\Cake\Collection\CollectionInterface $vouchers
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Voucher'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Extrato Saldo'), ['controller' => 'ExtratoSaldo', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Pedidos'), ['controller' => 'Pedidos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Pedido'), ['controller' => 'Pedidos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="vouchers index large-9 medium-8 columns content">
    <h3><?= __('Vouchers') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('codigo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valor') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cliente_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('used') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vouchers as $voucher): ?>
            <tr>
                <td><?= $this->Number->format($voucher->id) ?></td>
                <td><?= h($voucher->codigo) ?></td>
                <td><?= $this->Number->format($voucher->valor) ?></td>
                <td><?= $voucher->has('cliente') ? $this->Html->link($voucher->cliente->nome_fantasia, ['controller' => 'Clientes', 'action' => 'view', $voucher->cliente->id]) : '' ?></td>
                <td><?= h($voucher->created) ?></td>
                <td><?= h($voucher->used) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $voucher->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $voucher->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $voucher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $voucher->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
