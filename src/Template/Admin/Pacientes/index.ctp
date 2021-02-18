<?php echo $this->element('admin/home/index'); ?>


<div class="page-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?= $this->Flash->render() ?>
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('nome') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('cpf') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('rg') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('celular') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('telefone') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('sexo') ?></th>
                                        <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($pacientes as $paciente) : ?>
                                        <?php
                                        $pacienteData = $pacientesData->getByHash($paciente->hash);

                                        $pacienteData = json_decode($pacienteData);

                                        $paciente_result = $pacienteData;

                                        ?>
                                        <tr>
                                            <td><?= $this->Number->format($paciente->id) ?></td>
                                            <td><?= h($paciente_result->nome) ?></td>
                                            <td><?= h($paciente_result->cpf) ?></td>
                                            <td><?= h($paciente_result->rg) ?></td>
                                            <td><?= h($paciente_result->email) ?></td>
                                            <td><?= h($paciente_result->celular) ?></td>
                                            <td><?= h($paciente_result->telefone) ?></td>
                                            <td><?= h($paciente_result->sexo) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $paciente->id], ['escape' => false]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>


                        <div class="mt-4">
                            <div class="paginator">
                                <ul class="pagination pagination pagination-rounded justify-content-center mb-0">
                                    <?= $this->Paginator->prev('<i class="mdi mdi-chevron-left"></i>', ['escape' => false, '']) ?>

                                    <?= $this->Paginator->numbers() ?>
                                    <?= $this->Paginator->next('<i class="mdi mdi-chevron-right"></i>', ['escape' => false, '']) ?>
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
