<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Biogenetics</h4>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
            <li class="breadcrumb-item active"></li>
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
                <div style="height: 250px;" class="card">
                    <div style="    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;" class="card-body"> 
                            <div class="row">
                                  <div class="col-xl-12">
                                <h4>Favor escolher uma das opções do menu</h4>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <img style="margin: 40px;" height="100" src="<?= $this->Url->build('/', true);?>img/image-home.png">
                                    <img style="margin: 40px;" height="100" src="<?= $this->Url->build('/', true);?>img/image-home-2.png">
                                    <img style="margin: 40px;" height="100" src="<?= $this->Url->build('/', true);?>img/image-home-3.png">
                                    <img style="margin: 40px;" height="100" src="<?= $this->Url->build('/', true);?>img/image-home-4.png">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>