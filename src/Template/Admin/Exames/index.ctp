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
                <th scope="col"><?= $this->Paginator->sort('Resultado') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exames as $exame): ?>
            <tr>
                <td><?= $this->Number->format($exame->id) ?></td>
                <td><?= h($exame->paciente->nome) ?></td>
                <td><?= h($exame->amostra->code_amostra) ?></td>
                <td><?= h($exame->amostra->cep) ?></td>
                <td><?= $this->Number->format($exame->amostra->idade) ?></td>
                <td><?= h($exame->resultado) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $exame->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $exame->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $exame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $exame->id)]) ?>
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
