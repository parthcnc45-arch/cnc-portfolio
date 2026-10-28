<?php
include_once('header.php');
include_once('sidebar.php');
?>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.7.0/dist/multiple-select.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Include DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Include SheetJS for XLSX Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    /* =========================================================
       PAGE LAYOUT & HEADING (Reference Screenshot 2 Match)
       ========================================================= */
    .portfolio-page-body {
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

    /* Two Column Layout (Left 74%, Right 26%) */
    .portfolio-two-col-layout {
        display: flex;
        gap: 20px;
        align-items: flex-start;
        width: 100%;
    }
    .portfolio-main-column {
        flex: 1 1 calc(75% - 10px);
        min-width: 0;
    }
    .portfolio-side-column {
        flex: 0 0 calc(25% - 10px);
        max-width: calc(25% - 10px);
        min-width: 240px;
    }
    @media (max-width: 1180px) {
        .portfolio-two-col-layout {
            flex-direction: column;
        }
        .portfolio-side-column {
            flex: 1 1 100%;
            max-width: 100%;
            width: 100%;
        }
    }

    /* =========================================================
       SEARCH BAR & ADVANCED FILTERS
       ========================================================= */
    .modern-filter-wrapper {
        margin-bottom: 16px;
    }
    .modern-filter-bar {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .search-input-box {
        position: relative;
        flex: 1;
        height: 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
        display: flex;
        align-items: center;
        padding: 0 14px;
        gap: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .search-input-box:focus-within {
        border-color: #5b52f9;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12);
    }
    .search-input-box .search-icon {
        font-size: 14px;
        color: #64748b;
        pointer-events: none;
    }
    .search-input-box .search-input {
        border: none !important;
        outline: none !important;
        background: transparent !important;
        box-shadow: none !important;
        width: 100%;
        height: 100%;
        font-size: 13px;
        color: #1e293b;
        padding: 0 !important;
        margin: 0 !important;
    }
    .search-input-box .search-input::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    /* Search History Dropdown */
    .search-history-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
        z-index: 1060;
        overflow: hidden;
    }
    .history-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px 8px 14px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .history-dropdown-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    .btn-clear-history {
        background: none;
        border: none;
        color: #5b52f9;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.15s ease;
    }
    .btn-clear-history:hover {
        background: rgba(91, 82, 249, 0.08);
        color: #4338ca;
    }
    .history-items-container {
        max-height: 280px;
        overflow-y: auto;
        padding: 4px 0;
    }
    .history-items-container::-webkit-scrollbar {
        width: 5px;
    }
    .history-items-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .history-item {
        display: flex;
        align-items: center;
        padding: 9px 14px;
        cursor: pointer;
        transition: background-color 0.15s ease, color 0.15s ease;
        gap: 10px;
        font-size: 13px;
        color: #1e293b;
        user-select: none;
    }
    .history-item:hover {
        background-color: #f8fafc;
        color: #5b52f9;
    }
    .history-item-icon {
        font-size: 12px;
        color: #94a3b8;
        width: 16px;
        text-align: center;
        transition: color 0.15s ease;
    }
    .history-item:hover .history-item-icon {
        color: #5b52f9;
    }
    .history-item-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 500;
    }
    .history-item-arrow {
        font-size: 11px;
        color: #cbd5e1;
        transition: transform 0.15s ease, color 0.15s ease;
    }
    .history-item:hover .history-item-arrow {
        color: #5b52f9;
        transform: translateX(2px);
    }
    .history-empty-state {
        padding: 18px 14px;
        text-align: center;
        font-size: 13px;
        color: #94a3b8;
        font-style: italic;
    }
    .filter-actions-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-filter-action {
        height: 40px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
        outline: none !important;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-search-primary {
        background: #5b52f9;
        color: #ffffff !important;
        border: none;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.3);
    }
    .btn-search-primary:hover {
        background: #4940e8;
        box-shadow: 0 4px 10px rgba(91, 82, 249, 0.38);
    }
    .btn-advanced-toggle {
        background: #ffffff;
        color: #5b52f9 !important;
        border: 1.5px solid #5b52f9;
    }
    .btn-advanced-toggle:hover {
        background: #f5f3ff;
    }
    .btn-advanced-toggle.active {
        background: #f0efff;
        border-color: #5b52f9;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12);
    }
    .btn-clear-filters {
        background: #ffffff;
        color: #334155 !important;
        border: 1.5px solid #e2e8f0;
    }
    .btn-clear-filters:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Advanced Filters Collapsible Section */
    .advanced-filters-panel {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        margin-top: 10px;
        padding: 18px 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        z-index: 5;
    }
    .advanced-filters-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
    }
    @media (max-width: 1200px) {
        .advanced-filters-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 768px) {
        .advanced-filters-grid {
            grid-template-columns: 1fr;
        }
        .modern-filter-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-actions-group {
            flex-wrap: wrap;
        }
        .filter-actions-group .btn-filter-action {
            flex: 1;
        }
    }
    .adv-filter-col label {
        font-size: 12.5px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
        display: block;
    }
    .advanced-filters-footer {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .btn-export, .btn-import {
        height: 30px !important;
        padding: 0 12px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        border-radius: 6px !important;
        background-color: #5b52f9 !important;
        border: 1px solid #5b52f9 !important;
        color: #ffffff !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        white-space: nowrap !important;
        line-height: normal !important;
        margin: 0 !important;
    }
    .btn-export:hover, .btn-import:hover {
        background-color: #483fe6 !important;
        border-color: #483fe6 !important;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.35) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    .select2-container {
        width: 100% !important;
    }
    .select2-selection__clear,
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        display: none !important;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 38px !important;
        height: auto !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 2px 6px !important;
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 3px !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        line-height: normal !important;
    }
    .select2-container--default .select2-selection--multiple .select2-search--inline {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-grow: 1 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
        height: 30px !important;
        line-height: 30px !important;
        margin: 0 !important;
        padding: 0 4px !important;
        font-size: 13px !important;
        color: #1e293b !important;
        width: 100% !important;
        border: none !important;
        outline: none !important;
        background: transparent !important;
    }
    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
        color: #94a3b8 !important;
        font-size: 12.5px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #5b52f9 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 4px !important;
        padding: 2px 6px !important;
        font-size: 12px !important;
        display: inline-flex !important;
        align-items: center !important;
        margin: 2px 0 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
        margin-right: 4px !important;
        font-weight: bold !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5b52f9 !important;
        color: #fff !important;
    }

    /* =========================================================
       MODERN PORTFOLIO TABLE CARD & STYLES (Target Screenshot 2)
       ========================================================= */
    .portfolio-table-card {
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden !important;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
        height: 580px;
        display: flex;
        flex-direction: column;
    }

    .portfolio-table-responsive {
        width: 100% !important;
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }

    #portfolio-table_wrapper,
    .portfolio-table-card .dataTables_wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden !important;
    }
    #portfolio-table_wrapper::after {
        display: none !important;
    }

    /* Top Table Controls Toolbar (Pinned at top of card) */
    .portfolio-table-toolbar {
        flex: 0 0 auto;
        padding: 10px 14px 8px 14px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        z-index: 20;
    }

    /* Pagination / Length dropdown hidden */
    #portfolio-table_wrapper .dataTables_length {
        display: none !important;
    }

    /* Right controls in header toolbar: Multiple Delete & Status */
    #portfolio-table_wrapper .dataTables_filter {
        float: none !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 8px !important;
        height: 32px !important;
        width: auto !important;
    }
    #portfolio-table_wrapper .dataTables_filter label {
        display: none !important;
    }

    /* Scrollable Table Body Area */
    .portfolio-table-scroll {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto !important;
        overflow-x: auto !important;
        width: 100%;
        position: relative;
    }

    /* Modern Sleek Scrollbar */
    .portfolio-table-scroll::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }
    .portfolio-table-scroll::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    .portfolio-table-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .portfolio-table-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Status Dropdown */
    .custom-status-select {
        height: 30px !important;
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
    .custom-status-select:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Multiple Delete Button */
    .multi-delete-btn {
        height: 30px !important;
        padding: 0 12px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        background-color: #ff5e5e !important;
        border: 1px solid #ff5e5e !important;
        color: #ffffff !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        white-space: nowrap !important;
        line-height: normal !important;
        margin: 0 !important;
    }
    .multi-delete-btn:hover {
        background-color: #ff3b3b !important;
        border-color: #ff2a2a !important;
        box-shadow: 0 2px 6px rgba(255, 94, 94, 0.35) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    /* Main Table Element */
    #portfolio-table {
        table-layout: fixed !important;
        width: 100% !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: #ffffff !important;
    }

    /* Header Styling - Sticky at top of scroll container */
    #portfolio-table thead th {
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
        background-color: #f9f9fd !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: 0.2px !important;
        padding: 9px 8px !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1.5px solid #edf0f7 !important;
        box-shadow: 0 1.5px 0 #edf0f7 !important;
        white-space: nowrap !important;
    }

    /* Row & Body Cells */
    #portfolio-table tbody td {
        padding: 8px 8px !important;
        vertical-align: middle !important;
        font-size: 12.5px !important;
        color: #334155 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        background-color: #ffffff !important;
        line-height: 1.35 !important;
        transition: background-color 0.15s ease !important;
    }
    #portfolio-table tbody tr:hover td {
        background-color: #fbfbfe !important;
    }
    #portfolio-table tbody tr:last-child td {
        border-bottom: none !important;
    }
    #portfolio-table tbody tr.selected-row td {
        background-color: #f2f1ff !important;
        border-bottom-color: #e4e2fd !important;
    }

    /* Specific Columns Width & Ellipsis */
    #portfolio-table th.col-checkbox,
    #portfolio-table td.col-checkbox {
        width: 30px !important;
        max-width: 30px !important;
        min-width: 30px !important;
        text-align: center !important;
        padding: 8px 2px !important;
    }
    #portfolio-table input[type="checkbox"] {
        cursor: pointer !important;
        width: 14px !important;
        height: 14px !important;
        accent-color: #5b52f9 !important;
        vertical-align: middle !important;
        margin: 0 !important;
    }
    #portfolio-table th.col-company,
    #portfolio-table td.company_name {
        width: 16% !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        padding: 8px 4px !important;
    }
    #portfolio-table th.col-website,
    #portfolio-table td.col-website {
        width: 17% !important;
        padding: 8px 4px !important;
    }
    #portfolio-table th.col-country {
        width: 9% !important;
        color: #475569 !important;
        padding: 8px 3px !important;
        white-space: nowrap !important;
    }
    #portfolio-table td.col-country {
        width: 9% !important;
        color: #334155 !important;
        padding: 6px 3px !important;
        vertical-align: middle !important;
    }
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
        gap: 4px;
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
        max-width: calc(100% - 24px);
    }
    #portfolio-table th.col-industries,
    #portfolio-table td.col-industries {
        width: 12% !important;
        padding: 8px 4px !important;
    }
    #portfolio-table th.col-technologies,
    #portfolio-table td.col-technologies {
        width: 15% !important;
        padding: 8px 4px !important;
    }
    #portfolio-table th.col-cms,
    #portfolio-table td.col-cms {
        width: 11% !important;
        padding: 8px 4px !important;
    }
    #portfolio-table th.col-actions,
    #portfolio-table td.col-actions {
        width: 132px !important;
        min-width: 132px !important;
        max-width: 132px !important;
        padding: 8px 20px 8px 4px !important;
        text-align: center !important;
    }

    /* Website URL link styling */
    .website-url-wrapper {
        display: flex;
        align-items: center;
        max-width: 145px;
        min-width: 0;
    }
    .website-url-link {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        color: #5b52f9 !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        max-width: 100% !important;
        min-width: 0 !important;
        transition: all 0.15s ease !important;
        font-size: 12.5px !important;
    }
    .website-url-text {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        min-width: 0 !important;
        flex: 1 1 auto !important;
    }
    .external-icon-wrap {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        color: #5b52f9 !important;
        transition: transform 0.15s ease, color 0.15s ease !important;
    }
    .website-url-link:hover {
        color: #3b30e8 !important;
        text-decoration: none !important;
    }
    .website-url-link:hover .website-url-text {
        text-decoration: underline !important;
    }
    .website-url-link:hover .external-icon-wrap {
        transform: translate(1px, -1px);
    }

    /* Badges for Industries, Technologies, CMS */
    .badge-cluster {
        display: flex;
        flex-wrap: nowrap !important;
        gap: 3px;
        align-items: center;
        overflow: hidden;
    }
    .portfolio-badge {
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        font-size: 11px;
        font-weight: 500;
        border-radius: 5px;
        padding: 2px 6px;
        line-height: 1.25;
        white-space: nowrap;
        transition: all 0.15s ease;
        max-width: 125px;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
    }
    .badge-industry {
        background-color: #eef2ff;
        color: #5b52f9;
        border: 1px solid #e0e7ff;
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
    .badge-more {
        background-color: #ede9fe;
        color: #5b52f9;
        font-weight: 600;
        cursor: pointer;
        padding: 2px 4px;
    }

    /* Inline Action Buttons (Copy, View, 3-dots) */
    .table-actions-inline {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        white-space: nowrap;
        justify-content: center;
    }
    .btn-table-action {
        height: 23px;
        border-radius: 4px;
        padding: 0 4px;
        font-size: 10.5px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.15s ease;
        line-height: 1;
        border: none;
        outline: none !important;
    }
    .btn-table-action svg {
        width: 11px;
        height: 11px;
    }
    .btn-table-copy {
        background-color: #5950f6;
        color: #ffffff !important;
        box-shadow: 0 1px 2px rgba(89, 80, 246, 0.25);
    }
    .btn-table-copy:hover {
        background-color: #453be4;
        box-shadow: 0 2px 5px rgba(89, 80, 246, 0.35);
        transform: translateY(-1px);
    }
    .btn-table-copy.copied {
        background-color: #10b981 !important;
        box-shadow: 0 1px 2px rgba(16, 185, 129, 0.25);
        pointer-events: none;
    }
    .btn-table-copy.copied:hover {
        background-color: #059669 !important;
        box-shadow: 0 2px 5px rgba(16, 185, 129, 0.35);
        transform: none;
    }
    .btn-table-copy.disabled {
        background-color: #cbd5e1 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        opacity: 0.7;
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
    .action-dots-btn {
        width: 18px;
        height: 23px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid transparent;
        background: transparent;
        color: #64748b;
        transition: all 0.15s ease;
        cursor: pointer;
        outline: none !important;
        flex-shrink: 0;
    }
    .action-dots-btn:hover,
    .action-dots-btn.active {
        background-color: #f1f5f9;
        color: #5b52f9;
        border-color: #e2e8f0;
    }

    /* Table Footer & Pagination - Hidden */
    #portfolio-table_wrapper .dataTables_info,
    #portfolio-table_wrapper .dataTables_paginate {
        display: none !important;
    }
    #portfolio-table_paginate ul.pagination {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 3px !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        border: none !important;
        box-shadow: none !important;
    }
    #portfolio-table_paginate .paginate_button,
    #portfolio-table_paginate .page-item {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        outline: none !important;
    }
    #portfolio-table_paginate .paginate_button a,
    #portfolio-table_paginate .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 28px !important;
        height: 28px !important;
        padding: 0 7px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        margin: 0 !important;
        text-decoration: none !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        outline: none !important;
        box-shadow: none !important;
        -webkit-tap-highlight-color: transparent !important;
    }
    #portfolio-table_paginate .paginate_button a:hover,
    #portfolio-table_paginate .page-link:hover {
        background-color: #f5f3ff !important;
        border-color: #c7d2fe !important;
        color: #5b52f9 !important;
        outline: none !important;
        box-shadow: none !important;
    }
    #portfolio-table_paginate .paginate_button a:focus,
    #portfolio-table_paginate .paginate_button a:focus-visible,
    #portfolio-table_paginate .paginate_button a:active,
    #portfolio-table_paginate .page-link:focus,
    #portfolio-table_paginate .page-link:focus-visible,
    #portfolio-table_paginate .page-link:active {
        outline: none !important;
        box-shadow: none !important;
        border-color: #c7d2fe !important;
        color: #5b52f9 !important;
    }
    #portfolio-table_paginate .page-item.active a,
    #portfolio-table_paginate .page-item.active .page-link,
    #portfolio-table_paginate .paginate_button.active a,
    #portfolio-table_paginate .paginate_button.current,
    #portfolio-table_paginate .paginate_button.current a {
        background: #5b52f9 !important;
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(91, 82, 249, 0.3) !important;
        outline: none !important;
    }
    #portfolio-table_paginate .page-item.active a:focus,
    #portfolio-table_paginate .page-item.active .page-link:focus,
    #portfolio-table_paginate .page-item.active a:active,
    #portfolio-table_paginate .page-item.active .page-link:active {
        background: #5b52f9 !important;
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #ffffff !important;
        outline: none !important;
        box-shadow: 0 2px 4px rgba(91, 82, 249, 0.3) !important;
    }
    #portfolio-table_paginate .page-item.disabled a,
    #portfolio-table_paginate .page-item.disabled .page-link,
    #portfolio-table_paginate .paginate_button.disabled,
    #portfolio-table_paginate .paginate_button.disabled a {
        color: #cbd5e1 !important;
        background-color: #fafbfc !important;
        border-color: #edf0f5 !important;
        cursor: not-allowed !important;
        outline: none !important;
        box-shadow: none !important;
    }

    /* =========================================================
       RECENTLY COPIED LINKS PANEL (Target Screenshot 2)
       ========================================================= */
    .recently-copied-panel {
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 12px;
        padding: 15px 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        position: sticky;
        top: 20px;
    }
    .recent-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 11px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 3px;
    }
    .recent-header-title {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }
    .recent-header-title i {
        font-size: 13.5px;
        color: #64748b;
    }
    .recent-clear-btn {
        font-size: 11.5px;
        font-weight: 600;
        color: #5b52f9;
        text-decoration: none !important;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .recent-clear-btn:hover {
        color: #4338ca;
        text-decoration: underline !important;
    }
    .recent-copied-list {
        display: flex;
        flex-direction: column;
    }
    .recent-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        gap: 8px;
    }
    .recent-item:last-child {
        border-bottom: none;
    }
    .recent-item-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        flex: 1;
    }
    .recent-num-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: #eef2ff;
        color: #5b52f9;
        font-weight: 600;
        font-size: 11.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .recent-item-info {
        min-width: 0;
        flex: 1;
    }
    .recent-item-name {
        font-size: 12.5px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
    }
    .recent-item-url {
        font-size: 11px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
        margin-top: 2px;
    }
    .recent-copy-btn {
        width: 26px;
        height: 26px;
        border-radius: 5px;
        border: none;
        background: transparent;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
        outline: none !important;
    }
    .recent-copy-btn:hover {
        background-color: #f1f5f9;
        color: #5b52f9;
    }
    .recent-copy-btn.copied {
        background-color: #dcfce7;
        color: #059669;
        pointer-events: none;
    }
    .empty-recent-state {
        text-align: center;
        padding: 22px 10px;
    }

    /* Floating Actions Dropdown Menu */
    .action-dropdown-menu {
        position: fixed;
        z-index: 99999;
        min-width: 145px;
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 12px;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.12), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 6px 0;
        animation: fadeInDropdown 0.15s ease-out;
    }
    @keyframes fadeInDropdown {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .action-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 8px 16px;
        font-size: 13.5px;
        font-weight: 500;
        color: #4b5563;
        text-decoration: none !important;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: left;
    }
    .action-dropdown-item:hover {
        background-color: #f8fafc;
        color: #111827;
    }
    .action-dropdown-item i {
        font-size: 14px;
        width: 16px;
        text-align: center;
        color: #64748b;
    }
    .action-dropdown-item:hover i {
        color: #334155;
    }
    .action-dropdown-divider {
        height: 1px;
        background-color: #f1f5f9;
        margin: 4px 0;
    }
    .action-dropdown-item.text-danger {
        color: #ef4444 !important;
    }
    .action-dropdown-item.text-danger i {
        color: #ef4444 !important;
    }
    .action-dropdown-item.text-danger:hover {
        background-color: #fef2f2 !important;
        color: #dc2626 !important;
    }
