<?php
/**
* CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
* @link          https://cakephp.org CakePHP(tm) Project
* @since         0.10.0
* @license       https://opensource.org/licenses/mit-license.php MIT License
*/

$cakeDescription = 'Biogenetics';
?>
<!DOCTYPE html>
<html>
<head>
<?= $this->Html->charset() ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?= $cakeDescription ?>:
    <?= $this->fetch('title') ?>
</title>
<?= $this->Html->meta('icon') ?>

 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- datepicker -->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/air-datepicker/css/datepicker.min.css" rel="stylesheet" type="text/css" />

    <!-- jvectormap -->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="<?= $this->Url->build('/', true);?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $this->Url->build('/', true);?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $this->Url->build('/', true);?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->build('/', true);?>css/main.css" rel="stylesheet" type="text/css" />

    <!-- Plugins css -->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />


  <!-- DataTables -->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />   

    <!-- Sweet Alert-->
    <link href="<?= $this->Url->build('/', true);?>assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />


 <!-- JAVASCRIPT -->
    <script src="<?= $this->Url->build('/', true);?>assets/libs/jquery/jquery.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/node-waves/waves.min.js"></script>

    <script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

    <!-- datepicker -->
    <script src="<?= $this->Url->build('/', true);?>assets/libs/air-datepicker/js/datepicker.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/air-datepicker/js/i18n/datepicker.en.js"></script>

    <!-- apexcharts -->
    <script src="<?= $this->Url->build('/', true);?>assets/libs/apexcharts/apexcharts.min.js"></script>

    <script src="<?= $this->Url->build('/', true);?>assets/libs/jquery-knob/jquery.knob.min.js"></script> 

    <!-- Jq vector map -->
    <script src="<?= $this->Url->build('/', true);?>assets/libs/jqvmap/jquery.vmap.min.js"></script>
    <script src="<?= $this->Url->build('/', true);?>assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script>
        var BASE_URL_ADMIN = '<?=$this->Url->build('/admin', true)?>/';
    </script>

</head>

<body data-topbar="colored" data-layout="horizontal" data-layout-size="boxed">

  <div id="layout-wrapper">
     <?=$this->element('header')?>
  </div>

<div class="main-content">

    <div class="page-content">
        <?= $this->fetch('content') ?>
        <?= $this->element('footer')?>
    </div>
</div>



  <!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

  
<!-- Required datatable js -->
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/jszip/jszip.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= $this->Url->build('/', true);?>assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="<?= $this->Url->build('/', true);?>assets/libs/sweetalert2/sweetalert2.min.js"></script>

<script src="<?= $this->Url->build('/', true);?>assets/js/pages/dashboard.init.js"></script>

<script src="<?= $this->Url->build('/', true);?>assets/js/app.js"></script>

</body>
</html>
