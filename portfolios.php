<?php
include_once('header.php');
include_once('sidebar.php');?>
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
    .bootstrap-select .dropdown-toggle .filter-option {
        background-color: #fff;
        border: 1px solid #ced4da;
    }

    .toggle-favorite {
        cursor: pointer;
    }

    .form-control {
        border: 1px solid #ced4da !important;
    }
    /* Modern Filter Bar (User Reference Match) */
    .modern-filter-wrapper {
        padding: 0 25px 0 25px;
        margin-bottom: 0 !important;
    }
    .modern-filter-bar {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        margin-bottom: 0 !important;
    }
    .search-input-box {
        flex: 1;
        height: 42px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
        display: flex;
        align-items: center;
        padding: 0 14px;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .search-input-box:focus-within {
        border-color: #5b52f9;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12);
    }
    .search-input-box .search-icon {
        font-size: 15px;
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
        font-size: 13.5px;
        color: #1e293b;
        padding: 0 !important;
        margin: 0 !important;
    }
    .search-input-box .search-input::placeholder {
        color: #94a3b8;
        font-size: 13.5px;
    }
    .filter-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-filter-action {
        height: 42px;
        padding: 0 20px;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        outline: none !important;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-search-primary {
        background: #5b52f9;
        color: #ffffff !important;
        border: none;
        box-shadow: 0 2px 6px rgba(91, 82, 249, 0.32);
    }
    .btn-search-primary:hover {
        background: #4940e8;
        box-shadow: 0 4px 10px rgba(91, 82, 249, 0.4);
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
        background: #f8faff;
        border: 1.5px solid #edf0f7;
        border-top: none;
        border-radius: 0 0 10px 10px;
        margin-top: -3px;
        margin-bottom: 6px;
        padding: 18px 20px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        position: relative;
        z-index: 5;
    }
    .advanced-filters-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
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
            justify-content: stretch;
        }
        .filter-actions-group .btn-filter-action {
            flex: 1;
        }
    }
    .adv-filter-col label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        display: block;
    }
    .advanced-filters-footer {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .btn-export, .btn-import {
        height: 38px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #fff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-export:hover, .btn-import:hover {
        background-color: #483fe6 !important;
        border-color: #483fe6 !important;
    }

    .select2-container {
        width: 100% !important;
    }
    .select2-selection__clear,
    .select2-container--default .select2-selection--multiple .select2-selection__clear {
        display: none !important;
    }
    .select2-container--default .select2-selection--multiple {
        min-height: 42px !important;
        height: auto !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 2px 8px !important;
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
        gap: 4px !important;
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
        height: 34px !important;
        line-height: 34px !important;
        margin: 0 !important;
        padding: 0 4px !important;
        font-size: 13.5px !important;
        color: #1e293b !important;
        width: 100% !important;
        border: none !important;
        outline: none !important;
        background: transparent !important;
    }
    .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field::placeholder {
        color: #94a3b8 !important;
        font-size: 13px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #5b52f9 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 4px !important;
        padding: 3px 8px !important;
        font-size: 12.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        margin: 2px 0 !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
        margin-right: 5px !important;
        font-weight: bold !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5b52f9 !important;
        color: #fff !important;
    }

    /* DataTables Pagination UI Fix */
    #portfolio-table_paginate .pagination,
    .dataTables_wrapper .dataTables_paginate .pagination {
        margin: 8px 0 !important;
        display: inline-flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
        float: right !important;
    }
    #portfolio-table_paginate .paginate_button.page-item,
    .dataTables_wrapper .dataTables_paginate .paginate_button.page-item {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    #portfolio-table_paginate .page-link,
    .dataTables_wrapper .dataTables_paginate .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 32px !important;
        height: 32px !important;
        padding: 0 10px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #7571f9 !important;
        background-color: #fff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 4px !important;
        margin: 0 !important;
        text-decoration: none !important;
        line-height: 1 !important;
        transition: all 0.2s ease !important;
    }
    #portfolio-table_paginate .page-item.active .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
        background-color: #7571f9 !important;
        border-color: #7571f9 !important;
        color: #fff !important;
        box-shadow: 0 2px 5px rgba(117, 113, 249, 0.35) !important;
    }
    #portfolio-table_paginate .page-item:not(.active):not(.disabled) .page-link:hover,
    .dataTables_wrapper .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #f0f0ff !important;
        border-color: #7571f9 !important;
        color: #7571f9 !important;
    }
    #portfolio-table_paginate .page-item.disabled .page-link,
    .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
        color: #b5b5c3 !important;
        background-color: #f8f9fa !important;
        border-color: #ebedf2 !important;
        cursor: not-allowed !important;
        opacity: 0.8 !important;
    }

    /* Eliminate unnecessary blank space in dataTables_scrollBody and prevent horizontal scrollbar */
    .dataTables_scrollBody {
        height: auto !important;
        max-height: 600px !important;
        overflow-x: hidden !important;
    }
    #portfolio-table_wrapper .dataTables_scrollBody {
        overflow-x: hidden !important;
    }

    /* =========================================================
       MODERN TABLE UI & BORDER IMPROVEMENTS
       ========================================================= */

    /* Hide dummy header if scroll is ever used */
    #portfolio-table_wrapper .dataTables_scrollBody thead,
    #portfolio-table_wrapper .dataTables_scrollBody thead th,
    #portfolio-table_wrapper .dataTables_scrollBody thead tr {
        display: none !important;
        height: 0 !important;
        padding: 0 !important;
        border: none !important;
        margin: 0 !important;
        line-height: 0 !important;
        font-size: 0 !important;
    }

    /* Outer Table border & rounded card styling */
    #portfolio-table_wrapper .dataTables_scroll,
    #portfolio-table_wrapper table.dataTable {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        overflow: hidden !important;
        margin-top: 6px !important;
        margin-bottom: 12px !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        background-color: #ffffff !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
    }

    /* Reset inner tables if scroll container exists */
    #portfolio-table_wrapper .dataTables_scroll table.dataTable {
        border: none !important;
        margin: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Table Header Styling & Vertical Column Dividers */
    #portfolio-table_wrapper thead th,
    #portfolio-table_wrapper .dataTables_scrollHead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 13px 14px !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid #e2e8f0 !important;
        border-bottom: 1.5px solid #e2e8f0 !important;
        white-space: nowrap !important;
        position: relative !important;
    }
    #portfolio-table_wrapper thead th:last-child,
    #portfolio-table_wrapper .dataTables_scrollHead th:last-child {
        border-right: none !important;
    }

    /* Table Body Cells Styling & Vertical Column Dividers */
    #portfolio-table_wrapper tbody td,
    #portfolio-table_wrapper .dataTables_scrollBody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #334155 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: 1px solid #e2e8f0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        background-color: transparent !important;
        line-height: 1.5 !important;
    }
    #portfolio-table_wrapper tbody td:last-child,
    #portfolio-table_wrapper .dataTables_scrollBody td:last-child {
        border-right: none !important;
    }
    #portfolio-table_wrapper tbody tr:last-child td,
    #portfolio-table_wrapper .dataTables_scrollBody tr:last-child td {
        border-bottom: none !important;
    }

    /* Row Background, Subtle Striping, and Smooth Hover */
    #portfolio-table_wrapper tbody tr,
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease !important;
    }
    #portfolio-table_wrapper tbody tr:nth-child(even),
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr:nth-child(even) {
        background-color: #fbfcfe !important;
    }
    #portfolio-table_wrapper tbody tr:hover,
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr:hover {
        background-color: #f1f5f9 !important;
    }

    /* Selected Row Background Highlight */
    #portfolio-table_wrapper tbody tr.selected-row,
    #portfolio-table_wrapper tbody tr:has(.rowCheckbox:checked),
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr.selected-row,
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr:has(.rowCheckbox:checked) {
        background-color: #f0efff !important;
    }
    #portfolio-table_wrapper tbody tr.selected-row:hover,
    #portfolio-table_wrapper tbody tr:has(.rowCheckbox:checked):hover,
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr.selected-row:hover,
    #portfolio-table_wrapper .dataTables_scrollBody tbody tr:has(.rowCheckbox:checked):hover {
        background-color: #e6e3ff !important;
    }
    #portfolio-table_wrapper tbody tr.selected-row td,
    #portfolio-table_wrapper tbody tr:has(.rowCheckbox:checked) td {
        background-color: transparent !important;
        border-bottom-color: #e0ddfb !important;
    }

    /* Column Specific Enhancements */
    #portfolio-table_wrapper th:first-child,
    #portfolio-table_wrapper td:first-child {
        width: 44px !important;
        max-width: 44px !important;
        text-align: center !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    #portfolio-table_wrapper input[type="checkbox"] {
        cursor: pointer !important;
        width: 16px !important;
        height: 16px !important;
        accent-color: #7571f9 !important;
        vertical-align: middle !important;
    }
    #portfolio-table_wrapper th:nth-child(2),
    #portfolio-table_wrapper td:nth-child(2) {
        width: 50px !important;
        max-width: 50px !important;
        text-align: center !important;
        color: #94a3b8 !important;
        font-weight: 500 !important;
    }
    #portfolio-table_wrapper td.company_name {
        font-weight: 600 !important;
        color: #1e293b !important;
    }
    /* Website URL link styling with Ellipsis and External Link Icon */
    #portfolio-table_wrapper td:nth-child(5) {
        max-width: 280px !important;
    }
    .website-url-wrapper {
        display: flex;
        align-items: center;
        max-width: 270px;
        min-width: 0;
    }
    .website-url-link {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        color: #5b52f9 !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        max-width: 100% !important;
        min-width: 0 !important;
        transition: all 0.15s ease !important;
    }
    .website-url-text {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        min-width: 0 !important;
        flex: 1 1 auto !important;
        font-size: 13.5px !important;
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
        color: #3b30e8 !important;
    }
    .website-url-link:hover .external-icon-wrap {
        color: #3b30e8 !important;
        transform: translate(1.5px, -1.5px);
    }
    #portfolio-table_wrapper th:last-child,
    #portfolio-table_wrapper td:last-child {
        width: 70px !important;
        max-width: 70px !important;
        text-align: center !important;
    }

    /* Modern Table Header Controls (Length & Search Filter) */
    .dataTables_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }
    #portfolio-table_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_length {
        float: left;
        margin: 4px 0 6px 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        height: 38px !important;
    }
    #portfolio-table_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #6c757d !important;
        gap: 6px !important;
    }
    #portfolio-table_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_length select {
        height: 38px !important;
        min-width: 72px !important;
        padding: 6px 28px 6px 12px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #495057 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e5ec !important;
        border-radius: 8px !important;
        outline: none !important;
        cursor: pointer !important;
        margin: 0 4px !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3NTcxZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 10px center !important;
        background-size: 12px 12px !important;
    }
    #portfolio-table_wrapper .dataTables_length select:focus,
    #portfolio-table_wrapper .dataTables_length select:hover,
    .dataTables_wrapper .dataTables_length select:focus,
    .dataTables_wrapper .dataTables_length select:hover {
        border-color: #7571f9 !important;
        box-shadow: 0 0 0 3px rgba(117, 113, 249, 0.15) !important;
    }

    #portfolio-table_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin: 4px 0 6px 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 10px !important;
        height: 38px !important;
        text-align: right !important;
    }
    #portfolio-table_wrapper .dataTables_filter label,
    .dataTables_wrapper .dataTables_filter label {
        display: none !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"],
    .dataTables_wrapper .dataTables_filter input[type="search"] {
        height: 38px !important;
        width: 240px !important;
        padding: 6px 14px 6px 36px !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        color: #495057 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e5ec !important;
        border-radius: 8px !important;
        outline: none !important;
        margin-left: 0 !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3NTcxZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxjaXJjbGUgY3g9IjExIiBjeT0iMTEiIHI9IjgiPjwvY2lyY2xlPjxsaW5lIHgxPSIyMSIgeTE9IjIxIiB4Mj0iMTYuNjUiIHkyPSIxNi42NSI+PC9saW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: 12px center !important;
        background-size: 16px 16px !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"]:focus,
    .dataTables_wrapper .dataTables_filter input[type="search"]:focus {
        border-color: #7571f9 !important;
        box-shadow: 0 0 0 3px rgba(117, 113, 249, 0.15) !important;
    }

    /* Custom Status Filter Dropdown */
    #portfolio_status,
    .custom-status-select {
        height: 38px !important;
        min-width: 120px !important;
        padding: 6px 32px 6px 14px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #495057 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e5ec !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3NTcxZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 12px 12px !important;
    }
    #portfolio_status:focus,
    #portfolio_status:hover,
    .custom-status-select:focus,
    .custom-status-select:hover {
        border-color: #7571f9 !important;
        box-shadow: 0 0 0 3px rgba(117, 113, 249, 0.15) !important;
    }

    /* Modern Multiple Delete Button */
    #deleteSelected,
    .multi-delete-btn {
        height: 38px !important;
        padding: 0 16px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        background-color: #ff5e5e !important;
        border: 1px solid #ff5e5e !important;
        color: #ffffff !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(255, 94, 94, 0.2) !important;
        white-space: nowrap !important;
        line-height: normal !important;
        margin: 0 !important;
    }
    #deleteSelected:hover,
    .multi-delete-btn:hover {
        background-color: #ff3838 !important;
        border-color: #ff2b2b !important;
        box-shadow: 0 2px 6px rgba(255, 94, 94, 0.35) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }
    #deleteSelected:active,
    .multi-delete-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(255, 94, 94, 0.2) !important;
        color: #ffffff !important;
    }

    /* Modern 3-Dots Action Button (Circular with background) & Dropdown Menu */
    .action-dots-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #7571f9;
        background-color: #f0f0ff;
        border: 1px solid #e2e0ff;
        transition: all 0.2s ease;
        cursor: pointer;
        outline: none !important;
    }
    .action-dots-btn:hover,
    .action-dots-btn.active {
        color: #fff;
        background-color: #7571f9;
        border-color: #7571f9;
        box-shadow: 0 3px 8px rgba(117, 113, 249, 0.4);
    }
    .action-dots-btn i {
        font-size: 13px;
    }
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
                <div class="card">
                    <div class="card-body px-0" id="ajax_table_print">
                        <?php
                            $result = mysqli_query($conn,"SELECT * FROM portfolio WHERE `deleted`='0' ORDER BY timestamp DESC");
                            include 'portfolios-table.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Favourite Websites</h3>
                        <div class="form-group">
                            <?php
                            $result = mysqli_query($conn,"SELECT * FROM favourite WHERE `name`='websites' ORDER BY timestamp DESC");
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <textarea class="form-control fav_change_inp" id="fav_websites" rows="10"  placeholder="Enter Websites Link Here."><?php echo $row['value'];?></textarea>
                            <a href="javascript:void(0);" onclick="copyToClipboard(this)" class="copy_links_i" title="Click to copy."><i class="icon-notebook icons"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Favourite Designs</h3>
                        <div class="form-group">
                        <?php
                            $result = mysqli_query($conn,"SELECT * FROM favourite WHERE `name`='designs' ORDER BY timestamp DESC");
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <textarea class="form-control fav_change_inp" id="fav_designs" rows="10" placeholder="Enter Designs Link Here."><?php echo $row['value'];?></textarea>
                            <a href="javascript:void(0);" onclick="copyToClipboard(this)" class="copy_links_i" title="Click to copy."><i class="icon-notebook icons"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Delete Confirmation -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="deleteModalBody">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete" data-type="" data-id="">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Actions Dropdown Menu -->
