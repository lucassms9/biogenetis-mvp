<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra $amostra
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Amostra'), ['action' => 'edit', $amostra->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Amostra'), ['action' => 'delete', $amostra->id], ['confirm' => __('Are you sure you want to delete # {0}?', $amostra->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Amostras'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Amostra'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Exames'), ['controller' => 'Exames', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Exame'), ['controller' => 'Exames', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="amostras view large-9 medium-8 columns content">
    <h3><?= h($amostra->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Code Amostra') ?></th>
            <td><?= h($amostra->code_amostra) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('UF') ?></th>
            <td><?= h($amostra->uf) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sexo') ?></th>
            <td><?= h($amostra->sexo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($amostra->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idade') ?></th>
            <td><?= $this->Number->format($amostra->idade) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Exames') ?></h4>
        <?php if (!empty($amostra->exames)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Paciente Id') ?></th>
                <th scope="col"><?= __('Amostra Id') ?></th>
                <th scope="col"><?= __('Created By') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Resultado') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($amostra->exames as $exames): ?>
            <tr>
                <td><?= h($exames->id) ?></td>
                <td><?= h($exames->paciente_id) ?></td>
                <td><?= h($exames->amostra_id) ?></td>
                <td><?= h($exames->created_by) ?></td>
                <td><?= h($exames->created) ?></td>
                <td><?= h($exames->modified) ?></td>
                <td><?= h($exames->resultado) ?></td>
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
