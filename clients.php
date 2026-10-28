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
    @media (max-width: 768px) {
        .portfolio-page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .portfolio-header-right {
            width: 100%;
        }
        .btn-add-portfolio {
            width: 100%;
            justify-content: center;
        }
    }

    /* =========================================================
       MODERN TABLE CARD & STYLES (Matching portfolios.php)
       ========================================================= */
    .portfolio-table-card {
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow-x: hidden !important;
        overflow-y: visible !important;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .portfolio-table-responsive {
        width: 100% !important;
        overflow-x: hidden !important;
        overflow-y: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    #portfolio-table_wrapper,
    .portfolio-table-card .dataTables_wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: visible !important;
    }
    #portfolio-table_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }

    /* Responsive horizontal scrolling for table area only */
    #portfolio-table_wrapper > .row:nth-child(2) > .col-sm-12,
    .portfolio-table-card .dataTables_wrapper > .row:nth-child(2) > [class*="col-"] {
        overflow-x: auto !important;
        overflow-y: hidden !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
        margin-bottom: 0 !important;
        -webkit-overflow-scrolling: touch;
        border-top: 1px solid #edf0f7;
        border-bottom: 1px solid #edf0f7;
    }

    /* Custom horizontal scrollbar */
    #portfolio-table_wrapper > .row:nth-child(2) > .col-sm-12::-webkit-scrollbar,
    .portfolio-table-card .dataTables_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar {
        height: 7px;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > .col-sm-12::-webkit-scrollbar-track,
    .portfolio-table-card .dataTables_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > .col-sm-12::-webkit-scrollbar-thumb,
    .portfolio-table-card .dataTables_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    #portfolio-table_wrapper > .row:nth-child(2) > .col-sm-12::-webkit-scrollbar-thumb:hover,
    .portfolio-table-card .dataTables_wrapper > .row:nth-child(2) > [class*="col-"]::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
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

    /* Main Table Element */
    #portfolio-table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: #ffffff !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        table-layout: fixed !important;
    }

    /* Header Styling */
    #portfolio-table thead th {
        background-color: #f9f9fd !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: 0.2px !important;
        padding: 10px 16px 10px 8px !important;
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
        padding: 10px 8px !important;
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

    /* Explicit Column Width Proportions (Fit 100% without scrollbar) */
    /* Column Widths (readable minimums; scrollX handles overflow) */
    #portfolio-table th.col-client-name,
    #portfolio-table td.col-client-name,
    #portfolio-table th:nth-child(1),
    #portfolio-table td:nth-child(1) {
        min-width: 150px;
    }
    #portfolio-table th.col-email,
    #portfolio-table td.col-email,
    #portfolio-table th:nth-child(2),
    #portfolio-table td:nth-child(2) {
        min-width: 150px;
    }
    #portfolio-table th.col-phone,
    #portfolio-table td.col-phone,
    #portfolio-table th:nth-child(3),
    #portfolio-table td:nth-child(3) {
        min-width: 110px;
    }
    #portfolio-table th.col-city,
    #portfolio-table td.col-city,
    #portfolio-table th:nth-child(4),
    #portfolio-table td:nth-child(4) {
        min-width: 90px;
    }
    #portfolio-table th.col-country,
    #portfolio-table td.col-country,
    #portfolio-table th:nth-child(5),
    #portfolio-table td:nth-child(5) {
        min-width: 100px;
    }
    #portfolio-table th.col-company,
    #portfolio-table td.col-company,
    #portfolio-table th:nth-child(6),
    #portfolio-table td:nth-child(6) {
        min-width: 120px;
    }
    #portfolio-table th.col-website,
    #portfolio-table td.col-website,
    #portfolio-table th:nth-child(7),
    #portfolio-table td:nth-child(7) {
        min-width: 130px;
    }
    #portfolio-table th.col-lead-type,
    #portfolio-table td.col-lead-type,
    #portfolio-table th:nth-child(8),
    #portfolio-table td:nth-child(8) {
        min-width: 80px;
    }
    #portfolio-table th.col-status,
    #portfolio-table td.col-status,
    #portfolio-table th:nth-child(9),
    #portfolio-table td:nth-child(9) {
        min-width: 80px;
    }
    #portfolio-table th.col-action,
    #portfolio-table td.col-action,
    #portfolio-table th:nth-child(10),
    #portfolio-table td:nth-child(10) {
        min-width: 150px;
        text-align: center !important;
        padding-left: 4px !important;
        padding-right: 4px !important;
        overflow: visible !important;
    }
    #portfolio-table thead th.col-action::before,
    #portfolio-table thead th.col-action::after {
        display: none !important;
    }

    /* Fallback min-width on narrow mobile screens only */
    @media (max-width: 768px) {
        #portfolio-table {
            min-width: 850px !important;
        }
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
        background-color: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    /* Action Buttons (Matching portfolios.php) */
    .table-actions-inline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        white-space: nowrap;
        max-width: 100%;
    }
    .btn-table-action {
        height: 25px;
        border-radius: 4px;
        padding: 0 7px;
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
        background-color: #f2f2fd;
        color: #5950f6 !important;
        border: 1px solid #e0e0fb;
    }
    .btn-table-view:hover {
        background-color: #e4e4fc;
        border-color: #cfcffa;
        color: #3f35dc !important;
        transform: translateY(-1px);
    }
    .btn-table-delete {
        background-color: #fef2f2;
        color: #ef4444 !important;
        border: 1px solid #fee2e2;
    }
    .btn-table-delete:hover {
        background-color: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626 !important;
        transform: translateY(-1px);
    }

    /* Country Flag & Label Styles (Matching portfolios.php) */
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



    /* Final table layout fix: avoid DataTables scrollX cloned tables and
       let the single real table use the available card width. */
    .portfolio-table-card {
        overflow: visible !important;
        max-width: 100% !important;
    }
    .portfolio-table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        overflow-y: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-overflow-scrolling: touch;
    }
    #portfolio-table_wrapper,
    .portfolio-table-card .dataTables_wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
    }
    #portfolio-table {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
    }
    #portfolio-table thead th,
    #portfolio-table tbody td {
        min-width: 0 !important;
        max-width: 0;
        box-sizing: border-box !important;
    }
    /* Fix only the first column: keep the header text clear of the sorting indicator. */
    #portfolio-table th:nth-child(1),
    #portfolio-table td:nth-child(1) {
        width: 10% !important;
        min-width: 110px !important;
        padding-left: 14px !important;
        padding-right: 10px !important;
        box-sizing: border-box !important;
    }
    #portfolio-table th:nth-child(2), #portfolio-table td:nth-child(2) { width: 14% !important; }
    #portfolio-table th:nth-child(3), #portfolio-table td:nth-child(3) { width: 9% !important; }
    #portfolio-table th:nth-child(4), #portfolio-table td:nth-child(4) { width: 7% !important; }
    #portfolio-table th:nth-child(5), #portfolio-table td:nth-child(5) { width: 10% !important; }
    #portfolio-table th:nth-child(6), #portfolio-table td:nth-child(6) { width: 11% !important; }
    #portfolio-table th:nth-child(7), #portfolio-table td:nth-child(7) { width: 13% !important; }
    #portfolio-table th:nth-child(8), #portfolio-table td:nth-child(8) { width: 8% !important; }
    #portfolio-table th:nth-child(9), #portfolio-table td:nth-child(9) { width: 7% !important; }
    #portfolio-table th:nth-child(10), #portfolio-table td:nth-child(10) {
        width: 15% !important;
        min-width: 145px !important;
        max-width: none !important;
        text-align: center !important;
        white-space: nowrap !important;
        overflow: visible !important;
    }
    #portfolio-table tbody td:not(.col-action) {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    #portfolio-table .table-actions-inline {
        display: inline-flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 6px !important;
        width: 100%;
        max-width: 100%;
        white-space: nowrap !important;
    }
    #portfolio-table .btn-table-action {
        flex: 0 0 auto !important;
        padding: 0 7px !important;
    }
    /* Controls and pagination stay outside the scrolling table viewport. */
    #portfolio-table_wrapper .dataTables_length,
    #portfolio-table_wrapper .dataTables_filter {
        max-width: calc(100% - 24px);
        box-sizing: border-box;
    }
    #portfolio-table_wrapper .dataTables_info,
    #portfolio-table_wrapper .dataTables_paginate {
        box-sizing: border-box;
    }
    @media (max-width: 1100px) {
        #portfolio-table thead th,
        #portfolio-table tbody td {
            padding-left: 6px !important;
            padding-right: 6px !important;
            font-size: 11.5px !important;
        }
        #portfolio-table th:nth-child(11),
        #portfolio-table td:nth-child(11) {
            min-width: 145px !important;
        }
    }
    @media (max-width: 768px) {
        .portfolio-table-responsive {
            overflow-x: auto !important;
        }
        #portfolio-table {
            min-width: 1050px !important;
            width: 1050px !important;
            max-width: none !important;
        }
    }

