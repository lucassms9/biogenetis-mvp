<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <h2>Saldo Atual: <?= $saldo; ?></h2>
                            </div>
                        </div>

                        <?= $this->Form->create() ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('novo_saldo', ['class' => 'form-control', 'label' => 'Adicionar Saldo', 'type' => 'number', 'pattern' => "[0-9]{10}"]); ?>
                            </div>
                        </div>
                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-1">

                                <?= $this->Html->link(
                                    $this->Form->button(
                                        __('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']
                                    ),
                                    ['action' => 'index'],
                                    ['escape' => false]
                                ) ?>
                            </div>
                            <div class="col-md-1">
                                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>

                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
