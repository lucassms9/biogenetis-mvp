<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\EntradaExame $entradaExame
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Entrada Exame'), ['action' => 'edit', $entradaExame->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Entrada Exame'), ['action' => 'delete', $entradaExame->id], ['confirm' => __('Are you sure you want to delete # {0}?', $entradaExame->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Entrada Exames'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Entrada Exame'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="entradaExames view large-9 medium-8 columns content">
    <h3><?= h($entradaExame->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nome') ?></th>
            <td><?= h($entradaExame->nome) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Descricao') ?></th>
            <td><?= h($entradaExame->descricao) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Exame') ?></th>
            <td><?= h($entradaExame->tipo_exame) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($entradaExame->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valor Particular') ?></th>
            <td><?= $this->Number->format($entradaExame->valor_particular) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valor Laboratorio Conveniado') ?></th>
            <td><?= $this->Number->format($entradaExame->valor_laboratorio_conveniado) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Valor Laboratorio Nao Conveniado') ?></th>
            <td><?= $this->Number->format($entradaExame->valor_laboratorio_nao_conveniado) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Exames') ?></h4>
        <?php if (!empty($entradaExame->exames)): ?>
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
            <?php foreach ($entradaExame->exames as $exames): ?>
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
