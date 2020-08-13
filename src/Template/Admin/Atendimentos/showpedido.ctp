<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <?= $this->Flash->render(); ?>
                    <div class="card-body">
                        <h4 class="header-title">Pedidos</h4>
                        <p class="card-title-desc">Dados gerais</p>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#paciente" role="tab" aria-selected="true">
                                    <i class="fas fa-user-alt"></i> <span class="d-none d-md-inline-block">Paciente</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#anamnese" role="tab" aria-selected="true">
                                    <i class="fas fa-list-ul"></i> <span class="d-none d-md-inline-block">Anamnese</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pagamento" role="tab" aria-selected="false">
                                    <i class="fas fa-money-check-alt"></i> <span class="d-none d-md-inline-block">Forma de Pagamento</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#etiquetas" role="tab" aria-selected="false">
                                    <i class="fas fa-barcode"></i> <span class="d-none d-md-inline-block">Gerar Etiquetas</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="paciente" role="tabpanel">
                                <h1>Paciente</h1>
                            </div>
                            <div class="tab-pane" id="anamnese" role="tabpanel">
                                <h1>anamnese</h1>
                            </div>
                            <div class="tab-pane" id="pagamento" role="tabpanel">
                                <h1>pagamento</h1>
                            </div>
                            <div class="tab-pane" id="etiquetas" role="tabpanel">
                                <h1>etiquetas</h1>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
