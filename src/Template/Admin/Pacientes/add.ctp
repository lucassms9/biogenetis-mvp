<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div style="margin-top: 68px;" class="page-content-wrapper">
    <div class="container-fluid">
    <div class="row">
    <div class="col-xl-12">
    <?= $this->Flash->render('filterpaciente') ?>
        <div class="card">
            <div class="card-body">
                <form id="getPaciente" method="get">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $this->Form->control('paciente_nome',['label' =>'Nome do Paciente', 'class' => 'form-control', 'value' => !empty($query['paciente_nome'])? !empty($query['paciente_nome']) : '' ]);?>
                        </div>
                        <div class="col-md-3">
                            <?= $this->Form->control('paciente_cpf',['label' =>'CPF do Paciente', 'class' => 'form-control cpf', 'value' => !empty($query['paciente_cpf']) ? $query['paciente_cpf'] : '']);?>
                        </div>
                        <div style="margin-top: 29px" class="col-md-3">
                        <input name="tipo" type="hidden" value="" id="tipofiltro" />
                        <button style="margin-right: 5px;" type="button" onclick="submitGetPaciente('find')" class="btn btn-primary  mt-3 mt-sm-0">Buscar</button>

                        <button style="margin-right: 5px;background-color: #0089d8;border-color: #0089d8;" onclick="submitGetPaciente('new')"  type="button" class="btn btn-primary  mt-3 mt-sm-0">Novo Cadastro</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
</div>

<div style="margin-top: 0 !important;" class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($paciente,['id'=>'formnovopaciente']) ?>

                        <?php echo $this->element('admin/paciente/form',[
                            'disabled' => $disabled_inputs
                        ]);?>
                        <hr/>

                        <h2>Anamnese</h2>
                        <?php echo $this->element('admin/anamnese/form',[
                            'disabled' => $disabled_inputs
                        ]);?>

                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-1">

                                <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                            </div>
                            <div class="col-md-1">
                                <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'type' => 'button', 'id'=>'submitformpaciente']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/paciente/add.js"></script>
