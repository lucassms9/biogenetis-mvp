<header id="page-topbar">
    <div class="navbar-header">
        <div class="container-fluid">
            <div class="float-right">


                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="https://api.adorable.io/avatars/50/abott@adorable.png" alt="Header Avatar">
                        <span class="d-none d-sm-inline-block ml-1"><?= $_SESSION['Auth']['User']['nome_completo']?></span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- item-->
                        <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-lock font-size-16 align-middle mr-1"></i> Lock screen</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= $this->Url->build('/admin', true);?>/users/logout"><i class="mdi mdi-logout font-size-16 align-middle mr-1"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- LOGO -->
            <div style="padding: 24px 20px 0;" class="navbar-brand-box">
             
                <a href="<?= $this->Url->build('/admin', true);?>" class="logo logo-light">
                    <span class="logo-sm">
                        <!-- <img src="assets/images/logo-sm-light.png" alt="" height="22"> -->
                    </span>
                    <span class="logo-lg">
                        <h5 style="color: #fff; font-weight: bold; display: flex;justify-content: center;">Logo</h5>
                        <!-- <img src="assets/images/logo-light.png" alt="" height="20"> -->
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
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $this->Url->build('/admin', true);?>">
                                    Dashboard
                                </a>
                            </li>

                              <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Amostras <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                  
                                     <a href="<?= $this->Url->build('/admin', true);?>/amostras/add" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Nova</a>

                                        <a href="<?= $this->Url->build('/admin', true);?>/amostras" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Ver Todas</a>

                                </div>

                            </li>

                              <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Exames <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">

                                        <a href="<?= $this->Url->build('/admin', true);?>/exames/import" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Importar</a>

                                        <a href="<?= $this->Url->build('/admin', true);?>/exames" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Ver Todos</a>
                                    </div>
                            </li>
                            
                             <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Users <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                    <a href="<?= $this->Url->build('/admin', true);?>/users/add" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Novo</a>

                                        <a href="<?= $this->Url->build('/admin', true);?>/users" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Ver Todos</a>
                                    </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Relatório <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-components">
                                    <a href="<?= $this->Url->build('/admin', true);?>/exames/relatorio" class="dropdown-item">
                                        <div class="d-inline-block icons-sm mr-2"><i class="uim uim-schedule"></i></div> Exames</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    
</header>