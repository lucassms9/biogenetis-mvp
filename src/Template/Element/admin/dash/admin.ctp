
<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <h4 style="padding: 15px 15px 0 15px;">Faça o filtro utilizando um dos campos baixo:</h4>
            <div style="" class="card-body">
                 <form method="get">
                <div class="row">

                 <div class="col-md-2">
                    <!-- <label>Estado</label> -->
                    <?= $this->Form->control('estados_filter',['label' =>'Estado', 'class' => 'form-control', 'type' => 'select', 'options' => $ufs,'empty' =>'Escolha','default' => @$this->request->query['estados_filter']]);?>
                </div>
                <div class="col-md-2">
                    <!-- <label>Estado</label> -->
                    <?= $this->Form->control('equipamentos_filter',['label' =>'Equipamentos', 'class' => 'form-control', 'type' => 'select', 'options' => $equipamentos_options,'empty' =>'Escolha','default' => @$this->request->query['equipamentos_filter']]);?>
                </div>
                <div class="col-md-2">
                    <!-- <label>Estado</label> -->
                    <?= $this->Form->control('amostras_filter',['label' =>'Amostras', 'class' => 'form-control', 'type' => 'select', 'options' => $amostras_options,'empty' =>'Escolha','default' => @$this->request->query['amostras_filter']]);?>
                </div>
                 <div class="col-md-2">
                        <label>Data de</label>
                        <input name="date_init_filter" value="<?=@$this->request->query['date_init_filter']?>" class="form-control datepicker-here" data-language="pt-BR" id="date-init-filter" type="text" />
                    </div>
                     <div style="display: flex;align-items: center;justify-content: center;font-size: 15px;margin-top: 30px;">
                                 e/ou
                             </div>
                    <div class="col-md-2">
                        <label>Data até</label>
                        <input name="date_end_filter" data-language="pt-BR" value="<?=@$this->request->query['date_end_filter']?>" class="form-control datepicker-here" id="date-end-filter" type="text" />
                    </div>
                    <div style="margin-top: 28px" class="">
                        <button style="margin-right: 5px;" type="submit" class="btn btn-primary  mt-3 mt-sm-0">Filtrar</button>
                        <button type="button" style="margin-right: 5px;background-color: #0089d8;border-color: #0089d8;" id="reset-filter" class="btn btn-secondary  mt-3 mt-sm-0">Limpar Filtros</button>
                        <button style="margin-right: 5px;background-color: #31b1fb;border-color: #31b1fb;" type="button" id="printer-dash" class="btn btn-secondary  mt-3 mt-sm-0">Imprimir</button>
                    </div>
                   <!--   <div style="margin-top: 28px;" class="col-md-1">
                        <button type="submit" class="btn btn-primary  mt-3 mt-sm-0">Filtrar</button>
                    </div>
                     <div style="margin-top: 28px;" class="col-md-2">
                        <button type="button" style="background-color: #0089d8;border-color: #0089d8;" id="reset-filter" class="btn btn-secondary  mt-3 mt-sm-0">Limpar Filtros</button>
                    </div>
                   <div style="margin-top: 28px;margin-left: -50px;" class="col-md-1">
                        <button style="background-color: #31b1fb;border-color: #31b1fb;" type="button" id="printer-dash" class="btn btn-secondary  mt-3 mt-sm-0">Imprimir</button>
                    </div> -->
                </div>

                 </form>
            </div>
        </div>

        </div>
        <!-- <div class="col-xl-1"> -->
        <!-- </div> -->
    </div>
</div>

</div>
<div id="printer-dash-to">

     <div class="row">
        <div class="col-md-12 printHeader">
            <img width="100%" src="<?= $this->Url->build('/', true);?>img/header-printer.png">
        </div>
    </div>

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Resumo</h4>

                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Por UF</h4>
                 <div id="column_chart_uf" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

</div>
<!-- end row -->

