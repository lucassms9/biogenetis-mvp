<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SaldoContum $saldoContum
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $saldoContum->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $saldoContum->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Saldo Conta'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Clientes'), ['controller' => 'Clientes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cliente'), ['controller' => 'Clientes', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="saldoConta form large-9 medium-8 columns content">
    <?= $this->Form->create($saldoContum) ?>
    <fieldset>
        <legend><?= __('Edit Saldo Contum') ?></legend>
        <?php
            echo $this->Form->control('cliente_id', ['options' => $clientes, 'empty' => true]);
            echo $this->Form->control('saldo');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
