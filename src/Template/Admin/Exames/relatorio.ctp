<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body"> 
                         <h4>Faça o filtro utilizando um dos campos baixo:</h4>
                         <p>Para buscar todas a amostras processadas clique em gerar relatório.</p>
                        <?= $this->Form->create(null) ?>

                        <div class="row">
                             <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Amostra ID</label>
                                <input class="form-control" name="amostra_id" value="">
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Lote</label>
                                <input class="form-control" name="lote" value="">
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Date Início</label>
                                <input class="form-control" name="data_init" value="" type="date">
                            </div>
                             <div style="display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    margin-top: 35px;">
                                 e/ou
                             </div>
                             <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Date Fim</label>
                               <input class="form-control" type="date" name="data_fim" value="">
                            </div>
                        </div>

                         <div style="margin-top: 15px" class="row">
                            <div class="col-md-4">
                                <?= $this->Form->button(__('Gerar Relatório'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>
                        </div>

                        <?= $this->Form->end() ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>