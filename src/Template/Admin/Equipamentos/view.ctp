<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipamento $equipamento
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Equipamento'), ['action' => 'edit', $equipamento->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Equipamento'), ['action' => 'delete', $equipamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $equipamento->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Equipamentos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Equipamento'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Croquis'), ['controller' => 'Croquis', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Croqui'), ['controller' => 'Croquis', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="equipamentos view large-9 medium-8 columns content">
    <h3><?= h($equipamento->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome') ?></th>
            <td><?= h($equipamento->nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Foto Url') ?></th>
            <td><?= h($equipamento->foto_url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Exame') ?></th>
            <td><?= h($equipamento->tipo_exame) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Croqui') ?></th>
            <td><?= $equipamento->has('croqui') ? $this->Html->link($equipamento->croqui->id, ['controller' => 'Croquis', 'action' => 'view', $equipamento->croqui->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($equipamento->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Descricao') ?></h4>
        <?= $this->Text->autoParagraph(h($equipamento->descricao)); ?>
    </div>
</div>
