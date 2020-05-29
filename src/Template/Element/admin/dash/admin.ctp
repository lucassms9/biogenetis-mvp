<div class="row">

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div style="margin-bottom: 15px" class="row">
                    <div class="col-md-4">
                    <select class="form-control">
                        <option value="">Escolha</option>
                        <option value="SP">SP</option>
                        <option value="MG">MG</option>
                        <option value="RJ">RJ</option>
                    </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="filter-table" class="btn btn-primary mt-3 mt-sm-0">Buscar</button>
                    </div>
                </div>
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



<div class="row">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Pizza</h4>
                
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Coluna UF</h4>
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
                  <h4 class="header-title mb-4">Coluna Faixa Etária</h4>
                 <div id="column_chart_idade" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

   <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                  <h4 class="header-title mb-4">Coluna Sexo</h4>
                 <div id="column_chart_sexo" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

</div>
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