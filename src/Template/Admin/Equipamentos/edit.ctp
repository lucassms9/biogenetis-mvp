<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipamento $equipamento
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $equipamento->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $equipamento->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Equipamentos'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Croquis'), ['controller' => 'Croquis', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Croqui'), ['controller' => 'Croquis', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="equipamentos form large-9 medium-8 columns content">
    <?= $this->Form->create($equipamento) ?>
    <fieldset>
        <legend><?= __('Edit Equipamento') ?></legend>
        <?php
            echo $this->Form->control('nome');
            echo $this->Form->control('descricao');
            echo $this->Form->control('foto_url');
            echo $this->Form->control('tipo_exame');
            echo $this->Form->control('croqui_id', ['options' => $croquis, 'empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
