<?php
    $perfil_base = [3,4,5];
?>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="container-fluid">
            <div class="float-right">

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- <img class="rounded-circle header-profile-user" src="https://lh3.googleusercontent.com/proxy/G_h5u0Gl2wVt2vyO6cWg4nNQRFiAa-CRsO5IPmbhbzXz-oxkfZAslrxdXYck-kC3jpuO1FatRU7BHbi6VDTTh3mWRDPlHPxZDwSxEC69FRXl-k2uPrRu7JI" alt="Header Avatar"> -->
                        <span class="fontboldonly d-none d-sm-inline-block ml-1"><?= $_SESSION['Auth']['User']['nome_completo'] ?></span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-lock font-size-16 align-middle mr-1"></i> Lock screen</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= $this->Url->build('/admin', true); ?>/users/logout"><i class="mdi mdi-logout font-size-16 align-middle mr-1"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- LOGO -->
            <div class="navbar-brand-box">

                <a href="<?= $this->Url->build('/admin', true); ?>" class="logo logo-light">
                    <!-- <span class="logo-sm"> -->
                    <!-- <img src="assets/images/logo-sm-light.png" alt="" height="22"> -->
                    <!-- </span> -->
                    <span class="logo-lg">
                        <img src="<?= $this->Url->build('/', true); ?>img/biogenetics-logo.svg" alt="" height="55">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm mr-2 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="topnav">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                        <?php if ($_SESSION['Auth']['User']['user_type_id'] === 1) : ?>
                            <li class="nav-item">
                                <a class="nav-link fontbold" href="<?= $this->Url->build('/admin', true); ?>/amostras/import">
                                    Amostras
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if ($_SESSION['Auth']['User']['user_type_id'] !== 5) : ?>

                            <li class="nav-item dropdown">
                                <a style="color: #004ba7 !important;" class="fontbold nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Relatórios <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                    <a href="<?= $this->Url->build('/admin', true); ?>/exames/relatorio" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div>Amostras
                                    </a>

                                    <a href="<?= $this->Url->build('/admin', true); ?>/amostras/resultados" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div>Resultados Gerais
                                    </a>

                                    <?php if ($_SESSION['Auth']['User']['user_type_id'] === 1) : ?>

                                        <a href="<?= $this->Url->build('/admin', true); ?>/amostras/encadeamentos" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div>Encadeamentos
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </li>
                            <?php endif; ?>


                            <li class="nav-item dropdown">
                                <a style="color: #004ba7 !important;" class="fontbold nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Dashboard <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                    <?php if (!in_array($_SESSION['Auth']['User']['user_type_id'],$perfil_base)): ?>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/dashboard" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div>Dashboard
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= $this->Url->build('/admin', true); ?>/dashboard/operacao" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div>Dashboard Operação
                                    </a>
                                </div>
                            </li>


                            <?php if (!in_array($_SESSION['Auth']['User']['user_type_id'],$perfil_base)) : ?>
                                <li class="nav-item dropdown">
                                    <a style="color: #004ba7 !important;" class="fontbold nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Mais Opções <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-components">
                                        <?php if ($_SESSION['Auth']['User']['user_type_id'] === 1 || $_SESSION['Auth']['User']['user_type_id'] === 2) : ?>

                                            <a href="<?= $this->Url->build('/admin', true); ?>/clientes/" class="dropdown-item">
                                                <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Clientes
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/users/" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Usuários
                                        </a>

                                        <!-- <a href="<?= $this->Url->build('/admin', true); ?>/croquis/gerador" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Criar Croqui
                                        </a> -->

                                        <?php if ($_SESSION['Auth']['User']['user_type_id'] === 1) : ?>
                                            <a href="<?= $this->Url->build('/admin', true); ?>/origens" class="dropdown-item">
                                                <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Endpoints
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/pedidos" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Pedidos
                                        </a>

                                        <a href="<?= $this->Url->build('/admin', true); ?>/croquis" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Gestão de Croquis
                                        </a>

                                        <?php if (!in_array($_SESSION['Auth']['User']['user_type_id'],$perfil_base)) : ?>
                                            <a href="<?= $this->Url->build('/admin', true); ?>/entradaexames" class="dropdown-item">
                                                <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Gestão de Exames
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/equipamentos" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Gestão de Equipamentos
                                        </a>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/pacientes" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Gestão de Pacientes
                                        </a>
                                        <a href="<?= $this->Url->build('/admin', true); ?>/produtos" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Gestão de Produtos
                                        </a>
                                    </div>
                                </li>

                                <!-- <li class="nav-item dropdown">
                                    <a style="color: #004ba7 !important;" class="fontbold nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Usuários <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-components">
                                        <a href="<?= $this->Url->build('/admin', true); ?>/users/add" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-plus"></i></div> Novo
                                        </a>

                                        <a href="<?= $this->Url->build('/admin', true); ?>/users" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Ver Todos
                                        </a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a style="color: #004ba7 !important;" class="fontbold nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Endpoints <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-components">
                                        <a href="<?= $this->Url->build('/admin', true); ?>/origens/add" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-plus"></i></div> Novo
                                        </a>

                                        <a href="<?= $this->Url->build('/admin', true); ?>/origens" class="dropdown-item">
                                            <div class="d-inline-block icons-sm mr-2"><i class="mdi mdi-format-list-bulleted-square"></i></div> Ver Todos
                                        </a>
                                    </div>
                                </li> -->

                            <?php endif; ?>


                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>


</header>
