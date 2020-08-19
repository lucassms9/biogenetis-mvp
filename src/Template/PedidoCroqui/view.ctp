<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PedidoCroqui $pedidoCroqui
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Pedido Croqui'), ['action' => 'edit', $pedidoCroqui->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Pedido Croqui'), ['action' => 'delete', $pedidoCroqui->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pedidoCroqui->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Pedido Croqui'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido Croqui'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Pedidos'), ['controller' => 'Pedidos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido'), ['controller' => 'Pedidos', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Pedido Croqui Valores'), ['controller' => 'PedidoCroquiValores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Pedido Croqui Valore'), ['controller' => 'PedidoCroquiValores', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pedidoCroqui view large-9 medium-8 columns content">
    <h3><?= h($pedidoCroqui->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Pedido') ?></th>
            <td><?= $pedidoCroqui->has('pedido') ? $this->Html->link($pedidoCroqui->pedido->id, ['controller' => 'Pedidos', 'action' => 'view', $pedidoCroqui->pedido->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($pedidoCroqui->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Croqui Tipo Id') ?></th>
            <td><?= $this->Number->format($pedidoCroqui->croqui_tipo_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($pedidoCroqui->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($pedidoCroqui->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Pedido Croqui Valores') ?></h4>
        <?php if (!empty($pedidoCroqui->pedido_croqui_valores)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Pedido Croqui Id') ?></th>
                <th scope="col"><?= __('Conteudo') ?></th>
                <th scope="col"><?= __('Coluna Linha') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($pedidoCroqui->pedido_croqui_valores as $pedidoCroquiValores): ?>
            <tr>
                <td><?= h($pedidoCroquiValores->id) ?></td>
                <td><?= h($pedidoCroquiValores->pedido_croqui_id) ?></td>
                <td><?= h($pedidoCroquiValores->conteudo) ?></td>
                <td><?= h($pedidoCroquiValores->coluna_linha) ?></td>
                <td><?= h($pedidoCroquiValores->created) ?></td>
                <td><?= h($pedidoCroquiValores->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'PedidoCroquiValores', 'action' => 'view', $pedidoCroquiValores->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'PedidoCroquiValores', 'action' => 'edit', $pedidoCroquiValores->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'PedidoCroquiValores', 'action' => 'delete', $pedidoCroquiValores->id], ['confirm' => __('Are you sure you want to delete # {0}?', $pedidoCroquiValores->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
