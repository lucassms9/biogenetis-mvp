<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente $cliente
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Cliente'), ['action' => 'edit', $cliente->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Cliente'), ['action' => 'delete', $cliente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cliente->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Clientes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cliente'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="clientes view large-9 medium-8 columns content">
    <h3><?= h($cliente->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome Fantasia') ?></th>
            <td><?= h($cliente->nome_fantasia) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Razao Social') ?></th>
            <td><?= h($cliente->razao_social) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cnpj Cpf') ?></th>
            <td><?= h($cliente->cnpj_cpf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cep') ?></th>
            <td><?= h($cliente->cep) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Endereco') ?></th>
            <td><?= h($cliente->endereco) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bairro') ?></th>
            <td><?= h($cliente->bairro) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cidade') ?></th>
            <td><?= h($cliente->cidade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Uf') ?></th>
            <td><?= h($cliente->uf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Nome') ?></th>
            <td><?= h($cliente->responsavel_nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Email') ?></th>
            <td><?= h($cliente->responsavel_email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Telefone') ?></th>
            <td><?= h($cliente->responsavel_telefone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Financeiro Nome') ?></th>
            <td><?= h($cliente->responsavel_financeiro_nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Financeiro Email') ?></th>
            <td><?= h($cliente->responsavel_financeiro_email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Responsavel Financeiro Telefone') ?></th>
            <td><?= h($cliente->responsavel_financeiro_telefone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Cobranca') ?></th>
            <td><?= h($cliente->tipo_cobranca) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($cliente->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ativo') ?></th>
            <td><?= $cliente->ativo ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($cliente->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Nome Completo') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Senha') ?></th>
                <th scope="col"><?= __('User Type Id') ?></th>
                <th scope="col"><?= __('Cliente Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Cpf') ?></th>
                <th scope="col"><?= __('Telefone') ?></th>
                <th scope="col"><?= __('Ativo') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($cliente->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->nome_completo) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->senha) ?></td>
                <td><?= h($users->user_type_id) ?></td>
                <td><?= h($users->cliente_id) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td><?= h($users->cpf) ?></td>
                <td><?= h($users->telefone) ?></td>
                <td><?= h($users->ativo) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
