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

                                        $paciente = $pacienteData;

                                        ?>
                                        <tr>
                                            <td><?= $this->Number->format($paciente->id) ?></td>
                                            <td><?= h($paciente->nome) ?></td>
                                            <td><?= h($paciente->cpf) ?></td>
                                            <td><?= h($paciente->rg) ?></td>
                                            <td><?= h($paciente->email) ?></td>
                                            <td><?= h($paciente->celular) ?></td>
                                            <td><?= h($paciente->telefone) ?></td>
                                            <td><?= h($paciente->sexo) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $paciente->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $paciente->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $paciente->id)]) ?>
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
