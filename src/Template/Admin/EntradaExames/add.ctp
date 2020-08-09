<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EntradaExame $entradaExame
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Entrada Exames'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="entradaExames form large-9 medium-8 columns content">
    <?= $this->Form->create($entradaExame) ?>
    <fieldset>
        <legend><?= __('Add Entrada Exame') ?></legend>
        <?php
            echo $this->Form->control('nome');
            echo $this->Form->control('descricao');
            echo $this->Form->control('valor_particular');
            echo $this->Form->control('valor_laboratorio_conveniado');
            echo $this->Form->control('valor_laboratorio_nao_conveniado');
            echo $this->Form->control('tipo_exame');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