<div class="row">

  <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Por Faixa Etária</h4>
                 <div id="column_chart_idade" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

   <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Por Sexo</h4>
                 <div id="column_chart_sexo" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-9">
        <div class="card">
            <div class="card-body">
                 <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr style="text-align: center;">
                                        <th  class="td-blue-strong" scope="col">COVID</th>
                                        <th class="td-blue-strong" style="text-align: center;" colspan="11" scope="col">TOTAL</th>
                                    </tr>

                                    <tr style="text-align: center;">
                                        <th class="td-blue-strong" class="td-blue-strong" scope="col">RESULTADOS</th>
                                        <th class="td-blue-strong" colspan="2" scope="col">POS</th>
                                        <th class="td-blue-strong" colspan="2" scope="col">NEG</th>
                                        <th class="td-blue-strong" colspan="2" scope="col">IND</th>
                                        <th class="td-blue-strong" colspan="2" scope="col">INQ</th>
                                        <th class="td-blue-strong" style="vertical-align: middle;" rowspan="2" scope="col">TOTAIS</th>
                                        <th class="td-blue-strong" style="vertical-align: middle;" rowspan="2" scope="col">%</th>
                                    </tr>
                                     <tr style="text-align: center;">
                                        <th style="padding: 0 !important;" class="td-blue-strong crossOut" scope="col">
                                        <span style="font-size: 11px;position: relative;top: 0px;left: -25px;">IDADE</span>
                                        <span style="font-size: 11px;position: relative;left: -20px;">SEXO</span></th>
                                        <th class="td-blue-strong" scope="col">MASC</th>
                                        <th class="td-blue-strong" scope="col">FEM</th>
                                        <th class="td-blue-strong" scope="col">MASC</th>
                                        <th class="td-blue-strong" scope="col">FEM</th>
                                        <th class="td-blue-strong" scope="col">MASC</th>
                                        <th class="td-blue-strong" scope="col">FEM</th>
                                        <th class="td-blue-strong" scope="col">MASC</th>
                                        <th class="td-blue-strong" scope="col">FEM</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <tr>
                                        <td style="text-align: center;" class="td-blue-strong">0-20</td>
                                        <td style="text-align: right;" id="020pm"></td>
                                        <td style="text-align: right;" class="border-rt" id="020pf"></td>
                                        <td style="text-align: right;" id="020nm"></td>
                                        <td style="text-align: right;" class="border-rt" id="020nf"></td>
                                        <td style="text-align: right;" id="020im"></td>
                                        <td style="text-align: right;" class="border-rt" id="020if"></td>  
                                        <td style="text-align: right;" id="020iqm"></td>
                                        <td style="text-align: right;" class="border-rt" id="020iqf"></td>
                                        <td style="text-align: right;" class="border-rt" id="020tu"></td>
                                        <td style="text-align: right;" id="020tp"></td>
                                    </tr>
                                      <tr>
                                        <td style="text-align: center;" class="td-blue-strong">21-40</td>
                                        <td style="text-align: right;" id="2140pm"></td>
                                        <td style="text-align: right;" class="border-rt" id="2140pf"></td>
                                        <td style="text-align: right;" id="2140nm"></td>
                                        <td style="text-align: right;" class="border-rt" id="2140nf"></td>
                                        <td style="text-align: right;" id="2140im"></td>
                                        <td style="text-align: right;" class="border-rt" id="2140if"></td> 
                                        <td style="text-align: right;" id="2140iqm"></td>
                                        <td style="text-align: right;" class="border-rt" id="2140iqf"></td>
                                        <td style="text-align: right;" class="border-rt" id="2140tu"></td>
                                        <td style="text-align: right;" id="2140tp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">41-60</td>
                                        <td style="text-align: right;" id="4160pm"></td>
                                        <td style="text-align: right;" class="border-rt" id="4160pf"></td>
                                        <td style="text-align: right;" id="4160nm"></td>
                                        <td style="text-align: right;" class="border-rt" id="4160nf"></td>
                                        <td style="text-align: right;" id="4160im"></td>
                                        <td style="text-align: right;" class="border-rt" id="4160if"></td>  
                                        <td style="text-align: right;" id="4160iqm"></td>
                                        <td style="text-align: right;" class="border-rt" id="4160iqf"></td>
                                        <td style="text-align: right;" class="border-rt" id="4160tu"></td>
                                        <td style="text-align: right;" id="4160tp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">61-80</td>
                                        <td style="text-align: right;" id="6180pm"></td>
                                        <td style="text-align: right;" class="border-rt" id="6180pf"></td>
                                        <td style="text-align: right;" id="6180nm"></td>
                                        <td style="text-align: right;" class="border-rt" id="6180nf"></td>
                                        <td style="text-align: right;" id="6180im"></td>
                                        <td style="text-align: right;" class="border-rt" id="6180if"></td>  
                                        <td style="text-align: right;" id="6180iqm"></td>
                                        <td style="text-align: right;" class="border-rt" id="6180iqf"></td>
                                        <td style="text-align: right;" class="border-rt" id="6180tu"></td>
                                        <td style="text-align: right;" id="6180tp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">> 81</td>
                                        <td style="text-align: right;border-bottom: none;" id="81pm"></td>
                                        <td style="text-align: right;border-bottom: none;" class="border-rt" id="81pf"></td>
                                        <td style="text-align: right;border-bottom: none;" id="81nm"></td>
                                        <td style="text-align: right;border-bottom: none;" class="border-rt" id="81nf"></td>
                                        <td style="text-align: right;border-bottom: none;" id="81im"></td>
                                        <td style="text-align: right;border-bottom: none;" class="border-rt" id="81if"></td>  <td style="text-align: right;border-bottom: none;" id="81iqm"></td>
                                        <td style="text-align: right;border-bottom: none;" class="border-rt" id="81iqf"></td>
                                        <td style="text-align: right;border-bottom: none;" class="border-rt" id="81tu"></td>
                                        <td style="text-align: right;border-bottom: none;" id="81tp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">TOTAIS</td>
                                        <td style="text-align: right;" class="border-hz" id="totalupm"></td>
                                        <td style="text-align: right;" class="border-rt border-hz" id="totalupf"></td>
                                        <td style="text-align: right;" class="border-hz" id="totalunm"></td>
                                        <td style="text-align: right;" class="border-rt border-hz" id="totalunf"></td>
                                        <td style="text-align: right;" class="border-hz" id="totaluim"></td>
                                        <td style="text-align: right;" class="border-rt border-hz" id="totaluif"></td> 
                                        <td style="text-align: right;" class="border-hz" id="totaluiqm"></td>
                                        <td style="text-align: right;" class="border-rt border-hz" id="totaluiqf"></td>
                                        <td style="text-align: right;" class="border-rt border-hz" id="totalutu"></td>
                                        <td style="text-align: right;" class="border-hz" id="totalutp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">%</td>
                                        <td style="text-align: right;"  id="totalporpm" class="td-red"></td>
                                        <td style="text-align: right;" id="totalporpf" class="td-red"></td>
                                        <td style="text-align: right;" id="totalpornm" class="td-green"></td>
                                        <td style="text-align: right;" id="totalpornf" class="td-green"></td>
                                        <td style="text-align: right;" id="totalporim" class="td-yellow"></td>
                                        <td style="text-align: right;" id="totalporif" class="td-yellow"></td>   
                                        <td style="text-align: right;" id="totalporiqm" class="td-yellow">1</td>
                                        <td style="text-align: right;" id="totalporiqf" class="td-yellow"></td>
                                        <td  style="vertical-align: middle; text-align: right;" colspan="2" rowspan="2" id="totalportp"></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" class="td-blue-strong">% TOTAL</td>
                                        <td style="text-align: center;" id="totalmfporpos" style="text-align: center;" class="td-red" colspan="2"></td>
                                        <td style="text-align: center;" id="totalmfporneg" style="text-align: center;" class="td-green"colspan="2"></td>
                                        <td style="text-align: center;" id="totalmfporinc" style="text-align: center;" class="td-yellow" colspan="2"></td>   
                                        <td style="text-align: center;" id="totalmfporinq" style="text-align: center;" class="td-yellow" colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                </div>

            </div>
        </div>
    </div>