</style>

<div class="content-body portfolio-page-body">
    <div class="container-fluid px-4 py-3">
        <!-- Top Heading & Description Matching Screenshot 2 -->
        <div class="portfolio-page-header">
            <div class="portfolio-header-left">
                <div class="portfolio-header-title-wrap">
                    <h1 class="portfolio-page-title">
                        Portfolio <span class="portfolio-count-badge">(<span id="portfolio-total-count"><?php 
                            $count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM portfolio WHERE `deleted`='0'");
                            $count_row = mysqli_fetch_assoc($count_query);
                            echo $count_row['total'] ?? '156';
                        ?></span> found)</span>
                    </h1>
                </div>
                <p class="portfolio-page-desc">Find and manage all your projects. Search, copy links and use them while applying for jobs.</p>
            </div>
            <div class="portfolio-header-right">
                <a href="index.php" class="btn-add-portfolio" id="btnAddPortfolio">
                    <i class="fas fa-plus"></i>
                    <span>Add Portfolio</span>
                </a>
            </div>
        </div>

        <!-- Two Column Main Layout Matching Screenshot 2 -->
        <div class="portfolio-two-col-layout">
            <!-- Left Section (Approx 74% width) -->
            <div class="portfolio-main-column">
                <div id="ajax_table_print">
                    <?php
                        $result = mysqli_query($conn,"SELECT * FROM portfolio WHERE `deleted`='0' ORDER BY timestamp DESC");
                        include 'portfolios-table.php';
                    ?>
                </div>
            </div>

            <!-- Right Section (Approx 26% width) - Recently Copied Links -->
            <div class="portfolio-side-column">
                <div class="recently-copied-panel">
                    <div class="recent-panel-header">
                        <div class="recent-header-title">
                            <i class="far fa-clock"></i>
                            <span>Recently Copied Links</span>
                        </div>
                        <?php
                        $currentUserId = getCurrentAuthUserId($conn);
                        $recentCopiedLinks = $currentUserId ? fetchUserRecentCopiedLinks($conn, $currentUserId) : [];
                        $hasRecentLinks = !empty($recentCopiedLinks);
                        ?>
                        <a href="javascript:void(0);" id="clearRecentlyCopied" class="recent-clear-btn" style="<?php echo $hasRecentLinks ? '' : 'display: none;'; ?>">Clear All</a>
                    </div>
                    <div class="recent-copied-list" id="recentCopiedList">
                        <?php if ($hasRecentLinks): ?>
                            <?php foreach ($recentCopiedLinks as $idx => $link): ?>
                                <div class="recent-item">
                                    <div class="recent-item-left">
                                        <div class="recent-num-badge"><?php echo ($idx + 1); ?></div>
                                        <div class="recent-item-info">
                                            <div class="recent-item-name" title="<?php echo htmlspecialchars($link['company_name']); ?>"><?php echo htmlspecialchars($link['company_name']); ?></div>
                                            <div class="recent-item-url" title="<?php echo htmlspecialchars($link['website_url']); ?>"><?php echo htmlspecialchars($link['website_url']); ?></div>
                                        </div>
                                    </div>
                                    <button type="button" class="recent-copy-btn" data-id="<?php echo (int)$link['portfolio_id']; ?>" data-url="<?php echo htmlspecialchars($link['website_url']); ?>" title="Copy link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-recent-state">
                                <i class="far fa-clipboard" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"></i>
                                <p style="font-size: 12.5px; color: #94a3b8; margin: 0;">No recently copied links yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Favourite Websites & Designs Section (Preserved) -->
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="card" style="border-radius: 12px; border: 1.5px solid #e8ecf4; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                    <div class="card-body">
                        <h4 style="font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">Favourite Websites</h4>
                        <div class="form-group position-relative">
                            <?php
                            $result = mysqli_query($conn,"SELECT * FROM favourite WHERE `name`='websites' ORDER BY timestamp DESC");
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <textarea class="form-control fav_change_inp" id="fav_websites" rows="6" placeholder="Enter Websites Link Here."><?php echo $row['value'] ?? '';?></textarea>
                            <a href="javascript:void(0);" onclick="copyToClipboard(this)" class="copy_links_i" title="Click to copy."><i class="icon-notebook icons"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card" style="border-radius: 12px; border: 1.5px solid #e8ecf4; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                    <div class="card-body">
                        <h4 style="font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">Favourite Designs</h4>
                        <div class="form-group position-relative">
                            <?php
                            $result = mysqli_query($conn,"SELECT * FROM favourite WHERE `name`='designs' ORDER BY timestamp DESC");
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <textarea class="form-control fav_change_inp" id="fav_designs" rows="6" placeholder="Enter Designs Link Here."><?php echo $row['value'] ?? '';?></textarea>
                            <a href="javascript:void(0);" onclick="copyToClipboard(this)" class="copy_links_i" title="Click to copy."><i class="icon-notebook icons"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Delete Confirmation (Preserved) -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="deleteModalBody" style="color: #475569; font-size: 14px;">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete" data-type="" data-id="" style="border-radius: 6px;">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Actions Dropdown Menu (Preserved) -->
