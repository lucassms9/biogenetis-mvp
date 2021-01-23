<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ExtratoSaldo $extratoSaldo
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $extratoSaldo->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $extratoSaldo->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Extrato Saldo'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Vouchers'), ['controller' => 'Vouchers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Voucher'), ['controller' => 'Vouchers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="extratoSaldo form large-9 medium-8 columns content">
    <?= $this->Form->create($extratoSaldo) ?>
    <fieldset>
        <legend><?= __('Edit Extrato Saldo') ?></legend>
        <?php
            echo $this->Form->control('voucher_id', ['options' => $vouchers, 'empty' => true]);
            echo $this->Form->control('type');
            echo $this->Form->control('valor');
            echo $this->Form->control('created_by', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
