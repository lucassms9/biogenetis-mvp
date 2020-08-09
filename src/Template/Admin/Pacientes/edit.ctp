<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Paciente $paciente
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $paciente->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $paciente->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Pacientes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pacientes form large-9 medium-8 columns content">
    <?= $this->Form->create($paciente) ?>
    <fieldset>
        <legend><?= __('Edit Paciente') ?></legend>
        <?php
            echo $this->Form->control('nome');
            echo $this->Form->control('cpf');
            echo $this->Form->control('rg');
            echo $this->Form->control('email');
            echo $this->Form->control('celular');
            echo $this->Form->control('telefone');
            echo $this->Form->control('sexo');
            echo $this->Form->control('data_nascimento', ['empty' => true]);
            echo $this->Form->control('endereco');
            echo $this->Form->control('bairro');
            echo $this->Form->control('cep');
            echo $this->Form->control('cidade');
            echo $this->Form->control('uf');
            echo $this->Form->control('foto_perfil_url');
            echo $this->Form->control('foto_doc_url');
            echo $this->Form->control('nome_da_mae');
            echo $this->Form->control('nacionalidade');
            echo $this->Form->control('pais_residencia');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
