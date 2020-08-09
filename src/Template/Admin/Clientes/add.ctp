<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Clientes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="clientes form large-9 medium-8 columns content">
    <?= $this->Form->create($cliente) ?>
    <fieldset>
        <legend><?= __('Add Cliente') ?></legend>
        <?php
            echo $this->Form->control('nome_fantasia');
            echo $this->Form->control('razao_social');
            echo $this->Form->control('cnpj_cpf');
            echo $this->Form->control('cep');
            echo $this->Form->control('endereco');
            echo $this->Form->control('bairro');
            echo $this->Form->control('cidade');
            echo $this->Form->control('uf');
            echo $this->Form->control('responsavel_nome');
            echo $this->Form->control('responsavel_email');
            echo $this->Form->control('responsavel_telefone');
            echo $this->Form->control('responsavel_financeiro_nome');
            echo $this->Form->control('responsavel_financeiro_email');
            echo $this->Form->control('responsavel_financeiro_telefone');
            echo $this->Form->control('tipo_cobranca');
            echo $this->Form->control('ativo');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
