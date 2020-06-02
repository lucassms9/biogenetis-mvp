
<?php echo $this->element('admin/home/index');?>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">

         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body"> 
                        
                        <div class="row">
                            <div style="display: flex;justify-content: flex-end;" class="col-xl-12">

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <?= $this->Form->create(null,['action' => 'sendEmail']) ?>
                                     <input type="hidden" name="amostra_id" value="<?=@$this->request->query['amostra_id']?>">
                                         <input type="hidden" name="lote" value="<?=@$this->request->query['lote']?>">
                                        <input type="hidden" name="lote" value="<?=@$this->request->query['lote']?>">
                                        <input type="hidden" name="data_init" value="<?=@$this->request->query['data_init']?>">
                                        <input type="hidden" name="data_fim" value="<?=@$this->request->query['data_fim']?>">
                                    <button type="submit" class="btn btn-primary"> 
                                        <i style="margin-right: 5px;" class="mdi mdi-email-multiple"></i>Enviar por E-mail
                                    </button>
                                     <?= $this->Form->end() ?>
                                   

                                    <?= $this->Form->create(null,['action' => 'generateExcel']) ?>
                                        <input type="hidden" name="amostra_id" value="<?=@$this->request->query['amostra_id']?>">
                                         <input type="hidden" name="lote" value="<?=@$this->request->query['lote']?>">
                                        <input type="hidden" name="lote" value="<?=@$this->request->query['lote']?>">
                                        <input type="hidden" name="data_init" value="<?=@$this->request->query['data_init']?>">
                                        <input type="hidden" name="data_fim" value="<?=@$this->request->query['data_fim']?>">
                                        <button style="background-color: #0089d8;border-color: #0089d8;" type="submit" class="btn btn-secondary">
                                            <i class="mdi mdi-file-excel"></i> Gerar Excel
                                        </button>
                                    <?= $this->Form->end() ?>
                                     <button style="background-color: #31b1fb;border-color: #31b1fb;"  onclick="printerPage();" type="button" class="btn btn-secondary">
                                        <i style="margin-right: 5px;" class="mdi mdi-printer"></i>Imprimir
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div id="printer">
                        <div class="row">
                            <div class="col-md-12 printHeader">
                                <img width="100%" src="<?= $this->Url->build('/', true);?>img/header-printer.png">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('code_amostra','Amostra ID') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created','Data') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('lote','Lote') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('uf','UF') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('idade') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                                      
                                         <th scope="col"><?= $this->Paginator->sort('resultado') ?></th>
                                        <!-- <th scope="col" class="actions"><?= __('Ações') ?></th> -->
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($amostras as $amostra): ?>
                                        <tr>
                                            <td><?= $this->Number->format($amostra->id) ?></td>
                                            <td><?= h($amostra->code_amostra) ?></td>
                                            <td><?= h($amostra->created) ?></td>
                                            <td><?= h($amostra->lote) ?></td>
                                            <td><?= h($amostra->uf) ?></td>
                                            <td><?= $this->Number->format($amostra->idade) ?></td>
                                            <td><?= h($amostra->sexo) ?></td>
                                            <td><?= h($amostra->exame->resultado) ?></td>
                                            <!-- <td class="actions"> -->
                                                <!-- <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $amostra->code_amostra], ['escape' => false]) ?> -->
                                                <!-- <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $amostra->code_amostra], ['escape' => false, 'confirm' => __('Deseja deletar?', $amostra->code_amostra)]) ?> -->

                                            <!-- </td> -->
                                        </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                      
                         <div class="mt-4">
                            <p style="font-weight: bold; font-size: 17px;">Plataforma de Inteligência Artificial Biogenitcs</p>
                            <p>Positivo: Lorem ipsum</p>
                            <p>Negativo: Lorem Ipsum</p>
                            <p>Inconclusivo: Lorem Ipsum</p>

                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- end row -->
    </div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/amostras/index.js"></script>