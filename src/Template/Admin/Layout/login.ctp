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

$cakeDescription = 'Login - Biogenetics';
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->
        <link href="<?= $this->Url->build('/', true);?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?= $this->Url->build('/', true);?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= $this->Url->build('/', true);?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

</head>
<body class="bg-primary bg-pattern">
    
     <div class="account-pages my-5">
        <div class="container">          
             <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-5">
                            <a href="index.html" class="logo"><img src="<?= $this->Url->build('/', true);?>assets/images/logo-light.png" height="24" alt="logo"></a>
                            <h5 class="font-size-16 text-white-50 mb-4">Responsive Bootstrap 4 Admin Dashboard</h5>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            <?= $this->fetch('content') ?>
        </div>
    </div>

    <footer></footer>

    <!-- JAVASCRIPT -->
        <script src="<?= $this->Url->build('/', true);?>assets/libs/jquery/jquery.min.js"></script>
        <script src="<?= $this->Url->build('/', true);?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= $this->Url->build('/', true);?>assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="<?= $this->Url->build('/', true);?>assets/libs/simplebar/simplebar.min.js"></script>
        <script src="<?= $this->Url->build('/', true);?>assets/libs/node-waves/waves.min.js"></script>

        <script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

        <script src="<?= $this->Url->build('/', true);?>assets/js/app.js"></script>

</body>
</html>