</div>
</div>
<!-- end row -->

<!-- end row -->

<!--
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-transparent p-3">
                <h5 class="header-title mb-0">Dados</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="media my-2">

                        <div class="media-body">
                            <p class="text-muted mb-2">Laboratorios</p>
                            <h5 class="mb-0">2</h5>
                        </div>
                        <div class="icons-lg ml-2 align-self-center">
                            <i class="uim uim-layer-group"></i>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="media my-2">
                        <div class="media-body">
                            <p class="text-muted mb-2">Técnicos </p>
                            <h5 class="mb-0">10</h5>
                        </div>
                        <div class="icons-lg ml-2 align-self-center">
                            <i class="uim uim-analytics"></i>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="media my-2">
                        <div class="media-body">
                            <p class="text-muted mb-2">Amostras</p>
                            <h5 class="mb-0">100</h5>
                        </div>
                        <div class="icons-lg ml-2 align-self-center">
                            <i class="uim uim-ruler"></i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-4">Dash</h5>
                <div id="radial-chart" class="apex-charts"></div>

                <div class="text-center mt-3">
                    <div class="row">
                        <div class="col-4">
                            <div>
                                <p class="text-muted"><i style="color: #f06543;" class="mdi mdi-circle mr-1"></i> Positivos</p>
                                <div style="display: flex; justify-content: center;">
                                     <h5>5</h5>
                                     <span style="padding-left: 5px;font-size: 10px;">(5%)</span>
                                </div>



                            </div>
                        </div>
                        <div class="col-4">
                            <div>
                                <p class="text-muted"><i style="color: #3ddc97;" class="mdi mdi-circle mr-1"></i> Negativos</p>
                                 <div style="display: flex; justify-content: center;">
                                     <h5>10</h5>
                                     <span style="padding-left: 5px;font-size: 10px;">(20%)</span>
                                </div>
                            </div>
                        </div>
                         <div class="col-4">
                            <div>
                                <p class="text-muted"><i style="color: #e4cc37;" class="mdi mdi-circle mr-1"></i> Em Análise</p>
                                 <div style="display: flex; justify-content: center;">
                                     <h5>24</h5>
                                     <span style="padding-left: 5px;font-size: 10px;">(40%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> -->
<!-- end row -->
