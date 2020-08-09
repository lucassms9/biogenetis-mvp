<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipamento[]|\Cake\Collection\CollectionInterface $equipamentos
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Equipamento'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Croquis'), ['controller' => 'Croquis', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Croqui'), ['controller' => 'Croquis', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="equipamentos index large-9 medium-8 columns content">
    <h3><?= __('Equipamentos') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('foto_url') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tipo_exame') ?></th>
                <th scope="col"><?= $this->Paginator->sort('croqui_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipamentos as $equipamento): ?>
            <tr>
                <td><?= $this->Number->format($equipamento->id) ?></td>
                <td><?= h($equipamento->nome) ?></td>
                <td><?= h($equipamento->foto_url) ?></td>
                <td><?= h($equipamento->tipo_exame) ?></td>
                <td><?= $equipamento->has('croqui') ? $this->Html->link($equipamento->croqui->id, ['controller' => 'Croquis', 'action' => 'view', $equipamento->croqui->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $equipamento->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $equipamento->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $equipamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $equipamento->id)]) ?>
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
