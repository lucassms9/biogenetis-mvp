<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TecnicoPerito $tecnicoPerito
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Tecnico Perito'), ['action' => 'edit', $tecnicoPerito->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Tecnico Perito'), ['action' => 'delete', $tecnicoPerito->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tecnicoPerito->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Tecnico Peritos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Tecnico Perito'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="tecnicoPeritos view large-9 medium-8 columns content">
    <h3><?= h($tecnicoPerito->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome') ?></th>
            <td><?= h($tecnicoPerito->nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cpf') ?></th>
            <td><?= h($tecnicoPerito->cpf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($tecnicoPerito->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Celular') ?></th>
            <td><?= h($tecnicoPerito->celular) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Numero Crbio') ?></th>
            <td><?= h($tecnicoPerito->numero_crbio) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Certificado Digital') ?></th>
            <td><?= h($tecnicoPerito->certificado_digital) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Foto Assinatura Digital') ?></th>
            <td><?= h($tecnicoPerito->foto_assinatura_digital) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($tecnicoPerito->id) ?></td>
        </tr>
    </table>
</div>
