
<div class="row justify-content-center">
<div class="col-xl-12 col-sm-12">
    <div class="card card-auth">
        <div class="card-body p-4">
            <div class="p-2">
                <h3 class="text-center">Área Restrita Covid Express</h3>
                <p class="text-muted text-center">Informe seu e-mail e sua senha</p>
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
                                <button type="submit" class="btn btn-primary btn-auth btn-block waves-effect waves-light"><?= __('Logar') ?></button>
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
