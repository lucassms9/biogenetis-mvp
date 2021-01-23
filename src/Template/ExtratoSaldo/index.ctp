<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExtratoSaldo[]|\Cake\Collection\CollectionInterface $extratoSaldo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Extrato Saldo'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Vouchers'), ['controller' => 'Vouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Voucher'), ['controller' => 'Vouchers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="extratoSaldo index large-9 medium-8 columns content">
    <h3><?= __('Extrato Saldo') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('voucher_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valor') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($extratoSaldo as $extratoSaldo): ?>
            <tr>
                <td><?= $this->Number->format($extratoSaldo->id) ?></td>
                <td><?= $extratoSaldo->has('voucher') ? $this->Html->link($extratoSaldo->voucher->id, ['controller' => 'Vouchers', 'action' => 'view', $extratoSaldo->voucher->id]) : '' ?></td>
                <td><?= h($extratoSaldo->type) ?></td>
                <td><?= $this->Number->format($extratoSaldo->valor) ?></td>
                <td><?= h($extratoSaldo->created) ?></td>
                <td><?= h($extratoSaldo->created_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $extratoSaldo->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $extratoSaldo->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $extratoSaldo->id], ['confirm' => __('Are you sure you want to delete # {0}?', $extratoSaldo->id)]) ?>
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
