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
       GLOBAL LAYOUT & RESETS (Matching portfolios.php & clients.php)
       ========================================================= */
    html, body {
        overflow-x: hidden !important;
        max-width: 100% !important;
    }
    .content-body {
        background-color: #f7f8fb !important;
        min-height: 100vh;
        padding-bottom: 40px;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        max-width: 100% !important;
    }
    .page-titles,
    .breadcrumb {
        display: none !important;
    }

    /* =========================================================
       MODERN PAGE HEADER
       ========================================================= */
    .portfolio-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-top: 4px;
        gap: 15px;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
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
       SWIPE HINT BANNER (Mobile / Tablet)
       ========================================================= */
    .table-scroll-hint {
        display: none !important;
        background: linear-gradient(90deg, #f8fafc 0%, #eef2ff 50%, #f8fafc 100%);
        color: #5b52f9;
        font-size: 11.5px;
        font-weight: 600;
        padding: 7px 12px;
        border-bottom: 1px solid #e0e7ff;
        text-align: center;
        user-select: none;
        letter-spacing: 0.1px;
    }
    .table-scroll-hint i {
        font-size: 12px;
        color: #5b52f9;
    }
    @media (max-width: 991.98px) {
        .table-scroll-hint {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
    }

    /* =========================================================
       MODERN TABLE CARD & DATATABLES CONTROLS
       ========================================================= */
    .portfolio-table-card {
        background: #ffffff !important;
        border: 1.5px solid #e8ecf4 !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .portfolio-table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    #portfolio-table_wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }

    /* Row 1: Top Toolbar (Length + Search) */
    #portfolio-table_wrapper > .row:nth-child(1) {
        margin: 0 !important;
        padding: 12px 18px 10px 18px !important;
        background: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
    }
    #portfolio-table_wrapper > .row:nth-child(1) > [class*="col-"] {
        padding: 0 !important;
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: 100% !important;
    }
    #portfolio-table_wrapper .dataTables_length {
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    #portfolio-table_wrapper .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        gap: 6px !important;
    }
    #portfolio-table_wrapper .dataTables_length select {
        height: 34px !important;
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
        transition: all 0.2s ease !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 8px center !important;
        background-size: 10px 10px !important;
    }
    #portfolio-table_wrapper .dataTables_length select:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    #portfolio-table_wrapper .dataTables_filter {
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    #portfolio-table_wrapper .dataTables_filter label {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        position: relative !important;
        width: 100% !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"] {
        height: 36px !important;
        min-width: 220px !important;
        padding: 0 12px 0 34px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
        width: 100% !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"]:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }
    #portfolio-table_wrapper .dataTables_filter label::before {
        content: "\f002";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
        z-index: 2;
    }

    /* Row 2: Table Scroll Area */
    #portfolio-table_wrapper > .row:nth-child(2) {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > [class*="col-"] {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain;
    }

    /* Custom Sleek Scrollbar */
    #portfolio-table_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar {
        height: 6px;
        width: 6px;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Main Table Element */
    #portfolio-table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: #ffffff !important;
        width: 100% !important;
        table-layout: fixed !important;
    }

    /* Sticky Header */
    #portfolio-table thead th {
        position: sticky !important;
        top: 0 !important;
        z-index: 5 !important;
        background-color: #f9f9fd !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: 0.2px !important;
        padding: 10px 8px !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1.5px solid #edf0f7 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        box-sizing: border-box !important;
    }

    /* Row & Body Cells */
    #portfolio-table tbody td {
        padding: 9px 8px !important;
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
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        box-sizing: border-box !important;
    }
    #portfolio-table tbody tr:hover td {
        background-color: #fbfbfe !important;
    }
    #portfolio-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Column Widths */
    #portfolio-table th.col-username, #portfolio-table td.col-username { width: 140px !important; }
    #portfolio-table th.col-ip, #portfolio-table td.col-ip { width: 130px !important; }
    #portfolio-table th.col-country, #portfolio-table td.col-country { width: 130px !important; }
    #portfolio-table th.col-city, #portfolio-table td.col-city { width: 130px !important; }
    #portfolio-table th.col-action, #portfolio-table td.col-action {
        width: 90px !important;
        min-width: 90px !important;
        text-align: center !important;
        white-space: nowrap !important;
        padding: 8px 4px !important;
    }

    /* IP Badge */
    .ip-badge {
        display: inline-block;
        padding: 3px 8px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11.5px;
        font-weight: 500;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        letter-spacing: 0.2px;
    }

    /* Country Flag */
    .country-cell-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 100%;
    }
    .country-flag-img {
        width: 20px;
        height: 15px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
    }
    .country-name-text {
        font-size: 12.5px;
        color: #334155;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* View Button */
    .btn-view-log-details {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 4px 12px;
        font-size: 11.5px;
        font-weight: 600;
        color: #5b52f9;
        background-color: #f5f4fe;
        border: 1px solid #e0ddfc;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .btn-view-log-details:hover {
        background-color: #5b52f9;
        color: #ffffff !important;
        border-color: #5b52f9;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.25);
    }
    .btn-view-log-details i {
        font-size: 11px;
    }

    /* Cell Truncate */
    .cell-truncate {
        display: block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Row 3: Bottom info & pagination */
    #portfolio-table_wrapper > .row:nth-child(3) {
        margin: 0 !important;
        padding: 12px 18px !important;
        background: #ffffff !important;
        border-top: 1px solid #f1f5f9 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    #portfolio-table_wrapper > .row:nth-child(3) > [class*="col-"] {
        padding: 0 !important;
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: 100% !important;
    }
    #portfolio-table_wrapper .dataTables_info {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
    }
    #portfolio-table_wrapper .dataTables_paginate {
        margin: 0 !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
    }
    #portfolio-table_wrapper .dataTables_paginate .pagination {
        margin: 0 !important;
        gap: 4px !important;
    }
    #portfolio-table_wrapper .dataTables_paginate .page-item .page-link {
        height: 30px !important;
        min-width: 30px !important;
        padding: 0 9px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        transition: all 0.15s ease !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }
    #portfolio-table_wrapper .dataTables_paginate .page-item.active .page-link {
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.3) !important;
    }
    #portfolio-table_wrapper .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
        color: #1e293b !important;
    }
    #portfolio-table_wrapper .dataTables_paginate .page-item.disabled .page-link {
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        background-color: #f8fafc !important;
        cursor: not-allowed !important;
    }

    /* Modal Layout & Styles */
    .log-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
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
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 5px;
    }
    .badge-status-active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .badge-status-loggedout {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .badge-status-expired {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #10b981;
        display: inline-block;
    }
    .log-na {
        color: #94a3b8;
        font-style: italic;
    }

    /* Screen Width Responsive Adaptation for Table */
    @media (max-width: 991.98px) {
        #portfolio-table {
            min-width: 620px !important;
            width: 620px !important;
        }
    }
    @media (min-width: 992px) {
        #portfolio-table {
            min-width: 100% !important;
            width: 100% !important;
        }
    }

    /* Mobile Screens (<= 767.98px) */
    @media (max-width: 767.98px) {
        .content-body {
            padding: 4px 10px 24px !important;
            margin-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }
        .content-body .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 4px !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .portfolio-page-header {
            margin-top: 0 !important;
            padding-top: 0 !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 6px !important;
            margin-bottom: 12px !important;
        }
        #portfolio-table_wrapper > .row:nth-child(1) {
            flex-direction: column-reverse !important;
            align-items: stretch !important;
            gap: 8px !important;
            padding: 10px 12px !important;
        }
        #portfolio-table_wrapper > .row:nth-child(1) > [class*="col-"] {
            width: 100% !important;
        }
        #portfolio-table_wrapper .dataTables_filter {
            width: 100% !important;
        }
        #portfolio-table_wrapper .dataTables_filter label {
            width: 100% !important;
        }
        #portfolio-table_wrapper .dataTables_filter input[type="search"] {
            width: 100% !important;
            min-width: 100% !important;
            height: 38px !important;
        }
        #portfolio-table_wrapper .dataTables_length {
            width: 100% !important;
            justify-content: space-between !important;
        }
        #portfolio-table_wrapper .dataTables_length label {
            width: 100% !important;
            justify-content: space-between !important;
        }
        #portfolio-table_wrapper > .row:nth-child(3) {
            flex-direction: column !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 10px 12px !important;
        }
        #portfolio-table_wrapper > .row:nth-child(3) > [class*="col-"] {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
        }
        #portfolio-table_wrapper .dataTables_info {
            text-align: center !important;
        }
        #portfolio-table_wrapper .dataTables_paginate {
            justify-content: center !important;
            flex-wrap: wrap !important;
        }
        .modal-dialog {
            margin: 12px auto !important;
            max-width: calc(100% - 24px) !important;
        }
        .log-details-grid {
            grid-template-columns: 1fr !important;
            gap: 8px !important;
        }
        .log-detail-card[style*="span 2"],
        #detailUserAgentRow {
            grid-column: span 1 !important;
        }
    }
