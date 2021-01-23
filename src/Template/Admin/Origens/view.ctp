<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Origen $origen
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Origen'), ['action' => 'edit', $origen->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Origen'), ['action' => 'delete', $origen->id], ['confirm' => __('Are you sure you want to delete # {0}?', $origen->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Origens'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Origen'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="origens view large-9 medium-8 columns content">
    <h3><?= h($origen->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome Origem') ?></th>
            <td><?= h($origen->nome_origem) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Url Request') ?></th>
            <td><?= h($origen->url_request) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Equip Tipo') ?></th>
            <td><?= h($origen->equip_tipo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Amostra Tipo') ?></th>
            <td><?= h($origen->amostra_tipo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('IAModelType') ?></th>
            <td><?= h($origen->IAModelType) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('IAModelName') ?></th>
            <td><?= h($origen->IAModelName) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('DataScience') ?></th>
            <td><?= h($origen->DataScience) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($origen->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ativo') ?></th>
            <td><?= $origen->ativo ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
