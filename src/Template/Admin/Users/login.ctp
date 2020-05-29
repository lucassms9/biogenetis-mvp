
<div class="row justify-content-center">
<div class="col-xl-5 col-sm-8">
    <div class="card">
        <div class="card-body p-4">
            <div class="p-2">
                <h5 class="mb-5 text-center">Fazer Login</h5>
                    <?= $this->Form->create($user, ['class' => ['form-horizontal']]) ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <?= $this->Form->control('email', ['label' => 'E-mail', 'class' => 'form-control']) ?>
                            </div>

                            <div class="form-group mb-4">
                                <?= $this->Form->control('password', ['label' => 'Senha','required' => true, 'class' => 'form-control']) ?>
                            </div>
                            <?= $this->Flash->render() ?>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-block waves-effect waves-light"><?= __('Entrar') ?></button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end row -->