</style>

<div class="content-body portfolio-page-body">
    <div class="container-fluid px-4 py-3">
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
            <div class="table-scroll-hint">
                <i class="fas fa-arrows-alt-h"></i>
                <span>Swipe horizontally to view all columns</span>
            </div>
            <div class="portfolio-table-responsive">
                <table class="table" id="portfolio-table">
                    <colgroup>
                        <col class="col-username" style="width: 140px;">
                        <col class="col-ip" style="width: 130px;">
                        <col class="col-country" style="width: 130px;">
                        <col class="col-city" style="width: 130px;">
                        <col class="col-action" style="width: 90px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="col-username" title="Username">Username</th>
                            <th class="col-ip" title="IP Address">IP Address</th>
                            <th class="col-country" title="Country">Country</th>
                            <th class="col-city" title="City">City</th>
                            <th class="col-action" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        if ($total_logs > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $username = $row['username'] ?? '';
                                $ip_address = $row['ip_address'] ?? '';
                                $country = $row['country'] ?? '';
                                $city = $row['city'] ?? 'Unknown';
                        ?>
                                <tr>
                                    <td class="col-username" style="font-weight: 600; color: #1e293b;" title="<?php echo htmlspecialchars($username); ?>">
                                        <span class="cell-truncate"><?php echo htmlspecialchars($username); ?></span>
                                    </td>
                                    <td class="col-ip" title="<?php echo htmlspecialchars($ip_address); ?>">
                                        <span class="ip-badge"><?php echo htmlspecialchars($ip_address); ?></span>
                                    </td>
                                    <td class="col-country" title="<?php echo htmlspecialchars($country); ?>">
                                        <span class="country-cell-wrapper"><?php echo render_country_flags($country); ?></span>
                                    </td>
                                    <td class="col-city" title="<?php echo htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="cell-truncate"><?php echo htmlspecialchars($city, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </td>
                                    <td class="col-action" style="text-align: center; white-space: nowrap;">
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
        $.fn.DataTable.ext.pager.numbers_length = 4;
        var table = $('#portfolio-table').DataTable({
            "autoWidth": false,
            "scrollX": false,
            "aaSorting": [],
            "columnDefs": [
                { "orderable": false, "targets": [4] },
                { "width": "140px", "targets": 0 },
                { "width": "130px", "targets": 1 },
                { "width": "130px", "targets": 2 },
                { "width": "130px", "targets": 3 },
                { "width": "90px", "targets": 4 }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search logs...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
        table.columns.adjust();
        // Add native hover tooltips to truncated cells so full values remain accessible.
        $('#portfolio-table').on('mouseenter', 'th, td, .ip-badge', function() {
            var $cell = $(this);
            var fullText = $cell.text().trim();
            if (fullText && (this.scrollWidth > this.clientWidth || $cell.find('.ip-badge').length)) {
                $cell.attr('title', fullText);
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



