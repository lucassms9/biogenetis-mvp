<?php echo $this->element('admin/home/index');?>


<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                    <?= $this->Form->create(null,['type' => 'get']) ?>
                    <div class="row">
                             <div class="col-md-3">
                                <label for="example-date-input" class="col-form-label">Date Início</label>
                                <input class="form-control" name="data_init" value="<?= @$this->request->getQuery('data_init')?>" type="date">
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
                               <input class="form-control" type="date" name="data_fim" value="<?= @$this->request->getQuery('data_fim')?>">
                            </div>

                            <div style="margin-top: 37px;margin-bottom:20px" class="col-md-4">
                                <?= $this->Form->button(__('Filtrar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>

                        </div>

                        <?= $this->Form->end() ?>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                    <th scope="col"><?= $this->Paginator->sort('nome') ?></th>

                                    <th scope="col"><?= $this->Paginator->sort('Qtde Exames') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Valor Total') ?></th>

                                    </tr>
                                </thead>
                                 <tbody>

                                        <tr>

                                        <td><?= h($result['exame']['nome']) ?></td>
                                        <td><?= h($result['total_exames']) ?></td>
                                        <td><?= number_format($result['valor_total'], 2, ',', '.');  ?></td>

                                        </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
</div>
