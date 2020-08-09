<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Croqui $croqui
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Croquis'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Equipamentos'), ['controller' => 'Equipamentos', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Equipamento'), ['controller' => 'Equipamentos', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="croquis form large-9 medium-8 columns content">
    <?= $this->Form->create($croqui) ?>
    <fieldset>
        <legend><?= __('Add Croqui') ?></legend>
        <?php
            echo $this->Form->control('descricao');
            echo $this->Form->control('foto_url');
            echo $this->Form->control('qtde_posi_placa');
            echo $this->Form->control('tipo_exame_recomendado');
            echo $this->Form->control('tipo_equipament_recomendado');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
