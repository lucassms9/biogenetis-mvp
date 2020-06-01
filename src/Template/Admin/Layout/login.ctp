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
        <link href="<?= $this->Url->build('/', true);?>css/main.css" rel="stylesheet" type="text/css" />

</head>
<body class="bg-primary bg-pattern">
    
     <div style=" overflow: hidden;" class="account-pages my-5">
        <div class="container">          
             <div class="row">
                    <div class="col-sm-6">
                        <div class="text-center">
                            <a class="logo"><img src="<?= $this->Url->build('/', true);?>img/biogenetics-logo.png" height="90" alt="logo"></a>
                        </div>
                        <p style="    font-size: 28px;
    color: #004ba7;
    font-family: Open Sans,sans-serif;
    text-align: center;" class="cobalt f16 mb12 mb16-m style-module--subtitle--3nUDw">A vida precisa de respostas</p>
                         <?= $this->fetch('content') ?>
                    </div>
                    <div class="col-sm-6">
                        <img style="    width: 80%;
    left: 23%;
    top: 19%;
    z-index: -1;
    position: absolute;" src="<?= $this->Url->build('/', true);?>img/image-home.png" alt="logo">
                        <div class="img-svg">
                            <svg style="    position: absolute;
    left: -8%;
    top: -10%;
    z-index: -1;" width="4624" height="615" viewBox="0 0 4624 615"><path d="M4122.492 1.262c33.533-3.807 65.684 6.85 90.48 28.69l.807.717.403-.163a297.885 297.885 0 01109.853-21.512l1.378-.003c93.403 0 179.76 42.611 237.51 117.66a294.105 294.105 0 0159.137 149.019c11.26 110.257-36.794 214.568-128.681 278.364a287.342 287.342 0 01-117.1 47.336c-74.51 12.245-148.37-2.627-210.058-41.693l-3.369-2.148c-81.497-52.143-183.36-65.025-277.921-36.17l-2.863.887-40.976 12.896c-259.354 81.604-539.3 67.305-788.87-40.119l-7.553-3.284-210.91-92.605a1205.907 1205.907 0 00-781.612-64.644l-7.886 2.031-54.748 14.294a1726.216 1726.216 0 01-915.19-11.76l-9.038-2.636-524.406-154.56c-60.004-19.061-120.036-46.106-187.82-83.159-91.838-50.225-207.193-34.578-282.224 40.478-89.78 89.717-89.78 246.406 0 336.198 64.357 64.357 159.038 85.043 244.327 56.19l1.209-.417-.144-.465c-5.333-17.344-6.963-33.128-4.258-49.291l.149-.867c3.547-20.061 13.844-40.183 32.144-61.134 22.12-25.326 52.741-40.38 84.23-40.369 5.904.003 11.799.45 17.637 1.336 64.339 9.771 108.576 69.85 98.806 134.18-7.153 48.988-45.89 89.072-94.661 98.015-37.083 6.792-73.062-4.012-100.196-28.38l-.82-.743 2.025-2.214c26.547 24.27 61.934 35.074 98.45 28.387 47.508-8.712 85.266-47.784 92.235-95.506 9.522-62.701-33.589-121.25-96.29-130.773a114.89 114.89 0 00-17.187-1.302c-30.587-.011-60.398 14.645-81.97 39.343-17.956 20.557-28.002 40.189-31.449 59.683-2.87 16.23-1.22 32.067 4.303 49.657l.294.927.445 1.39-1.377.483c-87.028 30.534-184.189 9.826-250.023-56.008-90.952-90.964-90.952-249.553 0-340.442C144.693 61.052 261.5 45.208 354.499 96.068c66.95 36.598 126.291 63.396 185.465 82.351l1.794.572 524.376 154.55a1723.216 1723.216 0 00913.522 16.721l9.099-2.35 54.748-14.293a1208.908 1208.908 0 01783.976 59.512l7.486 3.257 210.91 92.605c248.292 109.015 527.394 125.18 786.472 45.724l7.844-2.437 40.976-12.896c95.238-29.958 198.143-17.787 280.8 34.034l2.5 1.583 1.505.959c61.443 39.554 135.29 54.698 209.82 42.45a284.341 284.341 0 00115.877-46.841c90.99-63.173 138.555-166.424 127.406-275.595a291.102 291.102 0 00-58.532-147.496c-57.18-74.31-142.659-116.487-235.13-116.487-37.727-.01-75.102 7.22-110.096 21.292l-2.156.875-.7-.637c-24.366-22.179-56.29-33.063-89.63-29.279-52.502 5.961-95.043 48.71-100.553 101.143-6.808 62.743 38.535 119.124 101.278 125.933 4.089.444 8.2.666 12.316.666 62.458-.003 113.208-50.128 114.22-112.346l.016-1.89 3 .001c-.005 64.746-52.49 117.232-117.235 117.235-4.226 0-8.445-.228-12.64-.684-64.39-6.987-110.924-64.849-103.938-129.234 5.657-53.829 49.3-97.685 103.197-103.804z" fill="#0099F0" fill-rule="nonzero" fill-opacity="0.22"></path></svg>
                        </div>
                    </div>
                </div>
                <!-- end row -->
           

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
