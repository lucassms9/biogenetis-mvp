<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra[]|\Cake\Collection\CollectionInterface $amostras
 */
?>

<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Exames</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Exames</a></li>
            <li class="breadcrumb-item active">Ver Todos</li>
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
            <div class="col-xl-10">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body"> 
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('code_amostra') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('uf') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('idade') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('Resultado') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($exames as $exame): ?>
                                        <td><?= $this->Number->format($exame->id) ?></td>
                                        <td><?= h($exame->paciente->nome) ?></td>
                                        <td><?= h($exame->amostra->code_amostra) ?></td>
                                        <td><?= h($exame->amostra->uf) ?></td>
                                        <td><?= $this->Number->format($exame->amostra->idade) ?></td>
                                        <td><?= h($exame->resultado) ?></td>

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
