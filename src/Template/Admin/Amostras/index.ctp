<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Amostras</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Amostras</a></li>
            <li class="breadcrumb-item active">Ver Todas</li>
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
                        
                        <div class="row">
                            <div class="col-xl-12">
                               <form method="get">
                                    <div style="margin-bottom: 30px;" class="row">
                                        <div class="col-md-4">
                                            <label for="amostra_id"> Amostra ID</label>
                                           <input name="amostra_id" value="<?= @$this->request->query['amostra_id']?>" class="form-control">
                                        </div>
                                         <div style="margin-top: 30px;" class="col-md-4">
                                           <button class="btn btn-primary btn-rounded waves-effect waves-light" type="submit">Buscar</button>
                                        </div>
                                    </div>

                               </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('code_amostra','Amostra ID') ?></th>
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
                            <div class="paginator">
                                <ul class="pagination pagination pagination-rounded justify-content-center mb-0">
                                    <?= $this->Paginator->prev('<i class="mdi mdi-chevron-left"></i>',['escape' => false,'']) ?>

                                    <?= $this->Paginator->numbers() ?>
                                    <?= $this->Paginator->next('<i class="mdi mdi-chevron-right"></i>',['escape' => false,'']) ?>
                                </ul>
                                <p><?= $this->Paginator->counter(['format' => __('Pagina {{page}} of {{pages}}, Listando {{current}} registro(s) de {{count}} total')]) ?></p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            
        </div>
        <!-- end row -->
    </div>
</div>
