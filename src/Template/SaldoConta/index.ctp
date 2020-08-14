<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SaldoContum[]|\Cake\Collection\CollectionInterface $saldoConta
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Saldo Contum'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="saldoConta index large-9 medium-8 columns content">
    <h3><?= __('Saldo Conta') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cliente_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('saldo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($saldoConta as $saldoContum): ?>
            <tr>
                <td><?= $this->Number->format($saldoContum->id) ?></td>
                <td><?= $saldoContum->has('cliente') ? $this->Html->link($saldoContum->cliente->nome_fantasia, ['controller' => 'Clientes', 'action' => 'view', $saldoContum->cliente->id]) : '' ?></td>
                <td><?= $this->Number->format($saldoContum->saldo) ?></td>
                <td><?= h($saldoContum->created) ?></td>
                <td><?= h($saldoContum->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $saldoContum->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $saldoContum->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $saldoContum->id], ['confirm' => __('Are you sure you want to delete # {0}?', $saldoContum->id)]) ?>
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
