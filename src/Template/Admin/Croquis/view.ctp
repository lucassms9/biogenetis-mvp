<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Croqui $croqui
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Croqui'), ['action' => 'edit', $croqui->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Croqui'), ['action' => 'delete', $croqui->id], ['confirm' => __('Are you sure you want to delete # {0}?', $croqui->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Croquis'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Croqui'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Equipamentos'), ['controller' => 'Equipamentos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Equipamento'), ['controller' => 'Equipamentos', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="croquis view large-9 medium-8 columns content">
    <h3><?= h($croqui->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Foto Url') ?></th>
            <td><?= h($croqui->foto_url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Exame Recomendado') ?></th>
            <td><?= h($croqui->tipo_exame_recomendado) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($croqui->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Qtde Posi Placa') ?></th>
            <td><?= $this->Number->format($croqui->qtde_posi_placa) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Equipament Recomendado') ?></th>
            <td><?= $this->Number->format($croqui->tipo_equipament_recomendado) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Descricao') ?></h4>
        <?= $this->Text->autoParagraph(h($croqui->descricao)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Equipamentos') ?></h4>
        <?php if (!empty($croqui->equipamentos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Nome') ?></th>
                <th scope="col"><?= __('Descricao') ?></th>
                <th scope="col"><?= __('Foto Url') ?></th>
                <th scope="col"><?= __('Tipo Exame') ?></th>
                <th scope="col"><?= __('Croqui Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($croqui->equipamentos as $equipamentos): ?>
            <tr>
                <td><?= h($equipamentos->id) ?></td>
                <td><?= h($equipamentos->nome) ?></td>
                <td><?= h($equipamentos->descricao) ?></td>
                <td><?= h($equipamentos->foto_url) ?></td>
                <td><?= h($equipamentos->tipo_exame) ?></td>
                <td><?= h($equipamentos->croqui_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Equipamentos', 'action' => 'view', $equipamentos->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Equipamentos', 'action' => 'edit', $equipamentos->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Equipamentos', 'action' => 'delete', $equipamentos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $equipamentos->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
