<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra[]|\Cake\Collection\CollectionInterface $amostras
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Amostra'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="amostras index large-9 medium-8 columns content">
    <h3><?= __('Amostras') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('code_amostra') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cep') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($amostras as $amostra): ?>
            <tr>
                <td><?= $this->Number->format($amostra->id) ?></td>
                <td><?= h($amostra->code_amostra) ?></td>
                <td><?= h($amostra->cep) ?></td>
                <td><?= $this->Number->format($amostra->idade) ?></td>
                <td><?= h($amostra->sexo) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $amostra->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $amostra->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $amostra->id], ['confirm' => __('Are you sure you want to delete # {0}?', $amostra->id)]) ?>
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
