<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Exams'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Import Exams'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create(null,['id' => 'formFiles','enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Importar Arquivo') ?></legend>
        <div id="files-input">
        </div>
    </fieldset>
    <?= $this->Form->button(__('Submit'),['type' => 'button', 'onClick' => 'sendFiles()']) ?>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/exames/import.js"></script>
