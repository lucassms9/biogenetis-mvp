<div class="container" style="display: flex;justify-content: center;">
<div class="users form large-4 medium-4 columns content">
    <?= $this->Form->create($user, ['class' => ['sign-box']]) ?>
        
        <div style="text-align: center; padding: 20px 0; padding-bottom: 40px">
            <?php echo $this->Html->image('../img/system/logo.png'); ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('email', ['label' => 'E-mail']) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('password', ['label' => 'Senha','required' => true]) ?>
        </div>
        
        <?= $this->Flash->render() ?>
        
        <button type="submit" class="btn primary btn-rounded"><?= __('Entrar') ?></button>
    
    </form>

    </div>
</div>