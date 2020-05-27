

<!-- Page-Title -->
<div class="page-title-box">
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="page-title mb-1">Dashboard</h4>
            <ol class="breadcrumb m-0">
            <!-- <li class="breadcrumb-item active">Welcome to Xoric Dashboard</li> -->
            </ol>
        </div>
        <div class="col-md-4">
            <div class="float-right d-none d-md-block">
               <!--  <div class="dropdown">
                    <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-settings-outline mr-1"></i> Settings
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

</div>
</div>
<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
	<div class="container-fluid">
<?php 
	if($user->user_type_id == 1){
		echo $this->element('admin/dash/admin');
	}else if($user->user_type_id == 2){
		echo $this->element('admin/dash/manage');
	}else{
		echo $this->element('admin/dash/tecnico');
	}
?>
	</div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/dashboard/index.js"></script>

