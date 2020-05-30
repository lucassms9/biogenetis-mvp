<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Relatório</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Relatório</a></li>
            <li class="breadcrumb-item active">Exames</li>
            </ol>
        </div>
        <div class="col-md-4">

        </div>
    </div>

</div>
</div>
<!-- end page title end breadcrumb -->


<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body"> 
                         <h4>Faça o filtro, utilizando um dos campos baixo</h4>
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
                             <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Date Fim</label>
                               <input class="form-control" type="date" name="data_fim" value="">
                            </div>
                        </div>

                         <div style="margin-top: 10px" class="row">
                            <div class="col-md-4">
                                <?= $this->Form->button(__('Gerar Relatório'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>
                        </div>

                        <?= $this->Form->end() ?>

                    </div>
                </div>

                 <div class="card">
                    <div class="card-body"> 
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>