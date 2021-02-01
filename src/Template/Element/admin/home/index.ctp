<!-- Page-Title -->
<div class="page-title-box">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-10">
                <h4 class="page-title mb-1"><?= $title ?></h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"><?= $action ?></a></li>
                    <li class="breadcrumb-item active"></li>
                </ol>
            </div>
            <div style="text-align: right;top: 10px;" class="col-md-2">
                <?php if (isset($showActions) &&  !empty($showActions)) : ?>
                    <?= $this->Html->link(__('Novo'), ['action' => 'add',], ['escape' => false, 'class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <!-- <div style="    background: #fff;
    width: 100%;
    margin-top: 5px;
    max-width: 1232px;
    height: 15px;
    left: 24px;
    border-radius: 2px;
    position: absolute;" class="row-fake"></div> -->
</div>
<!-- end page title end breadcrumb -->
