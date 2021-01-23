<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PedidoCroquiValore $pedidoCroquiValore
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $pedidoCroquiValore->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $pedidoCroquiValore->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Pedido Croqui Valores'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="pedidoCroquiValores form large-9 medium-8 columns content">
    <?= $this->Form->create($pedidoCroquiValore) ?>
    <fieldset>
        <legend><?= __('Edit Pedido Croqui Valore') ?></legend>
        <?php
            echo $this->Form->control('pedido_croqui_id');
            echo $this->Form->control('conteudo');
            echo $this->Form->control('coluna_linha');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
