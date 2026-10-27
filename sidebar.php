  <!--**********************************
            Sidebar start
        ***********************************-->

        <style>
            /* Fixed Nav Header with compact width */
            .nav-header {
                position: fixed !important;
                top: 0;
                left: 0;
                width: 185px !important;
                height: 85px !important;
                background: #ffffff !important;
                border-bottom: 1px solid #edf2f7 !important;
                border-right: 1px solid #edf2f7 !important;
                z-index: 1000 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0 12px !important;
                box-shadow: none !important;
            }
            .nav-header .brand-logo {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .nav-header .brand-logo a {
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                height: 100% !important;
            }
            .nav-header .brand-logo a .brand-title {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .nav-header .brand-logo a .brand-title img {
                max-height: 52px !important;
                max-width: 140px !important;
                width: auto !important;
                display: block !important;
                object-fit: contain !important;
            }

            /* Compact Fixed Sidebar */
            .nk-sidebar {
                position: fixed !important;
                top: 85px !important;
                bottom: 0;
                left: 0;
                width: 185px !important;
                height: calc(100vh - 85px) !important;
                z-index: 999;
                display: flex;
                flex-direction: column;
                background: #ffffff;
                border-right: 1px solid #edf2f7;
            }
            .nk-sidebar .nk-nav-scroll {
                flex: 1;
                overflow-y: auto;
            }
            .nk-sidebar .metismenu {
                padding-top: 14px !important;
                padding-bottom: 85px !important;
            }
            .nk-sidebar .metismenu > li {
                padding: 2px 10px !important;
            }
            .nk-sidebar .metismenu a {
                padding: 0.65rem 0.9rem !important;
                font-size: 13.5px !important;
                border-radius: 8px !important;
                color: #475569 !important;
                font-weight: 500 !important;
                transition: all 0.15s ease !important;
                display: flex !important;
                align-items: center !important;
            }
            .nk-sidebar .metismenu a:hover {
                background-color: #f8fafc !important;
                color: #4f46e5 !important;
            }
            .nk-sidebar .metismenu > li.active > a,
            .nk-sidebar .metismenu > li > a.active {
                background-color: #ede9fe !important;
                color: #4f46e5 !important;
                font-weight: 600 !important;
            }
            .nk-sidebar .metismenu > li.active > a i,
            .nk-sidebar .metismenu > li > a.active i {
                color: #4f46e5 !important;
            }
            .nk-sidebar .metismenu > li a > i {
                font-size: 0.95rem !important;
                padding-right: 0.5rem !important;
                color: #64748b;
            }
            .nk-sidebar .metismenu ul a {
                padding: 0.45rem 0.6rem 0.45rem 1.6rem !important;
                font-size: 12.5px !important;
            }
            .nk-sidebar .metismenu > li.submenu {
                position: relative;
            }
            .nk-sidebar .metismenu > li.submenu span.icon {
                position: absolute;
                top: 12px;
                right: 18px;
                cursor: pointer;
            }
            @media (max-width: 11999px) {
                .nk-sidebar .metismenu > li.submenu span.icon {
                    right: 14px;
                }
            }
            @media only screen and (min-width: 768px) {
                .nk-sidebar .metismenu > li.submenu ul li a span {
                    display: block;
                }
            }

            /* Align content-body with reduced sidebar width and remove breadcrumb */
            .content-body {
                margin-left: 185px !important;
                padding-top: 15px !important;
                background-color: #f8fafc !important;
            }
            .page-titles,
            .breadcrumb {
                display: none !important;
            }

            /* Bottom Logout Section */
            .sidebar-account-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #ffffff;
                border-top: 1px solid #edf2f7;
                padding: 14px 12px;
                z-index: 1001;
                box-sizing: border-box;
            }
            .sidebar-logout-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                height: 38px;
                background-color: #f8fafc;
                color: #475569 !important;
                border: 1.5px solid #e2e8f0;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 0.2px;
                text-decoration: none !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
                box-sizing: border-box;
                cursor: pointer;
            }
            .sidebar-logout-btn .logout-icon {
                width: 15px;
                height: 15px;
                color: #ef4444;
                transition: transform 0.2s ease, color 0.2s ease;
                flex-shrink: 0;
            }
            .sidebar-logout-btn:hover {
                background-color: #fef2f2;
                color: #dc2626 !important;
                border-color: #fca5a5;
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);
                transform: translateY(-1px);
            }
            .sidebar-logout-btn:hover .logout-icon {
                color: #dc2626;
                transform: translateX(2px);
            }
            .sidebar-logout-btn:active {
                transform: translateY(0);
                box-shadow: 0 1px 2px rgba(239, 68, 68, 0.08);
            }
        </style>
        <?php
            $active_script = basename($_SERVER['PHP_SELF']);
        ?>
        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <!-- <li class="<?php echo ($active_script == 'index.php') ? 'active' : ''; ?>">
                        <a href="index.php" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                    </li> -->
                    <li class="mega-menu mega-menu-sm <?php echo in_array($active_script, ['portfolios.php', 'add-portfolios.php', 'edit-portfolio.php', 'view-portfolio.php']) ? 'active' : ''; ?>">
                        <a href="portfolios.php" aria-expanded="false">
                            <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Portfolio</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm <?php echo ($active_script == 'clients.php') ? 'active' : ''; ?>">
                        <a href="clients.php" aria-expanded="false">
                            <i class="icon-people menu-icon"></i><span class="nav-text">Clients</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm <?php echo ($active_script == 'login-logs.php') ? 'active' : ''; ?>">
                        <a href="login-logs.php" aria-expanded="false">
                            <i class="icon-doc menu-icon"></i><span class="nav-text">Login Logs</span>
                        </a>
                    </li>
                    <li class="mega-menu mega-menu-sm submenu <?php echo in_array($active_script, ['add-industries.php', 'add-framework.php', 'add-programming_language.php', 'add-portfolio_type.php']) ? 'active' : ''; ?>">
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

            <!-- Bottom Account & Logout Section -->
            <div class="sidebar-account-footer">
                <a href="?logout=true" class="sidebar-logout-btn" title="Logout">
                    <svg class="logout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->