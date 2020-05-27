<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra $amostra
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $amostra->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $amostra->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Amostras'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="amostras form large-9 medium-8 columns content">
    <?= $this->Form->create($amostra) ?>
    <fieldset>
        <legend><?= __('Edit Amostra') ?></legend>
        <?php
            echo $this->Form->control('code_amostra');
            echo $this->Form->control('cep');
            echo $this->Form->control('idade');
            echo $this->Form->control('sexo');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
