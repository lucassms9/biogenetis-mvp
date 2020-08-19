<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PedidoCroquiValore $pedidoCroquiValore
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Pedido Croqui Valore'), ['action' => 'edit', $pedidoCroquiValore->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Pedido Croqui Valore'), ['action' => 'delete', $pedidoCroquiValore->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pedidoCroquiValore->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Pedido Croqui Valores'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido Croqui Valore'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pedidoCroquiValores view large-9 medium-8 columns content">
    <h3><?= h($pedidoCroquiValore->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Conteudo') ?></th>
            <td><?= h($pedidoCroquiValore->conteudo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Coluna Linha') ?></th>
            <td><?= h($pedidoCroquiValore->coluna_linha) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($pedidoCroquiValore->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pedido Croqui Id') ?></th>
            <td><?= $this->Number->format($pedidoCroquiValore->pedido_croqui_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($pedidoCroquiValore->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($pedidoCroquiValore->modified) ?></td>
        </tr>
    </table>
</div>
