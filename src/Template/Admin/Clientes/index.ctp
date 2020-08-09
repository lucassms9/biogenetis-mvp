<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Cliente[]|\Cake\Collection\CollectionInterface $clientes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cliente'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="clientes index large-9 medium-8 columns content">
    <h3><?= __('Clientes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nome_fantasia') ?></th>
                <th scope="col"><?= $this->Paginator->sort('razao_social') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cnpj_cpf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cep') ?></th>
                <th scope="col"><?= $this->Paginator->sort('endereco') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bairro') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cidade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('uf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_telefone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_financeiro_nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_financeiro_email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('responsavel_financeiro_telefone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('tipo_cobranca') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ativo') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= $this->Number->format($cliente->id) ?></td>
                <td><?= h($cliente->nome_fantasia) ?></td>
                <td><?= h($cliente->razao_social) ?></td>
                <td><?= h($cliente->cnpj_cpf) ?></td>
                <td><?= h($cliente->cep) ?></td>
                <td><?= h($cliente->endereco) ?></td>
                <td><?= h($cliente->bairro) ?></td>
                <td><?= h($cliente->cidade) ?></td>
                <td><?= h($cliente->uf) ?></td>
                <td><?= h($cliente->responsavel_nome) ?></td>
                <td><?= h($cliente->responsavel_email) ?></td>
                <td><?= h($cliente->responsavel_telefone) ?></td>
                <td><?= h($cliente->responsavel_financeiro_nome) ?></td>
                <td><?= h($cliente->responsavel_financeiro_email) ?></td>
                <td><?= h($cliente->responsavel_financeiro_telefone) ?></td>
                <td><?= h($cliente->tipo_cobranca) ?></td>
                <td><?= h($cliente->ativo) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $cliente->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $cliente->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $cliente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cliente->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
