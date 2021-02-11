<?php echo $this->element('admin/home/index'); ?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                    <div class="row">
                        <div style="text-align:center" class="col-xl-12">

                            <?php if(!empty($codigo_croquiParam)): ?>
                                <h3>Código do Croqui: <?= $codigo_croquiParam; ?></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                        <?= $this->Form->create($croqui, ['id' => 'formCroqui']) ?>
                        <?php echo $this->element('admin/croqui/form', [
                            'disabled' => true,
                        ]); ?>
                        <div class="row">
                            <div class="col-md-1">
                                <?= $this->Form->button(__('Salvar'), ['type' => 'button', 'onclick' => 'validateForm()' ,'class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>

                        <div style="margin-top: 15px;" class="col-md-12">
                                <h1>Pedidos</h1>

                            <div class="row">
                                <div class="card">
                                    <div style="" class="card-body">
                                        <form id="formFilter" method="get">
                                        <div class="row">

                                        <div class="col-md-3">
                                            <?= $this->Form->control('nome_paciente', [ 'label' =>'Nome do Paciente', 'class' => 'form-control', 'value' => @$this->request->getQuery('nome_paciente') ]);?>
                                        </div>

                                        <div class="col-md-2">
                                            <?= $this->Form->control('date_de', [ 'label' =>'Data de', 'class' => 'form-control', 'value' => @$this->request->getQuery('date_de') ]);?>
                                        </div>
                                        <div class="col-md-2">
                                            <?= $this->Form->control('date_ate', [ 'label' =>'Data até', 'class' => 'form-control', 'value' => @$this->request->getQuery('date_ate') ]);?>
                                        </div>
                                        <div class="col-md-2">
                                            <div style="margin-top: 28px; display:flex;" class="">
                                                <button style="margin-right: 5px;" type="submit" id="btn-form-filter" class="btn btn-primary  mt-3 mt-sm-0">Filtrar</button>
                                            </div>
                                        </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Codigo do Pedido</th>
                                                <th>Nome do Paciente</th>
                                                <th>Criação do Pedido</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pedidos_triagem as $key => $pedido):?>
                                                <tr>
                                                    <th scope="row">
                                                       <input type="checkbox" onchange="changeCheckPedido(this)" name="pedidos[]" value="<?=$pedido->codigo_pedido?>" />
                                                    </th>
                                                    <td><?= $pedido->codigo_pedido; ?></td>
                                                    <td><?= $pedido->anamnese->paciente->nome; ?></td>
                                                    <td><?= $pedido->created; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 10px" class="row">

                    </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/croqui/gerador.js"></script>
