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
       GLOBAL LAYOUT & RESETS (Matching portfolios.php)
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
    .portfolio-header-right {
        flex: 0 0 auto;
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
    .btn-add-portfolio {
        height: 38px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        background: #5b52f9;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(91, 82, 249, 0.25);
        border: none;
        white-space: nowrap;
        cursor: pointer;
    }
    .btn-add-portfolio:hover {
        background: #483fe6;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(91, 82, 249, 0.35);
    }
    .btn-add-portfolio i {
        font-size: 12px;
    }

    /* Desktop Header Alignment */
    @media (min-width: 768px) {
        .portfolio-page-header {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .portfolio-header-right {
            width: auto !important;
            flex: 0 0 auto !important;
        }
        .btn-add-portfolio {
            width: auto !important;
            display: inline-flex !important;
        }
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

    /* Column Widths (Fixed proportions on table) */
    #portfolio-table th.col-client-name, #portfolio-table td.col-client-name { width: 130px !important; }
    #portfolio-table th.col-email, #portfolio-table td.col-email { width: 150px !important; }
    #portfolio-table th.col-phone, #portfolio-table td.col-phone { width: 105px !important; }
    #portfolio-table th.col-city, #portfolio-table td.col-city { width: 85px !important; }
    #portfolio-table th.col-country, #portfolio-table td.col-country { width: 110px !important; }
    #portfolio-table th.col-company, #portfolio-table td.col-company { width: 120px !important; }
    #portfolio-table th.col-website, #portfolio-table td.col-website { width: 120px !important; }
    #portfolio-table th.col-lead-type, #portfolio-table td.col-lead-type { width: 85px !important; }
    #portfolio-table th.col-status, #portfolio-table td.col-status { width: 80px !important; }
    #portfolio-table th.col-action, #portfolio-table td.col-action {
        width: 120px !important;
        min-width: 120px !important;
        text-align: center !important;
        white-space: nowrap !important;
        padding: 8px 4px !important;
    }

    /* General Cell Text Truncation Helper */
    .cell-truncate {
        display: block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
    }

    /* Email Link Truncation */
    .cell-email-link {
        display: block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #5b52f9 !important;
        text-decoration: none !important;
        font-weight: 500;
        transition: color 0.15s ease;
    }
    .cell-email-link:hover {
        color: #483fe6 !important;
        text-decoration: underline !important;
    }

    /* Website Link Truncation */
    .cell-website-link {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        max-width: 100% !important;
        min-width: 0 !important;
        color: #5b52f9 !important;
        text-decoration: none !important;
        font-weight: 500;
        vertical-align: middle;
        transition: color 0.15s ease;
    }
    .cell-website-link:hover {
        color: #483fe6 !important;
        text-decoration: underline !important;
    }
    .cell-website-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
        flex: 1 1 auto;
    }
    .cell-website-icon {
        font-size: 10px;
        flex-shrink: 0;
        opacity: 0.85;
    }

    /* Badges with Ellipsis */
    .portfolio-badge {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 2px 7px;
        font-size: 11px;
        border-radius: 4px;
        line-height: 1.35;
        font-weight: 500;
        vertical-align: middle;
        box-sizing: border-box;
    }
    .badge-tech {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .badge-cms {
        background-color: #eef2ff;
        color: #5b52f9;
        border: 1px solid #e0e7ff;
    }

    /* Action Buttons */
    .table-actions-inline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
        max-width: 100%;
    }
    .btn-table-action {
        height: 26px;
        border-radius: 5px;
        padding: 0 8px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.15s ease;
        line-height: 1;
        border: none;
        outline: none !important;
        flex-shrink: 0;
    }
    .btn-table-view {
        background-color: #eef2ff;
        color: #5b52f9 !important;
        border: 1px solid #e0e7ff;
    }
    .btn-table-view:hover {
        background-color: #5b52f9;
        border-color: #5b52f9;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    .btn-table-delete {
        background-color: #fef2f2;
        color: #ef4444 !important;
        border: 1px solid #fee2e2;
    }
    .btn-table-delete:hover {
        background-color: #ef4444;
        border-color: #ef4444;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Country Flag & Label Styles */
    .country-cell-wrapper {
        display: inline-flex;
        flex-wrap: nowrap;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        min-width: 0;
        line-height: normal;
        vertical-align: middle;
        overflow: hidden;
    }
    .country-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        min-width: 0;
        vertical-align: middle;
        line-height: normal;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .country-flag-img {
        width: 18px;
        height: 13.5px;
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
        min-width: 0;
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

    /* Row 3: Bottom Footer (Info + Pagination) */
    #portfolio-table_wrapper > .row:nth-child(3) {
        margin: 0 !important;
        padding: 10px 18px !important;
        background: #ffffff !important;
        border-top: 1px solid #f1f5f9 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
    }
    #portfolio-table_wrapper > .row:nth-child(3) > [class*="col-"] {
        padding: 0 !important;
        flex: 0 0 auto !important;
        width: auto !important;
        max-width: 100% !important;
    }
    #portfolio-table_wrapper .dataTables_info {
        padding: 0 !important;
        font-size: 12px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }
    #portfolio-table_wrapper .dataTables_paginate {
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    /* Pagination Buttons */
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

    /* Screen Width Responsive Adaptation for Table */
    @media (max-width: 1199.98px) {
        #portfolio-table {
            min-width: 1050px !important;
            width: 1050px !important;
        }
    }
    @media (min-width: 1200px) {
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
            gap: 8px !important;
            margin-bottom: 12px !important;
        }
        .portfolio-header-right {
            width: 100% !important;
        }
        .btn-add-portfolio {
            width: 100% !important;
            height: 42px !important;
            font-size: 14px !important;
            justify-content: center !important;
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
            margin: 16px auto !important;
            max-width: calc(100% - 24px) !important;
        }
    }
