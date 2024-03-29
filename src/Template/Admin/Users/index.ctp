<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

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
                                        <th scope="col"><?= $this->Paginator->sort('nome_completo') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                                        <!-- <th scope="col"><?= $this->Paginator->sort('senha') ?></th> -->
                                        <th scope="col"><?= $this->Paginator->sort('user_type_id','Perfil') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('cliente_id') ?></th>
                                        <th scope="col"><?= $this->Paginator->sort('created', 'Criado em') ?></th>
                                        <!-- <th scope="col"><?= $this->Paginator->sort('modified') ?></th> -->
                                        <th scope="col" class="actions"><?= __('Ações') ?></th>
                                    </tr>
                                </thead>
                                 <tbody>
                                     <?php foreach ($users as $user): ?>
                                        <tr>
                                           <td><?= $this->Number->format($user->id) ?></td>
                                            <td><?= h($user->nome_completo) ?></td>
                                            <td><?= h($user->email) ?></td>
                                            <!-- <td><?= h($user->senha) ?></td> -->
                                            <td><?= $user->has('user_type') ? $this->Html->link($user->user_type->nome, ['controller' => 'UserTypes', 'action' => 'view', $user->user_type->id]) : '' ?></td>
                                            <td><?= $user->has('cliente') ? $this->Html->link($user->cliente->nome, ['controller' => 'Clientes', 'action' => 'view', $user->cliente->id]) : '' ?></td>
                                            <td><?= h($user->created) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('<i class="mdi mdi-pencil"></i>'), ['action' => 'edit', $user->id], ['escape' => false]) ?>
                                                <?= $this->Form->postLink(__('<i class="mdi mdi-trash-can"></i>'), ['action' => 'delete', $user->id], ['escape' => false, 'confirm' => __('Deseja deletar?', $user->id)]) ?>

                                            </td>
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
