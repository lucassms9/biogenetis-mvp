<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <?= $this->Flash->render(); ?>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'paciente' ? 'active' :  ''; ?>" data-toggle="tab" href="#paciente" role="tab" aria-selected="true">
                                    <i class="fas fa-user-alt"></i> <span class="d-none d-md-inline-block">Paciente</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'anamnese' ? 'active' :  ''; ?>" data-toggle="tab" href="#anamnese" role="tab" aria-selected="true">
                                    <i class="fas fa-list-ul"></i> <span class="d-none d-md-inline-block">Anamnese</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'pagamento' ? 'active' :  ''; ?>" data-toggle="tab" href="#pagamento" role="tab" aria-selected="false">
                                    <i class="fas fa-money-check-alt"></i> <span class="d-none d-md-inline-block">Forma de Pagamento</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'etiqueta' ? 'active' :  ''; ?>" data-toggle="tab" href="#etiqueta" role="tab" aria-selected="false">
                                    <i class="fas fa-barcode"></i> <span class="d-none d-md-inline-block">Gerar Etiquetas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'croqui' ? 'active' :  ''; ?>" data-toggle="tab" href="#croqui" role="tab" aria-selected="false">
                                    <i class="fas fa-cubes"></i> <span class="d-none d-md-inline-block">Croqui</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $tab_current === 'laudo' ? 'active' :  ''; ?>" data-toggle="tab" href="#laudo" role="tab" aria-selected="false">
                                    <i class="fas fa-cubes"></i> <span class="d-none d-md-inline-block">Laudo</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <div class="tab-pane <?= $tab_current === 'paciente' ? 'active' :  ''; ?>" id="paciente" role="tabpanel">
                                <h2>Paciente</h2>
                                <?php echo $this->element('admin/paciente/form', [
                                    'disabled' => 'disabled'
                                ]);?>
                            </div>
                            <div class="tab-pane <?= $tab_current === 'anamnese' ? 'active' :  ''; ?>" id="anamnese" role="tabpanel">
                                <h2>Anamnese</h2>
                                <?php echo $this->element('admin/anamnese/form', [
                                    'disabled' => true
                                ]);?>
                            </div>
                            <div class="tab-pane <?= $tab_current === 'pagamento' ? 'active' :  ''; ?>" id="pagamento" role="tabpanel">
                                <h2>Pagamento</h2>
                                <?php echo $this->element('admin/pagamento/form', [
                                    'disabled' => true,
                                    'pedido' => $pedido
                                ]);?>
                            </div>
                            <div class="tab-pane <?= $tab_current === 'etiqueta' ? 'active' :  ''; ?>" id="etiqueta" role="tabpanel">
                                <?php echo $this->element('admin/etiquetas/generate');?>
                            </div>
                            <div class="tab-pane <?= $tab_current === 'croqui' ? 'active' :  ''; ?>" id="croqui" role="tabpanel">
                                <?php echo $this->element('admin/croqui/view', [
                                    'disabled' => true,
                                    'pedido' => $pedido
                                ]);?>
                            </div>
                            <div class="tab-pane <?= $tab_current === 'laudo' ? 'active' :  ''; ?>" id="laudo" role="tabpanel">
                                <?php echo $this->element('admin/pedido/laudo');?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/atendimento/pedido.js"></script>