<div id="actionDropdownMenu" class="action-dropdown-menu" style="display: none;">
    <a id="actionDropdownView" href="#" target="_blank" class="action-dropdown-item">
        <i class="far fa-eye"></i>
        <span>View</span>
    </a>
    <div class="action-dropdown-divider"></div>
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
    function insertMultiDeleteButton() {
        var $filter = $('#portfolio-table_wrapper .dataTables_filter');
        if ($filter.length === 0) return;

        var multiDelBtnHtml = '<button type="button" id="deleteSelected" class="btn btn-danger multi-delete-btn"><i class="far fa-trash-alt"></i> Multiple Delete</button>';
        var $btn = $('#deleteSelected');
        var $status = $filter.find('#portfolio_status');

        if ($btn.length === 0) {
            if ($status.length > 0) {
                $status.before(multiDelBtnHtml);
            } else {
                $filter.prepend(multiDelBtnHtml);
            }
        } else {
            // Ensure button is positioned right before #portfolio_status inside .dataTables_filter
            if ($status.length > 0) {
                if (!$btn.next().is($status)) {
                    $btn.detach();
                    $status.before($btn);
                }
            } else if (!$filter.find('#deleteSelected').length) {
                $btn.detach();
                $filter.prepend($btn);
            }
        }
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
                backgroundColor: '#51A351',
            }).showToast();
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

        $("#exportSelected").on("click", function() {
            var selectedIds = [];

            $('.rowCheckbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert("Please select at least one row to export.");
                return;
            }
           // console.log(selectedIds)

            $.ajax({
                    url: 'export.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { ids: selectedIds },
                    success: function (response) {
                        //console.log(response);
                        if (response.status === 'success') {
                            // Trigger download
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

        $("#importSelected").on("click", function () {
            $("#importFile").click(); // Trigger file input
        });

        $("#importFile").on("change", function () {
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
                     $('#importSelected').css('cursor','pointer');
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

        
        $("#filter-country").select2({ placeholder: "Select Country", width: '100%' });
        $("#filter-type").select2({ placeholder: "Select Type", width: '100%' });
        $("#filter-industries").select2({ placeholder: "Select Industries", width: '100%' });
        $("#filter-prog_lang").select2({ placeholder: "Select Programming Language", width: '100%' });
        $("#filter-framework-portfolioedit").select2({ placeholder: "Select Framework", width: '100%' });
        $(document).on('change', '.fav_change_inp', function() {
            var _fav_website = $('#fav_websites').val();
            var _fav_design = $('#fav_designs').val();
            $.ajax({
                url: 'ajax_functions.php', // Your PHP file to handle the update
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
                    // Handle error if AJAX call fails
                    console.error('Error:', error);
                }
            });
        });
        $(document).on('click', '.toggle-favorite', function() {
            var itemId = $(this).data('id');
            var isFavorite = $(this).hasClass('fas') ? 0 : 1;
            $.ajax({
                url: 'ajax_functions.php', // Your PHP file to handle the update
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
                    // Handle error if AJAX call fails
                    console.error('Error:', error);
                }
            });
            $(this).toggleClass('far fas');
        });

        // Modern 3-Dots Action Dropdown Menu
        var activeActionBtn = null;

        $(document).on('click', '.action-dots-btn', function(e) {
            e.stopPropagation();
            var $btn = $(this);
            var id = $btn.attr('data-id');

            // If already open on this button, toggle close
            if (activeActionBtn && activeActionBtn[0] === $btn[0] && $('#actionDropdownMenu').is(':visible')) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
                return;
            }

            $('.action-dots-btn').removeClass('active');
            $btn.addClass('active');
            activeActionBtn = $btn;

            $('#actionDropdownView').attr('href', 'view-portfolio.php?id=' + id);
            $('#actionDropdownEdit').attr('href', 'edit-portfolio.php?id=' + id);
            $('#actionDropdownDelete').attr('data-id', id);

            var $menu = $('#actionDropdownMenu');
            $menu.show();

            var rect = this.getBoundingClientRect();
            var menuWidth = $menu.outerWidth();
            var menuHeight = $menu.outerHeight();

            // Align right edge of menu with right edge of button
            var left = rect.right - menuWidth;
            var top = rect.bottom + 4;

            // If bottom overflows viewport, show above button
            if (rect.bottom + menuHeight > window.innerHeight) {
                top = rect.top - menuHeight - 4;
            }

            $menu.css({
                top: top + 'px',
                left: left + 'px'
            });
        });

        // Close dropdown when clicking outside or scrolling
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

        $('.dataTables_scrollBody').on('scroll', function() {
            if ($('#actionDropdownMenu').is(':visible')) {
                $('#actionDropdownMenu').hide();
                $('.action-dots-btn').removeClass('active');
                activeActionBtn = null;
            }
        });

        // Close dropdown when View or Edit link is clicked
        $(document).on('click', '#actionDropdownView, #actionDropdownEdit', function() {
            $('#actionDropdownMenu').hide();
            $('.action-dots-btn').removeClass('active');
            activeActionBtn = null;
        });

        // Dropdown Delete button click - open Confirmation Modal
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

         // Single delete - open modal
        $(document).on('click', '.delete-btn', function() {
            var _id = $(this).attr('data-id');
            $('#confirmDelete')
                .attr('data-id', _id)
                .attr('data-type', 'single');
            $('#deleteModalBody').text('Are you sure you want to delete this portfolio?');
            $('#deleteConfirmationModal').modal('show');
        });

        // Multiple delete - open modal
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
                        ids: ids.split(',') // Convert back to array
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
        
        var table = $('#portfolio-table').DataTable({
            "aaSorting": [],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // Add "All" option
            "pageLength": 10, // Default entries to show
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search portfolios...",
                lengthMenu: "Show _MENU_ entries"
            },
            // searchHighlight: true
            columnDefs: [
                { 
                    targets: [
                        7,  // Framework
                        8,  // CMS / Website Builder
                        9,  // Backend Technologies
                        10, // Frontend Technologies
                        11, // Hosting / Cloud Services
                        12, // Other-Technologies
                        13, // Programming Language
                        14, // Current Status
                        15, // Tags
                        16, // Design URL
                        17, // Portfolio Type
                        18, // About Business
                        19, // Niche
                        20, // Role
                        21, // Features
                        22, // Team Associates
                        23, // Comments
                        24, // GitHub Repository
                        25, // Demo Video
                        26, // Timestamp
                        28, // Initial goal or purpose
                        29, // Pain Area
                        30, // Development Approach
                        31, // Deliverables Outlined
                        32, // Challenges Faced During Development
                        33, // Solution
                        34, // Unique or Interactive elements
                        35  // Success story
                    ],
                    visible: false
                }
            ],
           // dom: 'Bfrtip', // Enable export buttons
            buttons: [
            {
                extend: 'excel',
                text: 'Export Portfolio',
                filename: 'Framework_Portfolio',
                title: 'Framework Data Export',
                exportOptions: {
                    columns: ':hidden', // Includes hidden columns in the export
                    format: {
                        body: function (data, row, column, node) {
                            return data; // If you need to modify data, do it here
                        }
                    }
                }
            }
        ]
        });    
       
        insertMultiDeleteButton();

        table.on('draw', function () {
            insertMultiDeleteButton();
            updateSelectedRowStyles();
        });

        var countries = new Set();
            table.column(5).data().each(function (value) {
                countries.add(value);
            });

            // Populate country filter dropdown
            countries.forEach(function (country) {
                $("#filter-country").append(`<option value="${country}">${country}</option>`);
            });
        
         

        // Multi-select filter event
        $("#filter-type").on("change", function () {
            var selectedTypes = $(this).val(); // Get selected values
            
            if (selectedTypes.length > 0) {
                var regex = selectedTypes.join("|"); // Create regex for OR filtering
                table.column(2).search(regex, true, false).draw();
            } else {
                table.column(2).search("").draw(); // Reset filter if no selection
            }
        });

        $("#filter-country").on("change", function () {
            var selectedCountries = $(this).val(); // Get selected values
            
            if (selectedCountries.length > 0) {
                var regex = selectedCountries.join("|"); // Create regex for OR filtering
                table.column(5).search(regex, true, false).draw();
            } else {
                table.column(5).search("").draw(); // Reset filter if no selection
            }
        });

        $("#filter-industries").on("change", function () {
            var selectedIndustries = $(this).val(); // Get selected values
            
            if (selectedIndustries.length > 0) {
                var regex = selectedIndustries.join("|"); // Create regex for OR filtering
                table.column(6).search(regex, true, false).draw();
            } else {
                table.column(6).search("").draw(); // Reset filter if no selection
            }
        });

        $("#filter-prog_lang").on("change", function () {
            var selectedProgLang = $(this).val(); // Get selected values
            
            if (selectedProgLang.length > 0) {
                var regex = selectedProgLang.join("|"); // Create regex for OR filtering
                table.column(13).search(regex, true, false).draw();
            } else {
                table.column(13).search("").draw(); // Reset filter if no selection
            }
        });     
        
        var frameworks = new Set();
        table.column(7).data().each(function (value) {
            frameworks.add(value);
        });

        frameworks.forEach(function (fw) {
            $("#filter-framework-portfolioedit").append(`<option value="${fw}">${fw}</option>`);
        });

        // Apply custom filtering
        $("#filter-framework-portfolioedit").on("change", function () {
            var selectedFrameworks = $(this).val(); // Get selected values

            if (selectedFrameworks && selectedFrameworks.length > 0) {
                var regex = selectedFrameworks.join("|"); // Create regex for OR filtering
                table.column(7).search(regex, true, false).draw();
            } else {
                table.column(7).search("").draw(); // Reset filter if nothing selected
            }
        });
       
        // Global Modern Filter Search & Actions
        function performGlobalSearch() {
            var keyword = $('#global-portfolio-search').val().trim();
            table.search(keyword).draw();
            $('#portfolio-table').unmark();
            if (keyword !== '') {
                $('#portfolio-table').mark(keyword, {});
            }
        }

        // Search button click
        $(document).on('click', '#btn-global-search', function(e) {
            e.preventDefault();
            performGlobalSearch();
        });

        // Enter key in search input
        $(document).on('keydown', '#global-portfolio-search', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                performGlobalSearch();
            }
        });

        // Debounced search on input typing
        var searchTimer;
        $(document).on('input', '#global-portfolio-search', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                performGlobalSearch();
            }, 300);
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

        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
        });

        var table1 = $('#fav-portfolio-table').dataTable({
            "aaSorting": [],
            scrollY: "600px",
            scrollCollapse: true,
            scrollX: true,
            searchHighlight: true
        });
        $('#fav-portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#fav-portfolio-table').unmark();
            $('#fav-portfolio-table').mark(keyword, {});
        });
        var customDropdown =
            '<select id="portfolio_status" class="custom-status-select">' +
            '<option value="0">Published</option>' +
            '<option value="1">Deleted</option>' +
            '</select>';
        $('#portfolio-table_wrapper .dataTables_filter').append(customDropdown);
        insertMultiDeleteButton();
       
        $(document).on('change', '#portfolio_status', function() {
            var selectedValue = $(this).val();
            // Perform an AJAX call with the selected value
            $.ajax({
                url: 'ajax_functions.php', // Replace with your API endpoint
                method: 'GET', // Change the method if needed
                data: {
                    action: 'get_portfolios',
                    value: selectedValue
                }, // Pass any data needed
                success: function(response) {
                    $('#ajax_table_print').html(response);
                    var table = $('#portfolio-table').dataTable({
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "pageLength": 10,
                        "aaSorting": [],
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search portfolios...",
                            lengthMenu: "Show _MENU_ entries"
                        },
                        columnDefs: [
                            {
                                targets: [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 29, 30, 31, 32, 33, 34, 35],
                                visible: false
                            }
                        ]
                    });
                    table.on('draw', function() {
                        insertMultiDeleteButton();
                        updateSelectedRowStyles();
                    });
                    var customDropdown =
                        '<select id="portfolio_status" class="custom-status-select">' +
                        '<option value="0">Published</option>' +
                        '<option value="1">Deleted</option>' +
                        '</select>';
                    var $dropdown = $(customDropdown);
                    // Loop through options to set selected based on selectedValue
                    $dropdown.find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#portfolio-table_wrapper .dataTables_filter').append($dropdown);
                    insertMultiDeleteButton();
                },
                error: function(error) {
                    // Handle the error if the AJAX call fails
                    console.error('Error:', error);
                }
            });
        });
        var name;
        $(document).on('contextmenu', '.copy-link', function(event) {
            event.preventDefault();
            var linkURL = $(this).attr('href');
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(linkURL).select();
            document.execCommand('copy');
            tempInput.remove();
            // Display toast message
            Toastify({
                text: 'Copied to clipboard!',
                duration: 3000,
                close: true,
                gravity: 'top-right',
                backgroundColor: '#51A351',
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
                console.log($('#' + name));
            }
        });        
    });
</script>