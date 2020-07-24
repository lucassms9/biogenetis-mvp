<style>
    .custom-style-check{
        margin-right: 10px;
    }    
</style>

<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">
                        <?= $this->Form->create($encadeamento) ?>

                        <div style="font-size: 16px;font-weight: bold;" class="row">
                            <div style="text-align: center;color: #4d9ff1;display: flex;justify-content: center;" class="col-md-12">
                                Nome: <?= $origen->nome_origem ?>
                                /
                                URL:  <?= $origen->url_request ?>

                                <select style="width: 160px;margin: 0 15px;" name="regra_main" class="form-control" id="regra-main">
                                    <option value="">Escolha</option>
                                    <option <?= $origen->regra_encadeamento == 'Positivo' ? 'selected' : ''?> value="Positivo">Positivo</option>
                                    <option <?= $origen->regra_encadeamento == 'Negativo' ? 'selected' : ''?> value="Negativo">Negativo</option> 
                                    <option <?= $origen->regra_encadeamento == 'Inadequado' ? 'selected' : ''?> value="Inadequado">Inadequado</option>
                                </select>

                            </div>
                        </div>
                      
                           
                        <div class="row">
                            <div class="col-md-12">
                                <div style="display: flex;">
                                    <h5 style="margin-top: 10px;margin-right: 10px;" >Novos Encademantos</h5>
                                    <?= $this->Form->button(__('Adicionar Encadeamento'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'onclick' => 'addEnpoint()', 'type' => 'button']) ?>
                                </div>
                                <input type="hidden" name="endpoint_parent" id="endpoint_parent" value="<?= $origen->id; ?>">
                            <div id="content_inputs"></div>
                            </div>
                        </div>
                        
                        
                        <hr>

                          <div class="row">
                            <div class="col-md-12">
                                 <h5>Encademantos Cadastrados</h5>
                                 <div id="content_inputs_cadastros"></div>
                                <hr>
                            </div>
                        </div>
                        
                         <hr>


                        <div style="margin-top: 10px" class="row">
                          
                                <div class="col-md-2">

                                    <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                                </div>

                               <div class="col-md-6">
                                    
                               </div> 
                            </div>
                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/origens/encadeamentos.js"></script>