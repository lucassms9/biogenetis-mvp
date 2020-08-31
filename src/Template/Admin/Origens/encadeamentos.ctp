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
                        <?= $this->Form->create($encadeamento,['id'=> 'formSaveEncads']) ?>

                        <div style="font-size: 16px;font-weight: bold;" class="row">
                            <div style="text-align: center;color: #4d9ff1;display: flex;justify-content: center;" class="col-md-12">
                                Nome: <?= $origen->nome_origem ?>
                                /
                                URL:  <?= $origen->url_request ?>

                                <select style="width: 160px;margin: 0 15px;" name="regra_main" class="form-control" id="regra-main">
                                    <option value="">Escolha</option>
                                    <option <?= $origen->regra_encadeamento == 'Positivo' ? 'selected' : ''?> value="Positivo">Positivo</option>
                                    <option <?= $origen->regra_encadeamento == 'Negativo' ? 'selected' : ''?> value="Negativo">Negativo</option>
                                    <option <?= $origen->regra_encadeamento == 'Indeterminado' ? 'selected' : ''?> value="Indeterminado">Indeterminado</option>
                                </select>

                            </div>
                        </div>


                        <div class="row">
                            <div style="display: flex;" class="col-md-12">
                                <h2 style="margin-right: 10px">Encadeamentos</h2>
                                  <?= $this->Form->button(__('Novo Encadeamento'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'onclick' => 'addEnpoint()', 'type' => 'button']) ?>
                            </div>
                        </div>

                        <?php $total = count($origen->encadeamentos); foreach ($origen->encadeamentos as $key => $encade): ?>
                            <div id="<?=$encade->id?>" style="margin-top: 15px" class="row">
                                 <div class="col-md-5">
                                    <?php echo $this->Form->control('url_encad_'.$encade->id.'',['label' => 'URL encadeamento', 'class' => 'form-control', 'name' => 'url_encad[]' , 'default' => $encade->origem_encadeamento_id, 'options' => $combo_endpoint, 'empty' => 'Escolha']); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->control('regra_'.$encade->id.'',['label' => 'Regra', 'class' => 'form-control combo-rule','name' => 'regra[]', 'default' => $encade->regra, 'options' => $regras, 'empty' => 'Escolha']); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->control('ordem_'.$encade->id.'',['label' => 'Ordem', 'class' => 'form-control', 'name' => 'ordem[]', 'value' => $encade->ordem]); ?>
                                </div>
                                <div style="margin-top: 30px;" class="col-md-3">
                                    <div class="buttons">


                                        <?php
                                            $style_custom = 'display:none';
                                            $item = $key + 1;

                                            if($item == $total){
                                                $style_custom = '';
                                            }


                                        ?>

                                        <?= $this->Form->button(__('Remover'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light btn-remove', 'onclick' => 'removeEnpoint('.$encade->id.')', 'type' => 'button', 'style'=> $style_custom]) ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>



                        <div id="content_inputs">
                            <?php if($nextOrder > 0):?>
                            <!-- <div id="<?=$nextOrder?>" class="row">
                                <div class="col-md-5">
                                    <?php echo $this->Form->control('url_encad_'.$nextOrder.'',['name' => 'url_encad[]','label' => 'URL encadeamento', 'class' => 'form-control', 'options' => $combo_endpoint, 'empty' => 'Escolha']); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->control('regra_'.$nextOrder.'',['name' => 'regra[]','label' => 'Regra', 'class' => 'form-control', 'options' => $regras, 'empty' => 'Escolha']); ?>
                                </div>
                                <div class="col-md-2">
                                    <?php echo $this->Form->control('ordem_'.$nextOrder.'',['name' => 'ordem[]','value'=>$nextOrder, 'label' => 'Ordem', 'class' => 'form-control']); ?>
                                </div>
                                <div style="margin-top: 30px;" class="col-md-3">
                                    <div class="buttons">
                                        <?= $this->Form->button(__('Remover'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'onclick' => 'removeEnpoint('.$nextOrder.')', 'type' => 'button']) ?>
                                    </div>
                                </div>
                            </div> -->
                        <?php endif;?>
                        </div>

                         <hr>


                        <div style="margin-top: 10px" class="row">

                                <div style="display: flex;">
                                    <?= $this->Html->link(
                                    $this->Form->button(__('Voltar'),
                                        ['type' => 'button', 'class' => 'btn btn-secondary btn-rounded waves-effect waves-light']),
                                        ['action' => 'index'],
                                        ['escape' => false]
                                        ) ?>
                                    <?= $this->Form->button(__('Salvar'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light', 'type' => 'button', 'onclick' => 'saveDataEncads()', 'style' => 'margin-left: 5px;']) ?>
                                </div>

                                <div class="col-md-1">

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
<script>
    var nextOrder = <?= $nextOrder;?>
</script>
<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/origens/encadeamentos.js"></script>
