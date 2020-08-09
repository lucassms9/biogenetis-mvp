<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Paciente[]|\Cake\Collection\CollectionInterface $pacientes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Paciente'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="pacientes index large-9 medium-8 columns content">
    <h3><?= __('Pacientes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nome') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cpf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rg') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('celular') ?></th>
                <th scope="col"><?= $this->Paginator->sort('telefone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                <th scope="col"><?= $this->Paginator->sort('data_nascimento') ?></th>
                <th scope="col"><?= $this->Paginator->sort('endereco') ?></th>
                <th scope="col"><?= $this->Paginator->sort('bairro') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cep') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cidade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('uf') ?></th>
                <th scope="col"><?= $this->Paginator->sort('foto_perfil_url') ?></th>
                <th scope="col"><?= $this->Paginator->sort('foto_doc_url') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nome_da_mae') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nacionalidade') ?></th>
                <th scope="col"><?= $this->Paginator->sort('pais_residencia') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pacientes as $paciente): ?>
            <tr>
                <td><?= $this->Number->format($paciente->id) ?></td>
                <td><?= h($paciente->nome) ?></td>
                <td><?= h($paciente->cpf) ?></td>
                <td><?= h($paciente->rg) ?></td>
                <td><?= h($paciente->email) ?></td>
                <td><?= h($paciente->celular) ?></td>
                <td><?= h($paciente->telefone) ?></td>
                <td><?= h($paciente->sexo) ?></td>
                <td><?= h($paciente->data_nascimento) ?></td>
                <td><?= h($paciente->endereco) ?></td>
                <td><?= h($paciente->bairro) ?></td>
                <td><?= h($paciente->cep) ?></td>
                <td><?= h($paciente->cidade) ?></td>
                <td><?= h($paciente->uf) ?></td>
                <td><?= h($paciente->foto_perfil_url) ?></td>
                <td><?= h($paciente->foto_doc_url) ?></td>
                <td><?= h($paciente->nome_da_mae) ?></td>
                <td><?= h($paciente->nacionalidade) ?></td>
                <td><?= h($paciente->pais_residencia) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $paciente->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $paciente->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $paciente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $paciente->id)]) ?>
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
