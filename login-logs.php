<?php
include_once('header.php');
include_once('sidebar.php');
require_once('country_flags_helper.php');
?>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.7.0/dist/multiple-select.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<style>
    /* =========================================================
       MODERN PAGE HEADER & LAYOUT (Matching portfolios.php)
       ========================================================= */
    .content-body {
        background-color: #f7f8fb !important;
        min-height: 100vh;
        padding-bottom: 40px;
    }
    .portfolio-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-top: 4px;
        gap: 15px;
    }
    .portfolio-header-left {
        flex: 1 1 auto;
        min-width: 0;
    }
    .portfolio-header-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }
    .portfolio-page-title {
        font-size: 21px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: -0.3px;
        font-family: inherit;
    }
    .portfolio-count-badge {
        font-size: 19px;
        font-weight: 600;
        color: #1e293b;
    }
    .portfolio-page-desc {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    /* =========================================================
       MODERN TABLE CARD & STYLES (Matching portfolios.php)
       ========================================================= */
    .portfolio-table-card {
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .portfolio-table-responsive {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
        -webkit-overflow-scrolling: touch;
    }

    #portfolio-table_wrapper,
    .portfolio-table-card .dataTables_wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar,
    .portfolio-table-responsive::-webkit-scrollbar {
        height: 7px !important;
        width: 7px !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar-track,
    .portfolio-table-responsive::-webkit-scrollbar-track {
        background: #f8fafc !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar-thumb,
    .portfolio-table-responsive::-webkit-scrollbar-thumb {
        background: #cbd5e1 !important;
        border-radius: 4px !important;
    }
    .dataTables_scrollBody::-webkit-scrollbar-thumb:hover,
    .portfolio-table-responsive::-webkit-scrollbar-thumb:hover {
        background: #94a3b8 !important;
    }
    #portfolio-table_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }

    /* Top Table Controls Toolbar */
    #portfolio-table_wrapper .dataTables_length {
        float: left;
        margin: 12px 16px 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        height: 32px !important;
    }
    #portfolio-table_wrapper .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        gap: 5px !important;
    }
    #portfolio-table_wrapper .dataTables_length select {
        height: 32px !important;
        min-width: 65px !important;
        padding: 2px 24px 2px 10px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        outline: none !important;
        cursor: pointer !important;
        margin: 0 4px !important;
        transition: all 0.2s ease !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 8px center !important;
        background-size: 10px 10px !important;
    }
    #portfolio-table_wrapper .dataTables_length select:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Right controls in header toolbar: Search & Status */
    #portfolio-table_wrapper .dataTables_filter {
        float: right;
        margin: 12px 16px 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 10px !important;
        height: 32px !important;
    }
    #portfolio-table_wrapper .dataTables_filter label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        gap: 6px !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"] {
        height: 32px !important;
        min-width: 180px !important;
        padding: 4px 12px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"]:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Status Dropdown */
    .custom-status-select,
    #portfolio_status {
        height: 32px !important;
        min-width: 105px !important;
        padding: 2px 24px 2px 10px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 8px center !important;
        background-size: 10px 10px !important;
    }
    .custom-status-select:focus,
    #portfolio_status:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Main table: keep header and body in the same native scroll container */
    #portfolio-table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: #ffffff !important;
        width: 100% !important;
        min-width: 1250px !important;
        table-layout: auto !important;
    }

    /* DataTables wrapper must stay inside the card */
    #portfolio-table_wrapper,
    #portfolio-table_wrapper .dataTables_scroll,
    #portfolio-table_wrapper .dataTables_scrollBody {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    /* Avoid a second scrollbar when DataTables creates its own scroll elements */
    #portfolio-table_wrapper .dataTables_scrollHead {
        overflow: visible !important;
    }

    /* Header Styling */
    #portfolio-table thead th {
        background-color: #f9f9fd !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: 0.2px !important;
        padding: 10px 16px !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1.5px solid #edf0f7 !important;
        white-space: nowrap !important;
    }

    /* Row & Body Cells */
    #portfolio-table tbody td {
        padding: 10px 16px !important;
        vertical-align: middle !important;
        font-size: 12.5px !important;
        color: #334155 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        background-color: #ffffff !important;
        line-height: 1.4 !important;
        transition: background-color 0.15s ease !important;
    }
    #portfolio-table tbody tr:hover td {
        background-color: #fbfbfe !important;
    }
    #portfolio-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* IP Address Badge */
    .ip-badge {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 12px;
        color: #475569;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 4px;
        display: inline-block;
        white-space: nowrap;
    }

    /* Country Flag & Label Styles (Matching portfolios.php) */
    .country-cell-wrapper {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 3px 5px;
        max-width: 100%;
        line-height: normal;
    }
    .country-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        vertical-align: middle;
        line-height: normal;
    }
    .country-flag-img {
        width: 20px;
        height: 15px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
        vertical-align: middle;
        display: inline-block;
        background-color: #f8fafc;
    }
    .country-name-text {
        font-size: 12px;
        color: #334155;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    /* Responsive table controls and contained horizontal scrolling */
    .portfolio-table-card,
    .portfolio-table-responsive,
    #portfolio-table_wrapper {
        min-width: 0 !important;
        max-width: 100% !important;
    }

    .portfolio-table-responsive {
        overflow-x: auto !important;
        overflow-y: hidden !important;
        overscroll-behavior-x: contain;
        -webkit-overflow-scrolling: touch;
    }

    #portfolio-table_wrapper .dataTables_length,
    #portfolio-table_wrapper .dataTables_filter {
        max-width: calc(100% - 32px);
        box-sizing: border-box !important;
    }

    #portfolio-table_wrapper .dataTables_info {
        max-width: 55%;
        box-sizing: border-box !important;
    }

    #portfolio-table_wrapper .dataTables_paginate {
        max-width: 100%;
        box-sizing: border-box !important;
        overflow-x: auto;
        white-space: nowrap;
    }

    @media (max-width: 992px) {
        .container-fluid {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .portfolio-table-card {
            border-radius: 10px;
        }

        #portfolio-table_wrapper .dataTables_length {
            float: left !important;
            margin: 12px !important;
        }

        #portfolio-table_wrapper .dataTables_filter {
            float: right !important;
            margin: 12px !important;
            gap: 8px !important;
        }

        #portfolio-table_wrapper .dataTables_filter input[type="search"] {
            min-width: 140px !important;
            max-width: 48vw !important;
        }
    }

    @media (max-width: 576px) {
        .portfolio-page-header {
            margin-bottom: 14px;
            align-items: flex-start;
        }

        .portfolio-page-title {
            flex-wrap: wrap;
            font-size: 19px;
        }

        .portfolio-count-badge {
            font-size: 16px;
        }

        .portfolio-page-desc {
            font-size: 12px;
        }

        #portfolio-table_wrapper .dataTables_length,
        #portfolio-table_wrapper .dataTables_filter {
            float: none !important;
            display: flex !important;
            justify-content: space-between !important;
            width: calc(100% - 24px) !important;
            max-width: none !important;
            margin: 10px 12px !important;
        }

        #portfolio-table_wrapper .dataTables_filter label {
            width: 100%;
            min-width: 0;
        }

        #portfolio-table_wrapper .dataTables_filter input[type="search"] {
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
        }

        #portfolio-table_wrapper .dataTables_filter #portfolio_status,
        #portfolio-table_wrapper .dataTables_filter .custom-status-select {
            min-width: 92px !important;
        }

        #portfolio-table_wrapper .dataTables_info {
            float: none !important;
            max-width: 100%;
            padding: 10px 12px !important;
        }

        #portfolio-table_wrapper .dataTables_paginate {
            float: none !important;
            display: flex !important;
            justify-content: flex-start !important;
            padding: 8px 12px 12px !important;
        }

        #portfolio-table_paginate .paginate_button {
            flex: 0 0 auto;
        }

        #portfolio-table thead th,
        #portfolio-table tbody td {
            padding: 9px 12px !important;
        }
    }

    /* Empty state styling */
    .empty-table-cell {
        text-align: center !important;
        padding: 50px 20px !important;
        background-color: #ffffff !important;
        border: none !important;
    }
    .empty-state-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .empty-icon {
        font-size: 36px;
        color: #cbd5e1;
    }
    .empty-text {
        font-size: 14.5px;
        font-weight: 600;
        color: #475569;
    }
    .empty-subtext {
        font-size: 12.5px;
        color: #94a3b8;
    }

    /* Table Footer & Pagination */
    #portfolio-table_wrapper .dataTables_info {
        float: left;
        padding: 12px 16px !important;
        font-size: 12.5px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }
    #portfolio-table_wrapper .dataTables_paginate {
        float: right;
        padding: 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    #portfolio-table_paginate .paginate_button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 28px !important;
        height: 28px !important;
        padding: 0 8px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        margin: 0 2px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        outline: none !important;
        box-shadow: none !important;
    }
    #portfolio-table_paginate .paginate_button:hover {
        background-color: #f5f3ff !important;
        border-color: #c7d2fe !important;
        color: #5b52f9 !important;
    }
    #portfolio-table_paginate .paginate_button.current,
    #portfolio-table_paginate .paginate_button.current:hover,
    #portfolio-table_paginate .page-item.active .page-link {
        background: #5b52f9 !important;
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(91, 82, 249, 0.3) !important;
    }
    #portfolio-table_paginate .paginate_button.disabled,
    #portfolio-table_paginate .paginate_button.disabled:hover {
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        background-color: #f8fafc !important;
        cursor: not-allowed !important;
    }

    /* Enhanced Login Logs Badges & Action Styles */
    .device-badge, .env-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #334155;
        white-space: nowrap;
    }
    .device-badge i, .env-badge i {
        font-size: 12.5px;
        flex-shrink: 0;
    }
    .icon-windows { color: #0078d7; }
    .icon-apple { color: #1e293b; }
    .icon-android { color: #10b981; }
    .icon-linux { color: #f59e0b; }
    .icon-cros { color: #ea4335; }
    .icon-chrome { color: #ea4335; }
    .icon-edge { color: #0078d7; }
    .icon-firefox { color: #f97316; }
    .icon-safari { color: #0284c7; }
    .icon-opera { color: #dc2626; }
    .icon-ie { color: #0284c7; }

    .badge-method {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2.5px 8px;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-method-password {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .badge-method-remember {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .badge-method-autologin {
        background-color: #ede9fe;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-status-active {
        background-color: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .badge-status-loggedout {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .badge-status-expired {
        background-color: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
        animation: pulseDot 2s infinite;
    }
    @keyframes pulseDot {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .log-na {
        color: #94a3b8;
        font-size: 11.5px;
        font-weight: 500;
    }

    .btn-view-log-details {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        font-size: 11.5px;
        font-weight: 600;
        color: #5b52f9;
        background-color: #f5f4fe;
        border: 1px solid #e0ddfc;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s ease;
        line-height: 1.3;
        text-decoration: none !important;
    }
    .btn-view-log-details:hover {
        background-color: #5b52f9;
        color: #ffffff;
        border-color: #5b52f9;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.25);
    }

    /* Modal Layout & Styles */
    .log-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    @media (max-width: 576px) {
        .log-details-grid {
            grid-template-columns: 1fr;
        }
        .log-detail-card[style*="span 2"] {
            grid-column: span 1 !important;
        }
    }
    .log-detail-card {
        background: #ffffff;
        border: 1px solid #edf0f7;
        border-radius: 8px;
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .log-detail-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .log-detail-label i {
        font-size: 11px;
        color: #94a3b8;
    }
    .log-detail-value {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    .log-detail-ua {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
        border-radius: 6px;
        margin-top: 4px;
        word-break: break-all;
        max-height: 80px;
        overflow-y: auto;
    }

</style>

<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM login_logs ORDER BY id DESC");
                $total_logs = mysqli_num_rows($result);
                ?>
                <!-- Modern Page Header matching portfolios.php -->
                <div class="portfolio-page-header">
                    <div class="portfolio-header-left">
                        <div class="portfolio-header-title-wrap">
                            <h1 class="portfolio-page-title">Login Logs <span class="portfolio-count-badge">(<?php echo $total_logs; ?> found)</span></h1>
                        </div>
                        <p class="portfolio-page-desc">View and monitor user login timestamps, IP addresses, and authentication history.</p>
                    </div>
                </div>

                <!-- Modern Table Card matching portfolios.php -->
                <div class="portfolio-table-card" id="ajax_table_print">
                    <div class="portfolio-table-responsive">
                        <table class="table" id="portfolio-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>IP Address</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($total_logs > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $login_time_formatted = !empty($row['login_time']) ? date("d/m/Y h:i A", strtotime($row['login_time'])) : '<span class="log-na">N/A</span>';
                                        
                                        $status = resolveLogSessionStatus($row);
                                        
                                        $logout_time_formatted = !empty($row['logout_time']) 
                                            ? date("d/m/Y h:i A", strtotime($row['logout_time'])) 
                                            : ($status === 'Active' ? '<span class="badge-status badge-status-active"><span class="status-dot"></span> Active</span>' : '<span class="log-na">N/A</span>');
                                        
                                        $duration = getLogSessionDuration($row['login_time'] ?? null, $row['logout_time'] ?? null, $status);
                                        $duration_html = ($duration !== 'N/A') ? htmlspecialchars($duration) : '<span class="log-na">N/A</span>';
                                ?>
                                        <tr>
                                            <td style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
                                            <td><span class="ip-badge"><?php echo htmlspecialchars($row['ip_address'] ?? ''); ?></span></td>
                                            <td class="col-country"><?php echo render_country_flags($row['country'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($row['city'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td style="text-align: center; white-space: nowrap;">
                                                <button type="button" class="btn-view-log-details" data-id="<?php echo (int)$row['id']; ?>" title="View Details">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="empty-table-cell">
                                            <div class="empty-state-wrap">
                                                <i class="fas fa-history empty-icon"></i>
                                                <div class="empty-text">No Login Logs Found</div>
                                                <div class="empty-subtext">No user activity recorded yet.</div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Login Record Details -->
<div class="modal fade" id="loginLogDetailsModal" tabindex="-1" role="dialog" aria-labelledby="loginLogDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 14px; border: 1.5px solid #e8ecf4; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.12);">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1.5px solid #edf0f7; padding: 16px 22px; border-top-left-radius: 14px; border-top-right-radius: 14px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 34px; height: 34px; border-radius: 8px; background: #f5f4fe; color: #5b52f9; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="loginLogDetailsModalLabel" style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.2;">Login Record Details <span id="modalLogId" style="font-size: 13px; font-weight: 600; color: #5b52f9;"></span></h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0;">Comprehensive authentication and session audit record.</p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 10px; margin: -10px -10px -10px auto; opacity: 0.6; font-size: 22px; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 22px; background: #fafbfe;">
                <!-- Loading State -->
                <div id="loginDetailsLoading" style="text-align: center; padding: 35px 20px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 28px; color: #5b52f9;"></i>
                    <p style="margin-top: 10px; font-size: 13px; color: #64748b;">Loading login details...</p>
                </div>
                <!-- Error Alert -->
                <div id="loginDetailsError" class="alert alert-danger" style="display: none; border-radius: 8px; font-size: 13px;"></div>
                
                <!-- Content Area -->
                <div id="loginDetailsContent" style="display: none;">
                    <div class="log-details-grid">
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-user"></i> Username</span>
                            <span class="log-detail-value" id="detailUsername"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-network-wired"></i> IP Address</span>
                            <span class="log-detail-value" id="detailIpAddress" style="font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-globe-americas"></i> Country</span>
                            <span class="log-detail-value" id="detailCountry"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-city"></i> City</span>
                            <span class="log-detail-value" id="detailCity"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-laptop"></i> Device Type</span>
                            <span class="log-detail-value" id="detailDeviceType"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-compass"></i> Browser & Version</span>
                            <span class="log-detail-value" id="detailBrowser"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-microchip"></i> Operating System</span>
                            <span class="log-detail-value" id="detailOs"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-sign-in-alt"></i> Login Time</span>
                            <span class="log-detail-value" id="detailLoginTime"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-sign-out-alt"></i> Logout Time</span>
                            <span class="log-detail-value" id="detailLogoutTime"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-hourglass-half"></i> Session Duration</span>
                            <span class="log-detail-value" id="detailSessionDuration"></span>
                        </div>
                        <div class="log-detail-card">
                            <span class="log-detail-label"><i class="fas fa-shield-alt"></i> Login Method</span>
                            <span class="log-detail-value" id="detailLoginMethod"></span>
                        </div>
                        <div class="log-detail-card" style="grid-column: span 2;">
                            <span class="log-detail-label"><i class="fas fa-signal"></i> Session Status</span>
                            <span class="log-detail-value" id="detailSessionStatus"></span>
                        </div>
                        <div class="log-detail-card" id="detailUserAgentRow" style="grid-column: span 2;">
                            <span class="log-detail-label"><i class="fas fa-code"></i> User-Agent String</span>
                            <div class="log-detail-ua" id="detailUserAgent"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #ffffff; border-top: 1.5px solid #edf0f7; padding: 12px 22px; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px; font-size: 13px; font-weight: 500; padding: 6px 16px;">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>

<script>
    function copyToClipboard(clickedButton) {
        var textarea = $(clickedButton).parent().find('textarea');
        textarea.select();
        document.execCommand('copy');
        Toastify({
            text: 'Copied to clipboard!',
            duration: 3000,
            close: true,
            gravity: 'top-right',
            backgroundColor: '#10b981',
        }).showToast();
    }
    $(document).ready(function() {
        $(document).on('change', '.fav_change_inp', function() {
            var _fav_website = $('#fav_websites').val();
            var _fav_design = $('#fav_designs').val();
            $.ajax({
                url: 'ajax_functions.php',
                method: 'POST',
                data: {
                    action: 'updateFavouritePortfolio',
                    website: _fav_website,
                    design: _fav_design
                },
                success: function(response) {
                    // window.location.reload();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
        $(document).on('click', '.toggle-favorite', function() {
            var itemId = $(this).data('id');
            var isFavorite = $(this).hasClass('fas') ? 0 : 1;
            $.ajax({
                url: 'ajax_functions.php',
                method: 'POST',
                data: {
                    action: 'changeFavouritePortfolio',
                    item_id: itemId,
                    is_favorite: isFavorite
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
            $(this).toggleClass('far fas');
        });
        $(document).on('click', '.delete-btn', function() {
            var _id = $(this).attr('data-id');
            $('#confirmDelete').attr('data-id', _id);
            $('#deleteConfirmationModal').modal('show');
        });
        $(document).on('click', '#confirmDelete', function() {
            var _id = $(this).attr('data-id');
            $.ajax({
                url: "ajax_functions.php",
                type: "POST",
                data: {
                    action: 'delet_portfolio',
                    portfolio_id: _id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
            $('#deleteConfirmationModal').modal('hide');
        });
        var table = $('#portfolio-table').DataTable({
            "autoWidth": false,
            "scrollX": false,
            "aaSorting": [],
            "columnDefs": [
                { "orderable": false, "targets": 11 }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search logs...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
        });

        // View Details Modal Handler
        $(document).on('click', '.btn-view-log-details', function(e) {
            e.preventDefault();
            var logId = $(this).data('id');
            if (!logId) return;

            $('#modalLogId').text('#' + logId);
            $('#loginDetailsLoading').show();
            $('#loginDetailsContent').hide();
            $('#loginDetailsError').hide();
            $('#loginLogDetailsModal').modal('show');

            $.ajax({
                url: 'ajax_functions.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'get_login_log_details',
                    log_id: logId
                },
                success: function(res) {
                    $('#loginDetailsLoading').hide();
                    if (res && res.success && res.data) {
                        var d = res.data;
                        $('#detailUsername').text(d.username);
                        $('#detailIpAddress').text(d.ip_address);
                        $('#detailCountry').text(d.country);
                        $('#detailCity').text(d.city || 'Unknown');
                        $('#detailDeviceType').text(d.device_type);
                        $('#detailBrowser').text(d.browser);
                        $('#detailOs').text(d.os);
                        $('#detailLoginTime').text(d.login_time);
                        $('#detailLogoutTime').text(d.logout_time);
                        $('#detailSessionDuration').text(d.session_duration);
                        $('#detailLoginMethod').text(d.login_method);
                        
                        var statusHtml = d.session_status;
                        if (d.session_status === 'Active') {
                            statusHtml = '<span class="badge-status badge-status-active"><span class="status-dot"></span> Active</span>';
                        } else if (d.session_status === 'Logged Out') {
                            statusHtml = '<span class="badge-status badge-status-loggedout"><i class="fas fa-sign-out-alt"></i> Logged Out</span>';
                        } else if (d.session_status === 'Expired') {
                            statusHtml = '<span class="badge-status badge-status-expired"><i class="far fa-clock"></i> Expired</span>';
                        } else {
                            statusHtml = '<span class="log-na">N/A</span>';
                        }
                        $('#detailSessionStatus').html(statusHtml);

                        if (d.user_agent && d.user_agent !== 'Not Available' && d.user_agent !== 'N/A') {
                            $('#detailUserAgent').text(d.user_agent);
                            $('#detailUserAgentRow').show();
                        } else {
                            $('#detailUserAgentRow').hide();
                        }

                        $('#loginDetailsContent').fadeIn(150);
                    } else {
                        $('#loginDetailsError').text((res && res.message) ? res.message : 'Unable to fetch login log details.').show();
                    }
                },
                error: function(xhr) {
                    $('#loginDetailsLoading').hide();
                    var msg = 'An error occurred while loading details.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $('#loginDetailsError').text(msg).show();
                }
            });
        });

        var customDropdown =
            '<label><select id="portfolio_status" class="custom-status-select">' +
            '<option value="0">Published</option>' +
            '<option value="1">Deleted</option>' +
            '</select></label>';
        $('#portfolio-table_wrapper .dataTables_filter').append(customDropdown);
        $(document).on('change', '#portfolio_status', function() {
            var selectedValue = $(this).val();
            $.ajax({
                url: 'ajax_functions.php',
                method: 'GET',
                data: {
                    action: 'get_portfolios',
                    value: selectedValue
                },
                success: function(response) {
                    $('#ajax_table_print').html(response);
                    var newTable = $('#portfolio-table').DataTable({
                        "autoWidth": false,
                        "scrollX": false,
                        "aaSorting": [],
                        "columnDefs": [
                            { "orderable": false, "targets": 11 }
                        ],
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "pageLength": 10,
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search logs...",
                            lengthMenu: "Show _MENU_ entries"
                        }
                    });
                    var customDropdown =
                        '<label><select id="portfolio_status" class="custom-status-select">' +
                        '<option value="0">Published</option>' +
                        '<option value="1">Deleted</option>' +
                        '</select></label>';
                    var $dropdown = $(customDropdown);
                    $dropdown.find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#portfolio-table_wrapper .dataTables_filter').append($dropdown);
                    table = newTable;
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
        var name;
        $('.copy-link').on('contextmenu', function(event) {
            event.preventDefault();
            var linkURL = $(this).attr('href');
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(linkURL).select();
            document.execCommand('copy');
            tempInput.remove();
            Toastify({
                text: 'Copied to clipboard!',
                duration: 3000,
                close: true,
                gravity: 'top-right',
                backgroundColor: '#10b981',
            }).showToast();
        });
        $(".company_name").on({
            mouseenter: function() {
                name = $(this).find('span').attr('id');
                $('#' + name).show();
            },
            mouseleave: function() {
                name = $(this).find('span').attr('id');
                $('#' + name).hide();
            }
        });
    });
</script>