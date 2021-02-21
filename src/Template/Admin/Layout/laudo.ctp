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
<html lang="pt-BR">

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

    <!-- Bootstrap Css -->
    <link href="<?= $this->Url->build('/', true); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $this->Url->build('/', true); ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $this->Url->build('/', true); ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $this->Url->build('/', true); ?>css/main.css" rel="stylesheet" type="text/css" />

    <!-- JAVASCRIPT -->
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/jquery/jquery.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<style>
    @page { margin: 5; }

</style>

</head>

<body data-topbar="colored" data-layout="horizontal" data-layout-size="boxed">

    <div id="layout-wrapper">

    </div>

    <div class="main-content">

        <div style="padding:0 15px" class="page-content">
            <?= $this->fetch('content') ?>
            <!-- <?= $this->element('footer') ?> -->
        </div>
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Required datatable js -->
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/jszip/jszip.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= $this->Url->build('/', true); ?>assets/libs/alertifyjs/build/alertify.min.js"></script>


    <script src="<?= $this->Url->build('/', true); ?>assets/js/pages/dashboard.init.js"></script>

    <script src="<?= $this->Url->build('/', true); ?>assets/js/app.js"></script>

</body>

</html>
