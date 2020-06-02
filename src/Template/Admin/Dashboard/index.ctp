
<?php echo $this->element('admin/home/index');?>

<!-- end page title end breadcrumb -->

<div class="page-content-wrapper">
	<div class="container-fluid">
<?php 

    echo $this->element('admin/dash/admin');


?>
	</div>
</div>

<script type="text/javascript" src="<?= $this->Url->build('/', true) ?>js/dashboard/index.js"></script>

