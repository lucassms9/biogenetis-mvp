<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EntradaExame[]|\Cake\Collection\CollectionInterface $entradaExames
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Entrada Exame'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="entradaExames index large-9 medium-8 columns content">
    <h3><?= __('Entrada Exames') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('descricao') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valor_particular') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valor_laboratorio_conveniado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('valor_laboratorio_nao_conveniado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tipo_exame') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entradaExames as $entradaExame): ?>
            <tr>
                <td><?= $this->Number->format($entradaExame->id) ?></td>
                <td><?= h($entradaExame->nome) ?></td>
                <td><?= h($entradaExame->descricao) ?></td>
                <td><?= $this->Number->format($entradaExame->valor_particular) ?></td>
                <td><?= $this->Number->format($entradaExame->valor_laboratorio_conveniado) ?></td>
                <td><?= $this->Number->format($entradaExame->valor_laboratorio_nao_conveniado) ?></td>
                <td><?= h($entradaExame->tipo_exame) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $entradaExame->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $entradaExame->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $entradaExame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $entradaExame->id)]) ?>
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
