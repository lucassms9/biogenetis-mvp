<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($cliente, ['type' => 'file']) ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('nome_fantasia', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('razao_social', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('cnpj_cpf', ['class' => 'form-control', 'label' => 'CNPJ']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <?php echo $this->Form->control('cep', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('endereco', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('bairro', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('cidade', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-2">
                                <?php echo $this->Form->control('uf', ['class' => 'form-control', 'label' => 'UF']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_nome', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_email', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_telefone', ['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_financeiro_nome', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_financeiro_email', ['class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('responsavel_financeiro_telefone', ['class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('tipo_cobranca', ['class' => 'form-control', 'options' => $cobranca_tipos, 'empty' => 'Escolha']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('img_header_url', ['class' => 'form-control', 'type' => 'file', 'label' => 'Cabeçalho Laudo']); ?>
                            </div>
                            <div class="col-md-3">
                                <?php echo $this->Form->control('img_footer_url', ['class' => 'form-control', 'type' => 'file', 'label' => 'Rodapé Laudo']); ?>
                            </div>
                        </div>

                        <div style="margin-top: 10px" class="row">
                            <div class="col-md-4">
                                <?php echo $this->Form->control('ativo', ['class' => 'custom-style-check']); ?>
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