</style>

<div class="content-body portfolio-page-body">
    <div class="container-fluid px-4 py-3">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM clients WHERE `deleted`='0' ORDER BY created_at DESC");
        $total_clients = mysqli_num_rows($result);
        ?>
        <!-- Modern Page Header matching portfolios.php -->
        <div class="portfolio-page-header">
            <div class="portfolio-header-left">
                <div class="portfolio-header-title-wrap">
                    <h1 class="portfolio-page-title">Clients <span class="portfolio-count-badge">(<?php echo $total_clients; ?> found)</span></h1>
                </div>
                <p class="portfolio-page-desc">Manage and track your clients, contacts, and lead statuses.</p>
            </div>
            <div class="portfolio-header-right">
                <a href="add-client.php" class="btn-add-portfolio">
                    <i class="fas fa-plus"></i> Add Client
                </a>
            </div>
        </div>

        <!-- Modern Table Card matching portfolios.php -->
        <div class="portfolio-table-card" id="ajax_table_print">
            <?php include 'clients-table.php'; ?>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Delete Confirmation -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: 1.5px solid #e8ecf4; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
            <div class="modal-header" style="border-bottom: 1.5px solid #edf0f7; padding: 16px 20px;">
                <h5 class="modal-title" id="deleteConfirmationModalLabel" style="font-weight: 600; font-size: 16px; color: #0f172a;">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 14px; color: #475569;">
                Are you sure you want to delete this client?
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 12px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px; font-size: 13px; font-weight: 500;">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete" style="border-radius: 6px; font-size: 13px; font-weight: 500; background-color: #ef4444; border-color: #ef4444;">Delete</button>
            </div>
        </div>
    </div>
</div>
<?php if (isset($_GET['success'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Toastify === 'function') {
        Toastify({ 
            text: "<?php echo $_GET['success']==='added'?'Client added successfully!':'Client updated successfully!'; ?>", 
            duration: 3500, 
            close: true, 
            gravity: "top", 
            position: "right", 
            backgroundColor: "#10b981", 
            stopOnFocus: true 
        }).showToast();
    }
    // Remove ?success=... from the URL bar so reloading the page won't trigger the toast again
    if (window.history && window.history.replaceState) {
        var cleanUrl = new URL(window.location.href);
        cleanUrl.searchParams.delete('success');
        window.history.replaceState({}, document.title, cleanUrl.pathname + (cleanUrl.search ? cleanUrl.search : ''));
    }
});
</script>
<?php endif; ?>
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
                    action: 'delet_client',
                    client_id: _id
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
            "aaSorting": [],
            "scrollX": false,
            "scrollCollapse": false,
            "columnDefs": [
                { "orderable": false, "targets": [9] },
                { "width": "130px", "targets": 0 },
                { "width": "150px", "targets": 1 },
                { "width": "105px", "targets": 2 },
                { "width": "85px", "targets": 3 },
                { "width": "110px", "targets": 4 },
                { "width": "120px", "targets": 5 },
                { "width": "120px", "targets": 6 },
                { "width": "85px", "targets": 7 },
                { "width": "80px", "targets": 8 },
                { "width": "120px", "targets": 9 }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search clients...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
        // Keep column sizing consistent after filtering, sorting, and pagination.
        table.columns.adjust();
        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
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

