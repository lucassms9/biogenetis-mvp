<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Paciente $paciente
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Paciente'), ['action' => 'edit', $paciente->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Paciente'), ['action' => 'delete', $paciente->id], ['confirm' => __('Are you sure you want to delete # {0}?', $paciente->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Pacientes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Paciente'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="pacientes view large-9 medium-8 columns content">
    <h3><?= h($paciente->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome') ?></th>
            <td><?= h($paciente->nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cpf') ?></th>
            <td><?= h($paciente->cpf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rg') ?></th>
            <td><?= h($paciente->rg) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($paciente->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Celular') ?></th>
            <td><?= h($paciente->celular) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Telefone') ?></th>
            <td><?= h($paciente->telefone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sexo') ?></th>
            <td><?= h($paciente->sexo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Endereco') ?></th>
            <td><?= h($paciente->endereco) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bairro') ?></th>
            <td><?= h($paciente->bairro) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cep') ?></th>
            <td><?= h($paciente->cep) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cidade') ?></th>
            <td><?= h($paciente->cidade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Uf') ?></th>
            <td><?= h($paciente->uf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Foto Perfil Url') ?></th>
            <td><?= h($paciente->foto_perfil_url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Foto Doc Url') ?></th>
            <td><?= h($paciente->foto_doc_url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nome Da Mae') ?></th>
            <td><?= h($paciente->nome_da_mae) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nacionalidade') ?></th>
            <td><?= h($paciente->nacionalidade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Pais Residencia') ?></th>
            <td><?= h($paciente->pais_residencia) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($paciente->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Nascimento') ?></th>
            <td><?= h($paciente->data_nascimento) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Exames') ?></h4>
        <?php if (!empty($paciente->exames)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Amostra Id') ?></th>
                <th scope="col"><?= __('Created By') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Resultado') ?></th>
                <th scope="col"><?= __('File Name') ?></th>
                <th scope="col"><?= __('File Extesion') ?></th>
                <th scope="col"><?= __('Amostra Tipo') ?></th>
                <th scope="col"><?= __('Equip Tipo') ?></th>
                <th scope="col"><?= __('Origem Id') ?></th>
                <th scope="col"><?= __('Entrada Exame Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($paciente->exames as $exames): ?>
            <tr>
                <td><?= h($exames->id) ?></td>
                <td><?= h($exames->amostra_id) ?></td>
                <td><?= h($exames->created_by) ?></td>
                <td><?= h($exames->created) ?></td>
                <td><?= h($exames->modified) ?></td>
                <td><?= h($exames->resultado) ?></td>
                <td><?= h($exames->file_name) ?></td>
                <td><?= h($exames->file_extesion) ?></td>
                <td><?= h($exames->amostra_tipo) ?></td>
                <td><?= h($exames->equip_tipo) ?></td>
                <td><?= h($exames->origem_id) ?></td>
                <td><?= h($exames->entrada_exame_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Exames', 'action' => 'view', $exames->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Exames', 'action' => 'edit', $exames->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Exames', 'action' => 'delete', $exames->id], ['confirm' => __('Are you sure you want to delete # {0}?', $exames->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
