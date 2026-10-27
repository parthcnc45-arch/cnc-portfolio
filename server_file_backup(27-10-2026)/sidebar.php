  <!--**********************************
            Sidebar start
        ***********************************-->

        <style>
            .nk-sidebar .metismenu > li.submenu{position: relative;}
            .nk-sidebar .metismenu > li.submenu span.icon{position:absolute;top:15px;right: 15px;cursor: pointer;}
            @media (max-width:11999px) {
                .nk-sidebar .metismenu > li.submenu span.icon {
                    right: 10px;
                }
            }
            @media only screen and (min-width: 768px) {
                .nk-sidebar .metismenu > li.submenu ul li a span{
                    display: block;
                }
            }
            
        </style>
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label">Dashboard</li>
                    <li>
                        <a  href="index.php" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                   
                    </li>
                    <li class="mega-menu mega-menu-sm">
                        <a href="portfolios.php" aria-expanded="false">
                            <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Portfolio</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm">
                        <a href="clients.php" aria-expanded="false">
                            <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Clients</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm">
                        <a href="login-logs.php" aria-expanded="false">
                            <i class="icon-doc menu-icon"></i><span class="nav-text">Login Logs</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm submenu">
                        <a href="#" aria-expanded="false">
                            <i class="icon-folder-alt menu-icon"></i><span class="nav-text">Master</span>
                        </a>
                        <span class="icon"><i class="icon-arrow-right menu-icon"></i>
                        </span>
                        <ul>
                            <li class="mega-menu mega-menu-sm">
                                <a href="add-industries.php" aria-expanded="false">
                                    <span class="nav-text">Industries</span>
                                </a>
                            </li>
                            <li class="mega-menu mega-menu-sm">
                                <a href="add-framework.php" aria-expanded="false">
                                    <span class="nav-text">Framework</span>
                                </a>
                            </li> 
                            <li class="mega-menu mega-menu-sm">
                                <a href="add-programming_language.php" aria-expanded="false">
                                    <span class="nav-text">Programming Language</span>
                                </a>
                            </li>
                            <!-- <li class="mega-menu mega-menu-sm">
                                <a href="add-technology.php" aria-expanded="false">
                                    <span class="nav-text">Technology</span>
                                </a>
                            </li> -->
                            <li class="mega-menu mega-menu-sm">
                                <a href="add-portfolio_type.php" aria-expanded="false">
                                    <span class="nav-text">Application Type</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->