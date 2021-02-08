<style>

</style>

<?php echo $this->element('admin/home/index'); ?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div style="top: 20px;position: relative;" class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">


                    <div class="card-body">
                        <div class="row">


                            <div class="col-md-3">
                                <a style="width: 290px;" href="<?= $this->Url->build('/admin/pacientes/add', true); ?>" class="btn btn-sm">
                                    <div style="padding: 10px;" class="card text-white bg-danger">
                                        <div style="display: flex;justify-content: center;">
                                            <h5 style="margin: 4px;" class="text-white">Novo Cadastro</h5><i style="font-size: 20px;" class="mdi mdi-progress-clock"></i>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="text-white"><?= $result['aguardando_atendimento'] ?></h3>
                                        </div>
                                    </div>
                                </a>

                                <div class="separator-row"></div>
                                <a style="width: 290px;" href="<?= $this->Url->build('/admin/pedidos?status=EmAtendimento', true); ?>" class="btn btn-sm">
                                    <div style="padding: 10px;" class="card text-white bg-warning">
                                        <div style="display: flex;justify-content: center;">
                                            <h5 style="margin: 4px;" class="text-white">Em Atendimento</h5><i style="font-size: 20px;" class="mdi mdi-pencil"></i>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="text-white"><?= $result['atendimento'] ?></h3>
                                        </div>
                                    </div>
                                </a>

                            </div>

                            <?php if ($_SESSION['Auth']['User']['user_type_id'] !== 5) : ?>
                            <div class="col-3">
                                <a style="width: 290px;" href="<?= $this->Url->build('/admin/croquis/gerador', true); ?>" class="btn btn-sm">
                                    <div style="padding: 10px;" class="card text-white bg-info">
                                        <div style="display: flex;justify-content: center;">
                                            <h5 style="margin: 4px;" class="text-white">Em Triagem</h5><i style="font-size: 20px;" class="mdi mdi-progress-wrench"></i>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="text-white"><?= $result['triagem'] ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php if ($_SESSION['Auth']['User']['user_type_id'] !== 5) : ?>
                            <div class="col-3">
                                <a style="width: 290px;" href="<?= $this->Url->build('/admin/pedidos/croquis', true); ?>" class="btn btn-sm">
                                    <div style="padding: 10px; background-color:#4399f0" class="card text-white">
                                        <div style="display: flex;justify-content: center;">
                                            <h5 style="margin: 4px;" class="text-white">Em Diagnóstico</h5><i style="font-size: 20px;" class="mdi mdi-medical-bag"></i>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="text-white"><?= $result['diagnostico'] ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="col-3">
                                <a style="width: 290px;" href="<?= $this->Url->build('/admin/pedidos?status=Finalizado', true); ?>" class="btn btn-sm">
                                    <div style="padding: 10px;" class="card text-white bg-success">
                                        <div style="display: flex;justify-content: center;">
                                            <h5 style="margin: 4px;" class="text-white">Finalizados</h5><i style="font-size: 20px;" class="mdi mdi-clock-check-outline"></i>
                                        </div>
                                        <div class="card-title">
                                            <h3 class="text-white"><?= $result['finalizados'] ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