<div id="actionDropdownMenu" class="action-dropdown-menu" style="display: none;">
    <a id="actionDropdownEdit" href="#" class="action-dropdown-item">
        <i class="far fa-edit"></i>
        <span>Edit</span>
    </a>
    <div class="action-dropdown-divider"></div>
    <button type="button" id="actionDropdownDelete" class="action-dropdown-item text-danger" data-id="">
        <i class="far fa-trash-alt"></i>
        <span>Delete</span>
    </button>
</div>

<?php
include_once('footer.php');
?>

<script>
    function insertTableToolbarButtons() {
        var $filter = $('#portfolio-table_wrapper .dataTables_filter');
        if ($filter.length === 0) return;

        var exportBtnHtml = '<button type="button" id="exportSelected" class="btn btn-export"><i class="fas fa-file-export mr-1"></i> Export Selected</button>';
        var importInputHtml = '<input type="file" id="importFile" accept=".xlsx" style="display: none;" />';
        var importBtnHtml = '<button type="button" id="importSelected" class="btn btn-import"><i class="fas fa-file-import mr-1"></i> Import&nbsp;<img class="loader-btn" src="/images/loader1.gif" alt="loader" style="width: 16px;display:none;"></button>';
        var multiDelBtnHtml = '<button type="button" id="deleteSelected" class="btn btn-danger multi-delete-btn"><i class="far fa-trash-alt"></i> Multiple Delete</button>';

        if ($('#importFile').length === 0) {
            $('body').append(importInputHtml);
        }

        var $status = $filter.find('#portfolio_status');

        if ($('#exportSelected').length === 0) {
            $filter.append(exportBtnHtml);
        }
        if ($('#importSelected').length === 0) {
            $filter.append(importBtnHtml);
        }
        if ($('#deleteSelected').length === 0) {
            $filter.append(multiDelBtnHtml);
        }

        var $export = $('#exportSelected');
        var $import = $('#importSelected');
        var $delete = $('#deleteSelected');

        if ($status.length > 0) {
            $export.insertBefore($status);
            $import.insertBefore($status);
            $delete.insertBefore($status);
        } else {
            $filter.append($export).append($import).append($delete);
        }
    }

    function insertMultiDeleteButton() {
        insertTableToolbarButtons();
    }

    function copyToClipboard(clickedButton) {
        var textarea = $(clickedButton).parent().find('textarea');
        textarea.select();
        document.execCommand('copy');
        Toastify({
            text: 'Copied to clipboard!',
            duration: 3000,
            close: true,
            gravity: 'top-right',
            style: {
                background: "linear-gradient(to right, #10b981, #059669)",
            },
            backgroundColor: '#10b981',
        }).showToast();
    }

    function copyTextToClipboard(text, onSuccess) {
        if (!text) return;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showToastFeedback();
                if (typeof onSuccess === 'function') onSuccess();
            }).catch(function() {
                fallbackCopyText(text, onSuccess);
            });
        } else {
            fallbackCopyText(text, onSuccess);
        }
    }

    function fallbackCopyText(text, onSuccess) {
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        try {
            var successful = document.execCommand('copy');
            tempInput.remove();
            if (successful) {
                showToastFeedback();
                if (typeof onSuccess === 'function') onSuccess();
            }
        } catch (err) {
            tempInput.remove();
        }
    }

    function showToastFeedback() {
        if (typeof Toastify === 'function') {
            Toastify({
                text: 'Copied to clipboard!',
                duration: 3000,
                close: true,
                gravity: 'top-right',
                style: {
                    background: "linear-gradient(to right, #10b981, #059669)",
                },
                backgroundColor: '#10b981',
            }).showToast();
        }
    }

    $(document).ready(function() {
        function updateSelectedRowStyles() {
            $('.rowCheckbox').each(function() {
                var isChecked = $(this).is(':checked');
                $(this).closest('tr').toggleClass('selected-row', isChecked);
            });
        }

        $(document).on("click change", "#selectAll", function() {
            var isChecked = this.checked;
            $(".rowCheckbox").prop("checked", isChecked);
            updateSelectedRowStyles();
        });

        $(document).on("change", ".rowCheckbox", function() {
            var isChecked = $(this).is(':checked');
            $(this).closest('tr').toggleClass('selected-row', isChecked);

            var total = $('.rowCheckbox').length;
            var checked = $('.rowCheckbox:checked').length;
            $('#selectAll').prop('checked', total > 0 && total === checked);
        });

        updateSelectedRowStyles();

        // Export Selected Rows
        $(document).on("click", "#exportSelected", function() {
            var selectedIds = [];
            $('.rowCheckbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert("Please select at least one row to export.");
                return;
            }

            $.ajax({
                url: 'export.php',
                type: 'POST',
                dataType: 'json',
                data: { ids: selectedIds },
                success: function (response) {
                    if (response.status === 'success') {
                        var downloadUrl = 'exports/' + response.file;
                        window.location.href = downloadUrl;
                    } else {
                        alert('Failed to export: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error exporting data.');
                }
            });
        });

        // Header small download/export button
        $(document).on('click', '#headerExportBtn', function(e) {
            e.preventDefault();
            $("#exportSelected").trigger('click');
        });

        // Import
        $(document).on("click", "#importSelected", function () {
            $("#importFile").click();
        });

        $(document).on("change", "#importFile", function () {
            $('#importSelected .loader-btn').show();
            $('#importSelected').prop('disabled', true).css('cursor', 'not-allowed');
            var file = this.files[0];
            if (!file) return;

            var formData = new FormData();
            formData.append("file", file);
            
            $.ajax({
                url: "import.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#importSelected .loader-btn').hide();
                    $('#importSelected').prop('disabled', false).css('cursor', 'pointer');
                    alert("Import success: " + response.message);
                    window.location.reload();
                },
                error: function () {
                    $('#importSelected .loader-btn').hide();
                    $('#importSelected').prop('disabled', false).css('cursor', 'pointer');
                    alert("Import failed.");
                }
            });
        });

        // Initialize Select2 filter dropdowns
        function initSelect2Filters() {
            $("#filter-country").select2({ placeholder: "Select Country", width: '100%' });
            $("#filter-type").select2({ placeholder: "Select Type", width: '100%' });
            $("#filter-industries").select2({ placeholder: "Select Industries", width: '100%' });
            $("#filter-prog_lang").select2({ placeholder: "Select Programming Language", width: '100%' });
            $("#filter-framework-portfolioedit").select2({ placeholder: "Select Framework", width: '100%' });
        }
        initSelect2Filters();

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
                success: function(response) {},
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

        // 3-Dots Action Dropdown Menu
        var activeActionBtn = null;

        $(document).on('click', '.action-dots-btn', function(e) {
            e.stopPropagation();
            var $btn = $(this);
            var id = $btn.attr('data-id');

            if (activeActionBtn && activeActionBtn[0] === $btn[0] && $('#actionDropdownMenu').is(':visible')) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
                return;
            }

            $('.action-dots-btn').removeClass('active');
            $btn.addClass('active');
            activeActionBtn = $btn;

            $('#actionDropdownEdit').attr('href', 'edit-portfolio.php?id=' + id);
            $('#actionDropdownDelete').attr('data-id', id);

            var $menu = $('#actionDropdownMenu');
            $menu.show();

            var rect = this.getBoundingClientRect();
            var menuWidth = $menu.outerWidth();
            var menuHeight = $menu.outerHeight();

            var left = rect.right - menuWidth;
            var top = rect.bottom + 4;

            if (rect.bottom + menuHeight > window.innerHeight) {
                top = rect.top - menuHeight - 4;
            }

            $menu.css({
                top: top + 'px',
                left: left + 'px'
            });
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#actionDropdownMenu, .action-dots-btn').length) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
            }
        });

        $(window).on('scroll resize', function() {
            if ($('#actionDropdownMenu').is(':visible')) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
            }
        });

        $(document).on('scroll', '.portfolio-table-scroll', function() {
            if ($('#actionDropdownMenu').is(':visible')) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
            }
        });

        $(document).on('click', '#actionDropdownEdit', function() {
            $('#actionDropdownMenu').hide();
            $('.action-dots-btn').removeClass('active');
            activeActionBtn = null;
        });

        $(document).on('click', '#actionDropdownDelete', function(e) {
            e.preventDefault();
            var _id = $(this).attr('data-id');
            $('#actionDropdownMenu').hide();
            $('.action-dots-btn').removeClass('active');
            activeActionBtn = null;

            $('#confirmDelete')
                .attr('data-id', _id)
                .attr('data-type', 'single');
            $('#deleteModalBody').text('Are you sure you want to delete this portfolio?');
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on('click', '.delete-btn', function() {
            var _id = $(this).attr('data-id');
            $('#confirmDelete')
                .attr('data-id', _id)
                .attr('data-type', 'single');
            $('#deleteModalBody').text('Are you sure you want to delete this portfolio?');
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on("click", "#deleteSelected", function () {
            var selectedIds = [];
            $('.rowCheckbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert("Please select at least one row to delete.");
                return;
            }

            $('#confirmDelete')
                .attr('data-id', selectedIds.join(','))
                .attr('data-type', 'multi');
            $('#deleteModalBody').text('Are you sure you want to delete the selected portfolios?');
            $('#deleteConfirmationModal').modal('show');
        });

        $(document).on('click', '#confirmDelete', function () {
            var type = $(this).attr('data-type');
            var ids = $(this).attr('data-id');

            if (type === 'single') {
                $.ajax({
                    url: "ajax_functions.php",
                    type: "POST",
                    data: {
                        action: 'delet_portfolio',
                        portfolio_id: ids
                    },
                    success: function (response) {
                        $('#deleteConfirmationModal').modal('hide');
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    },
                    error: function (xhr) {
                        console.error("AJAX Error", xhr.responseText);
                    }
                });
            } else if (type === 'multi') {
                $.ajax({
                    url: 'ajax_functions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'multi_delete_portfolio',
                        ids: ids.split(',')
                    },
                    success: function (response) {
                        $('#deleteConfirmationModal').modal('hide');
                        if (response.success) {                           
                            window.location.reload();
                        } else {
                            alert(response.message || "Delete failed.");
                        }
                    },
                    error: function (xhr) {
                        alert("AJAX error: " + xhr.responseText);
                    }
                });
            }
        });

        // =========================================================
        // DYNAMIC RECENTLY COPIED LINKS
        // =========================================================
        function escapeHtml(text) {
            if (!text) return '';
            return $('<div>').text(text).html();
        }

        function renderRecentlyCopiedList(links) {
            var $list = $('#recentCopiedList');
            var $clearBtn = $('#clearRecentlyCopied');
            $list.empty();

            if (!links || links.length === 0) {
                $list.html(
                    '<div class="empty-recent-state">' +
                        '<i class="far fa-clipboard" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px;"></i>' +
                        '<p style="font-size: 12.5px; color: #94a3b8; margin: 0;">No recently copied links yet</p>' +
                    '</div>'
                );
                $clearBtn.hide();
                return;
            }

            $clearBtn.show();

            links.forEach(function(item, idx) {
                var num = idx + 1;
                var compName = escapeHtml(item.company_name || 'Untitled Project');
                var webUrl = escapeHtml(item.website_url || '');
                var rawUrl = item.website_url || '';
                var portId = item.portfolio_id;

                var html = 
                    '<div class="recent-item">' +
                        '<div class="recent-item-left">' +
                            '<div class="recent-num-badge">' + num + '</div>' +
                            '<div class="recent-item-info">' +
                                '<div class="recent-item-name" title="' + compName + '">' + compName + '</div>' +
                                '<div class="recent-item-url" title="' + webUrl + '">' + webUrl + '</div>' +
                            '</div>' +
                        '</div>' +
                        '<button type="button" class="recent-copy-btn" data-id="' + portId + '" data-url="' + rawUrl + '" title="Copy link">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                '<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>' +
                                '<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>' +
                            '</svg>' +
                        '</button>' +
                    '</div>';

                $list.append(html);
            });
        }

        function recordPortfolioCopy(portfolioId) {
            if (!portfolioId) return;
            $.ajax({
                url: 'ajax_functions.php',
                type: 'POST',
                data: {
                    action: 'record_copy_history',
                    portfolio_id: portfolioId
                },
                dataType: 'json',
                success: function(res) {
                    if (res && res.success && res.history) {
                        renderRecentlyCopiedList(res.history);
                    }
                }
            });
        }

        // Table Row Copy Button
        $(document).on('click', '.btn-table-copy', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(this);
            if ($btn.hasClass('copied') || $btn.hasClass('disabled')) {
                return;
            }
            var url = $btn.attr('data-url');
            var portId = $btn.attr('data-id');
            if (!portId) {
                portId = $btn.closest('tr').find('.rowCheckbox').val();
            }
            var originalHtml = $btn.html();
            copyTextToClipboard(url, function() {
                $btn.addClass('copied');
                $btn.html('<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Copied</span>');
                setTimeout(function() {
                    $btn.removeClass('copied');
                    $btn.html(originalHtml);
                }, 3000);
                if (portId) {
                    recordPortfolioCopy(portId);
                }
            });
        });

        // Recently Copied Links Copy Button
        $(document).on('click', '.recent-copy-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(this);
            if ($btn.hasClass('copied')) {
                return;
            }
            var url = $btn.attr('data-url');
            var portId = $btn.attr('data-id');
            var originalHtml = $btn.html();
            copyTextToClipboard(url, function() {
                $btn.addClass('copied');
                $btn.html('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>');
                setTimeout(function() {
                    $btn.removeClass('copied');
                    $btn.html(originalHtml);
                }, 3000);
                if (portId) {
                    recordPortfolioCopy(portId);
                }
            });
        });

        // Clear All in Recently Copied Panel
        $(document).on('click', '#clearRecentlyCopied', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'ajax_functions.php',
                type: 'POST',
                data: { action: 'clear_copy_history' },
                dataType: 'json',
                success: function(res) {
                    if (res && res.success) {
                        renderRecentlyCopiedList([]);
                        if (typeof Toastify === 'function') {
                            Toastify({
                                text: 'Recently copied links cleared',
                                duration: 3000,
                                close: true,
                                gravity: 'top-right',
                                backgroundColor: '#10b981',
                            }).showToast();
                        }
                    }
                },
                error: function() {
                    alert('Error clearing copy history.');
                }
            });
        });

        // Context menu copy
        $(document).on('contextmenu', '.copy-link', function(event) {
            event.preventDefault();
            var linkURL = $(this).attr('href');
            var portId = $(this).attr('data-id');
            if (!portId) {
                portId = $(this).closest('tr').find('.rowCheckbox').val();
            }
            copyTextToClipboard(linkURL, function() {
                if (portId) {
                    recordPortfolioCopy(portId);
                }
            });
        });

        // Prevent focus outline lingering on pagination buttons
        $(document).on('click', '#portfolio-table_paginate a, #portfolio-table_paginate .paginate_button', function() {
            $(this).blur();
        });

        // Initialize DataTable
        var table = $('#portfolio-table').DataTable({
            "autoWidth": false,
            "aaSorting": [],
            "paging": false,
            "info": false,
            dom: '<"portfolio-table-toolbar"f><"portfolio-table-scroll"t>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search portfolios..."
            },
            columnDefs: [
                { targets: [0, 7], orderable: false },
                { targets: 0, width: "30px" },
                { targets: 1, width: "16%" },
                { targets: 2, width: "17%" },
                { targets: 3, width: "9%" },
                { targets: 4, width: "12%" },
                { targets: 5, width: "15%" },
                { targets: 6, width: "11%" },
                { targets: 7, width: "132px" },
                { 
                    targets: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36],
                    visible: false
                }
            ],
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export Portfolio',
                    filename: 'Framework_Portfolio',
                    title: 'Framework Data Export',
                    exportOptions: {
                        columns: ':hidden'
                    }
                }
            ]
        });

        insertMultiDeleteButton();

        table.on('draw', function () {
            $('#portfolio-table').css('width', '100%');
            insertMultiDeleteButton();
            updateSelectedRowStyles();
            var filteredCount = table.rows({ search: 'applied' }).count();
            $('#portfolio-total-count').text(filteredCount);
        });

        // Function to populate filter options from table columns
        function populateFilterOptions(targetTable) {
            var countries = new Set();
            targetTable.column(3).data().each(function (value) {
                if (value && value !== '-') {
                    var text = $('<div>').html(value).text().trim();
                    if (text && text !== '-') {
                        var parts = text.split(',');
                        parts.forEach(function(p) {
                            p = p.trim();
                            if (p && p !== '-') countries.add(p);
                        });
                    }
                }
            });
            countries.forEach(function (country) {
                if (!$("#filter-country option[value='" + country + "']").length) {
                    $("#filter-country").append(`<option value="${country}">${country}</option>`);
                }
            });

            var frameworks = new Set();
            targetTable.column(9).data().each(function (value) {
                if (value && value !== '-') {
                    var text = $('<div>').html(value).text().trim();
                    if (text && text !== '-') {
                        var parts = text.split(',');
                        parts.forEach(function(p) {
                            p = p.trim();
                            if (p && p !== '-') frameworks.add(p);
                        });
                    }
                }
            });
            frameworks.forEach(function (fw) {
                if (!$("#filter-framework-portfolioedit option[value='" + fw + "']").length) {
                    $("#filter-framework-portfolioedit").append(`<option value="${fw}">${fw}</option>`);
                }
            });
        }

        populateFilterOptions(table);

        // Delegated Multi-select filters
        // Type -> Column 8
        $(document).on("change", "#filter-type", function () {
            var selectedTypes = $(this).val();
            if (selectedTypes && selectedTypes.length > 0) {
                var regex = selectedTypes.join("|");
                table.column(8).search(regex, true, false).draw();
            } else {
                table.column(8).search("").draw();
            }
        });

        // Country -> Column 3
        $(document).on("change", "#filter-country", function () {
            var selectedCountries = $(this).val();
            if (selectedCountries && selectedCountries.length > 0) {
                var regex = selectedCountries.join("|");
                table.column(3).search(regex, true, false).draw();
            } else {
                table.column(3).search("").draw();
            }
        });

        // Industries -> Column 4
        $(document).on("change", "#filter-industries", function () {
            var selectedIndustries = $(this).val();
            if (selectedIndustries && selectedIndustries.length > 0) {
                var regex = selectedIndustries.join("|");
                table.column(4).search(regex, true, false).draw();
            } else {
                table.column(4).search("").draw();
            }
        });

        // Programming Language -> Column 15
        $(document).on("change", "#filter-prog_lang", function () {
            var selectedProgLang = $(this).val();
            if (selectedProgLang && selectedProgLang.length > 0) {
                var regex = selectedProgLang.join("|");
                table.column(15).search(regex, true, false).draw();
            } else {
                table.column(15).search("").draw();
            }
        });

        // Framework -> Column 9
        $(document).on("change", "#filter-framework-portfolioedit", function () {
            var selectedFrameworks = $(this).val();
            if (selectedFrameworks && selectedFrameworks.length > 0) {
                var regex = selectedFrameworks.join("|");
                table.column(9).search(regex, true, false).draw();
            } else {
                table.column(9).search("").draw();
            }
        });

        // =========================================================
        // SEARCH HISTORY & GLOBAL SEARCH
        // =========================================================
        var searchHistoryList = [];
        var isSearchHistoryLoaded = false;
        var $searchDropdown = $('#search-history-dropdown');
        var $historyContainer = $('#history-items-container');

        function renderSearchHistory(items) {
            searchHistoryList = items || [];
            $historyContainer.empty();
            if (!searchHistoryList || searchHistoryList.length === 0) {
                $historyContainer.html('<div class="history-empty-state">No recent searches yet</div>');
                return;
            }
            searchHistoryList.forEach(function(item) {
                var safeTerm = escapeHtml(item.search_term);
                var $entry = $(
                    '<div class="history-item" role="button" tabindex="0">' +
                        '<i class="fas fa-history history-item-icon"></i>' +
                        '<span class="history-item-text">' + safeTerm + '</span>' +
                        '<i class="fas fa-arrow-right history-item-arrow"></i>' +
                    '</div>'
                );
                $entry.data('searchTerm', item.search_term);
                $historyContainer.append($entry);
            });
        }

        function fetchSearchHistory(callback) {
            $.ajax({
                url: 'ajax_functions.php',
                type: 'GET',
                data: { action: 'get_search_history' },
                dataType: 'json',
                success: function(res) {
                    if (res && res.success) {
                        isSearchHistoryLoaded = true;
                        renderSearchHistory(res.history || []);
                    }
                    if (typeof callback === 'function') callback();
                },
                error: function() {
                    renderSearchHistory([]);
                    if (typeof callback === 'function') callback();
                }
            });
        }

        function showSearchHistoryDropdown() {
            if (!isSearchHistoryLoaded) {
                fetchSearchHistory(function() {
                    $searchDropdown.stop(true, true).fadeIn(150);
                });
            } else {
                $searchDropdown.stop(true, true).fadeIn(150);
            }
        }

        function hideSearchHistoryDropdown() {
            $searchDropdown.stop(true, true).fadeOut(150);
        }

        var searchHistoryDebounceTimer = null;
        var lastSearchHistoryRequestTerm = '';

        function saveSearchHistory(term) {
            var trimmed = (term || '').trim();
            if (trimmed === '') return;

            // Avoid a second save when the debounce and Search/Enter happen together.
            var normalizedTerm = trimmed.toLocaleLowerCase();
            if (normalizedTerm === lastSearchHistoryRequestTerm) return;
            lastSearchHistoryRequestTerm = normalizedTerm;

            $.ajax({
                url: 'ajax_functions.php',
                type: 'POST',
                data: {
                    action: 'save_search_history',
                    search_term: trimmed
                },
                dataType: 'json',
                success: function(res) {
                    if (res && res.success && res.history) {
                        isSearchHistoryLoaded = true;
                        renderSearchHistory(res.history);
                    } else if (lastSearchHistoryRequestTerm === normalizedTerm) {
                        lastSearchHistoryRequestTerm = '';
                    }
                },
                error: function() {
                    if (lastSearchHistoryRequestTerm === normalizedTerm) {
                        lastSearchHistoryRequestTerm = '';
                    }
                }
            });
        }

        // Save the latest meaningful keyword only after the user pauses typing.
        function debounceSaveSearchHistory(term) {
            clearTimeout(searchHistoryDebounceTimer);
            searchHistoryDebounceTimer = setTimeout(function() {
                saveSearchHistory(term);
            }, 5000);
        }

        // Flush any pending history save when the user explicitly searches.
        function flushDebouncedSearchHistory(term) {
            clearTimeout(searchHistoryDebounceTimer);
            searchHistoryDebounceTimer = null;
            saveSearchHistory(term);
        }

        function clearSearchHistory() {
            $.ajax({
                url: 'ajax_functions.php',
                type: 'POST',
                data: { action: 'clear_search_history' },
                dataType: 'json',
                success: function(res) {
                    if (res && res.success) {
                        isSearchHistoryLoaded = true;
                        renderSearchHistory([]);
                    }
                }
            });
        }

        // Global Search
        function performGlobalSearch(saveToHistory) {
            var keyword = $('#global-portfolio-search').val().trim();
            table.search(keyword).draw();
            $('#portfolio-table').unmark();
            if (keyword !== '') {
                $('#portfolio-table').mark(keyword, {});
            }
            if (saveToHistory && keyword !== '') {
                flushDebouncedSearchHistory(keyword);
            }
        }

        // Event: Search button click
        $(document).on('click', '#btn-global-search', function(e) {
            e.preventDefault();
            hideSearchHistoryDropdown();
            performGlobalSearch(true);
        });

        // Event: Enter or Escape on search input
        $(document).on('keydown', '#global-portfolio-search', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                hideSearchHistoryDropdown();
                performGlobalSearch(true);
            } else if (e.which === 27) { // Escape
                e.preventDefault();
                hideSearchHistoryDropdown();
            }
        });

        // Event: Live filtering plus debounced history save (never save each keystroke).
        var searchTimer;
        $(document).on('input', '#global-portfolio-search', function() {
            var keyword = $(this).val().trim();

            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                performGlobalSearch(false);
            }, 300);

            debounceSaveSearchHistory(keyword);
        });

        // Event: Focus or Click on search input shows dropdown
        $(document).on('focus click', '#global-portfolio-search', function() {
            showSearchHistoryDropdown();
        });

        // Event: Click on history item
        $(document).on('click', '.history-item', function(e) {
            e.preventDefault();
            var term = $(this).data('searchTerm');
            if (typeof term === 'undefined') {
                term = $(this).find('.history-item-text').text();
            }
            if (term !== undefined) {
                $('#global-portfolio-search').val(term);
                hideSearchHistoryDropdown();
                performGlobalSearch(true);
            }
        });

        // Event: Keyboard Enter on history item
        $(document).on('keydown', '.history-item', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        // Event: Clear history button
        $(document).on('click', '#btn-clear-history', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearSearchHistory();
        });

        // Event: Click outside closes dropdown
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-input-box').length) {
                hideSearchHistoryDropdown();
            }
        });

        // Toggle Advanced Filters
        $(document).on('click', '#btn-toggle-advanced-filters', function(e) {
            e.preventDefault();
            var $panel = $('#advanced-filters-panel');
            $panel.slideToggle(200, function() {
                if ($panel.is(':visible')) {
                    $(window).trigger('resize');
                }
            });
            $(this).toggleClass('active');
        });

        // Clear All Filters
        $(document).on('click', '#btn-clear-filters', function(e) {
            e.preventDefault();
            $('#global-portfolio-search').val('');
            $('#filter-type').val(null);
            $('#filter-country').val(null);
            $('#filter-industries').val(null);
            $('#filter-prog_lang').val(null);
            $('#filter-framework-portfolioedit').val(null);
            $('#filter-type, #filter-country, #filter-industries, #filter-prog_lang, #filter-framework-portfolioedit').trigger('change.select2');
            table.search('').columns().search('').draw();
            $('#portfolio-table').unmark();
        });

        // Append custom status dropdown
        var customDropdown =
            '<select id="portfolio_status" class="custom-status-select">' +
            '<option value="0">Published</option>' +
            '<option value="1">Deleted</option>' +
            '</select>';
        $('#portfolio-table_wrapper .dataTables_filter').append(customDropdown);
        insertMultiDeleteButton();

        // Status change handler
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
                        "paging": false,
                        "info": false,
                        "aaSorting": [],
                        dom: '<"portfolio-table-toolbar"f><"portfolio-table-scroll"t>',
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search portfolios..."
                        },
                        columnDefs: [
                            { targets: [0, 7], orderable: false },
                            { targets: 0, width: "30px" },
                            { targets: 1, width: "16%" },
                            { targets: 2, width: "17%" },
                            { targets: 3, width: "9%" },
                            { targets: 4, width: "12%" },
                            { targets: 5, width: "15%" },
                            { targets: 6, width: "11%" },
                            { targets: 7, width: "132px" },
                            { 
                                targets: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36],
                                visible: false
                            }
                        ]
                    });
                    newTable.on('draw', function() {
                        $('#portfolio-table').css('width', '100%');
                        insertMultiDeleteButton();
                        updateSelectedRowStyles();
                        var filteredCount = newTable.rows({ search: 'applied' }).count();
                        $('#portfolio-total-count').text(filteredCount);
                    });
                    var customDropdown =
                        '<select id="portfolio_status" class="custom-status-select">' +
                        '<option value="0">Published</option>' +
                        '<option value="1">Deleted</option>' +
                        '</select>';
                    var $dropdown = $(customDropdown);
                    $dropdown.find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#portfolio-table_wrapper .dataTables_filter').append($dropdown);
                    insertMultiDeleteButton();
                    initSelect2Filters();
                    populateFilterOptions(newTable);
                    table = newTable;
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>