</style>

<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Clients</a></li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
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
        var table = $('#portfolio-table').DataTable({
            "autoWidth": false,
            "aaSorting": [],
            "scrollX": false,
            "scrollCollapse": false,
            "columnDefs": [
                { "orderable": false, "targets": [9] },
                { "width": "10%", "targets": 0 },
                { "width": "14%", "targets": 1 },
                { "width": "9%", "targets": 2 },
                { "width": "7%", "targets": 3 },
                { "width": "10%", "targets": 4 },
                { "width": "11%", "targets": 5 },
                { "width": "13%", "targets": 6 },
                { "width": "8%", "targets": 7 },
                { "width": "7%", "targets": 8 },
                { "width": "15%", "targets": 9 }
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


<style id="mobile-responsive-clients">
/* Mobile layout for client list, filters, and actions */
@media (max-width: 767.98px) {
  .content-body { padding: 14px 9px 24px !important; overflow-x: clip; }
  .content-body > .container, .content-body .container, .content-body .container-fluid { width: 100% !important; max-width: 100% !important; padding-left: 6px !important; padding-right: 6px !important; }
  .portfolio-page-header { display: flex !important; flex-direction: column !important; align-items: stretch !important; gap: 12px !important; }
  .portfolio-header-left, .portfolio-header-right { width: 100% !important; max-width: 100% !important; }
  .portfolio-header-right { display: flex !important; flex-wrap: wrap !important; gap: 8px !important; }
  .portfolio-header-right .btn, .portfolio-header-right button { min-height: 42px; }
  .portfolio-table-card, .ajax_table_print, #portfolio-table_wrapper { width: 100% !important; max-width: 100% !important; min-width: 0 !important; }
  .ajax_table_print, .portfolio-table-responsive, .dataTables_wrapper { overflow-x: auto !important; -webkit-overflow-scrolling: touch; }
  #portfolio-table { min-width: 680px; }
  .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { float: none !important; text-align: left !important; width: 100% !important; margin: 8px 0 !important; }
  .dataTables_wrapper .dataTables_filter label { display: flex !important; flex-wrap: wrap !important; align-items: center; gap: 6px; }
  .dataTables_wrapper .dataTables_filter input { width: min(100%, 230px) !important; margin-left: 0 !important; }
  .dataTables_wrapper .dataTables_info { white-space: normal !important; padding-top: 10px !important; }
  .dataTables_wrapper .dataTables_paginate { float: none !important; text-align: left !important; white-space: normal !important; display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px; }
  .portfolio-page-title { font-size: 22px !important; line-height: 1.3; }
  .portfolio-page-desc { font-size: 13px !important; line-height: 1.5; }
  .content-body .btn, .content-body button, .content-body select { min-height: 40px; }
  .modal-dialog { margin: 10px !important; max-width: calc(100% - 20px) !important; }
}
</style>
