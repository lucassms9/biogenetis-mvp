
<div class="row">
    <div class="col-xl-1">
    </div>
    <div class="col-xl-10">
    
        <div class="card">
            <div class="card-body">
                 <form method="get"> 
                <div class="row">
                   
                 <div class="col-md-3">
                    <label>Estado</label>
                     <select name="estados_filter" id="estados-filter" class="form-control">
                       <option value="">Escolha</option>
                        <option <?= (@$this->request->query['estados_filter']) == 'AC' ? 'selected' : '' ?> value="AC">Acre</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'AL' ? 'selected' : '' ?> value="AL">Alagoas</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'AM' ? 'selected' : '' ?> value="AM">Amapá</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'AP' ? 'selected' : '' ?> value="AP">Amazonas</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'BA' ? 'selected' : '' ?> value="BA">Bahia</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'CE' ? 'selected' : '' ?> value="CE">Ceará</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'DF' ? 'selected' : '' ?> value="DF">Distrito Federal</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'ES' ? 'selected' : '' ?> value="ES">Espírito Santo</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'GO' ? 'selected' : '' ?> value="GO">Goiás</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'MA' ? 'selected' : '' ?> value="MA">Maranhão</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'MG' ? 'selected' : '' ?> value="MG">Minas Gerais</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'AC' ? 'selected' : '' ?> value="MS">Mato Grosso do Sul</option>
                         <option  value="MT">Mato Grosso</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'PA' ? 'selected' : '' ?> value="PA">Pará</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'PB' ? 'selected' : '' ?> value="PB">Paraíba</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'PE' ? 'selected' : '' ?> value="PE">Pernambuco</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'PI' ? 'selected' : '' ?> value="PI">Piauí</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'PR' ? 'selected' : '' ?> value="PR">Paraná</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'RJ' ? 'selected' : '' ?> value="RJ">Rio de Janeiro</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'RN' ? 'selected' : '' ?> value="RN">Rio Grande do Norte</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'RO' ? 'selected' : '' ?> value="RO">Rondônia</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'RR' ? 'selected' : '' ?> value="RR">Roraima</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'RS' ? 'selected' : '' ?> value="RS">Rio Grande do Sul</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'SC' ? 'selected' : '' ?> value="SC">Santa Catarina</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'SE' ? 'selected' : '' ?> value="SE">Sergipe</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'SP' ? 'selected' : '' ?> value="SP">São Paulo</option>
                         <option <?= (@$this->request->query['estados_filter']) == 'TO' ? 'selected' : '' ?> value="TO">Tocantins</option>
                    </select>
                </div>
                    <div class="col-md-2">
                        <label>Data de</label>
                        <input name="date_init_filter" value="<?=@$this->request->query['date_init_filter']?>" class="form-control datepicker-here" data-language="pt-BR" id="date-init-filter" type="text" />
                    </div>
                    
                    <div class="col-md-2">
                        <label>Data até</label>
                        <input name="date_end_filter" data-language="pt-BR" value="<?=@$this->request->query['date_end_filter']?>" class="form-control datepicker-here" id="date-end-filter" type="text" />
                    </div>

                    <div style="margin-top: 28px;" class="col-md-1">
                        <button type="submit" class="btn btn-primary  mt-3 mt-sm-0">Filtrar</button>
                    </div>
                     <div style="margin-top: 28px;" class="col-md-3">
                        <button type="button" id="reset-filter" class="btn btn-secondary  mt-3 mt-sm-0">Limpar Filtros</button>
                    </div>
                  
                </div>
                 </form>
            </div>
        </div>
       
        </div>
        <div class="col-xl-1">
        </div>
    </div>
</div>

</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Resumo</h4>
                
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
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
   
  <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Por Faixa Etária</h4>
                 <div id="column_chart_idade" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

   <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Por Sexo</h4>
                 <div id="column_chart_sexo" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                 <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th style="text-align: center;" colspan="9" scope="col">TOTAL</th>
                                    </tr>

                                    <tr>
                                        <th scope="col"></th>
                                        <th colspan="2" scope="col">POS</th>
                                        <th colspan="2" scope="col">NEG</th>
                                        <th colspan="2" scope="col">INC</th>
                                        <th style="vertical-align: middle;" rowspan="2" scope="col">TOTAIS</th>
                                        <th style="vertical-align: middle;" rowspan="2" scope="col">%</th>
                                    </tr>
                                     <tr>
                                        <th scope="col"></th>
                                        <th scope="col">MASC</th>
                                        <th scope="col">FEM</th>
                                        <th scope="col">MASC</th>
                                        <th scope="col">FEM</th>
                                        <th scope="col">MASC</th>
                                        <th scope="col">FEM</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <tr>
                                        <td>0-20</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>3</td>
                                        <td>3%</td>
                                    </tr>
                                      <tr>
                                        <td>21-40</td>
                                         <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>3</td>
                                        <td>3%</td>
                                    </tr> 
                                    <tr>
                                        <td>41-60</td>
                                         <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>10%</td>
                                    </tr>
                                    <tr>
                                        <td>61-80</td>
                                         <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>3</td>
                                        <td>3%</td>
                                    </tr> 
                                    <tr>
                                        <td>> 81</td>
                                         <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>3</td>
                                        <td>3%</td>
                                    </tr>
                                    <tr>
                                        <td>TOTAIS</td>
                                         <td>0</td>
                                        <td>0</td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>3</td>
                                        <td>100%</td>
                                    </tr> 
                                    <tr>
                                        <td>%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>100%</td>
                                    </tr> 
                                    <tr>
                                        <td>% TOTAL</td>
                                        <td style="text-align: center;" colspan="2">10%</td>
                                        <td style="text-align: center;" colspan="2">10%</td>
                                        <td style="text-align: center;" colspan="2">10%</td>
                                    </tr>
                                </tbody>
                            </table>
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