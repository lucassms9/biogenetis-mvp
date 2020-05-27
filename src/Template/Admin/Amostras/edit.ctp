<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Amostra $amostra
 */
?>
<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Amostras</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Amostras</a></li>
            <li class="breadcrumb-item active">Editar</li>
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
                         <?= $this->Form->create($amostra) ?>

                        <?php
                            echo $this->Form->control('code_amostra',['class' => 'form-control']);
                            echo $this->Form->control('cep',['class' => 'form-control']);
                            echo $this->Form->control('idade',['class' => 'form-control']);
                            echo $this->Form->control('sexo',['class' => 'form-control']);
                        ?>
                        <div style="margin-top: 10px" class="row">
                            <div class="col-xl-10">
                            <?= $this->Form->button(__('Submit'),['class' => 'btn btn-primary btn-rounded waves-effect waves-light']) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>