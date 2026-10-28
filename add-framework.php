<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php'); // This file contains your DB connection (e.g., $conn)
// set timezone to India (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');


$framework_labels = [
    "cms"       => "CMS / Website Builder",
    "backend"   => "Backend Technologies",
    "frontend"  => "Frontend Technologies",
    "hosting"   => "Hosting / Cloud Services",
    "other"     => "Other Technologies"
];
 
function call_all_rows_and_append_in_select_option($conn,$table_name,$col_name,$framework_labels){    
    $sql = "SELECT DISTINCT $col_name FROM $table_name";
    $result = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_assoc($result)) {  
        $framework_type_label = isset($framework_labels[$row[$col_name]]) ? $framework_labels[$row[$col_name]] : ""; // Get label           
        $options .= "<option value='" . $row[$col_name] . "'>" . htmlspecialchars($framework_type_label) . "</option>"; 
    }
    return $options;
}

$message = "";
$is_edit_mode = false;
$framework_name_value = "";
$framework_type_value = "";

// -----------------------
// Handle Delete Request
// -----------------------
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM framework WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Framework Deleted successfully!</div>';
        // Redirect immediately after deletion to avoid resubmission issues
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// -----------------------
// Handle Edit Request
// -----------------------
if (isset($_GET['edit'])) {  
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM framework WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();       
        $framework_name_value = $row['framework_name'];
        $framework_type_value = $row['framework_type'];
        $is_edit_mode = true;
    } else {
        $message = '<div class="alert alert-danger">Framework not found!</div>';
    }
    $stmt->close();
}

// -----------------------
// Handle Form Submission
// -----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Insert New Framework
    if (isset($_POST['insert-framework'])) {
        $framework_name = trim($_POST['framework_name']);
        $framework_type = trim($_POST['framework_type']);
        if (empty($framework_name)) {
            $message = '<div class="alert alert-danger">Framework name is required!</div>';
        } else {           

            $stmt = $conn->prepare("SELECT COUNT(*) FROM framework WHERE framework_name = ?");
            $stmt->bind_param("s", $framework_name);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $message = '<div class="alert alert-warning">This framework already exists!</div>';
            } else {
                // Insert both the Framework name and the timestamp
                $current_time = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO framework (framework_name, framework_type, created_at) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $framework_name, $framework_type, $current_time);
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Framework added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }       
            
        }
    }
    // 2. Update Existing Framework
    elseif (isset($_POST['update-framework'])) {
        $framework_name = trim($_POST['framework_name']);
        $framework_type = trim($_POST['framework_type']);
        $framework_id = intval($_POST['framework_id']);
        if (empty($framework_name)) {
            $message = '<div class="alert alert-danger">Framework name is required!</div>';
        } else {
            $stmt = $conn->prepare("UPDATE framework SET framework_name = ?, framework_type = ? WHERE id = ?");
            $stmt->bind_param("ssi", $framework_name, $framework_type, $framework_id);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Framework updated successfully!</div>';
                // Reset edit mode after update
                $is_edit_mode = false;
                $framework_name_value = "";
                $framework_type_value = "cms";
            } else {
                $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
}
?>
<style>
/* =========================================================
   ADD FRAMEWORK RESPONSIVE & MODERN UI STYLES
   ========================================================= */

/* Content Body Background */
.content-body {
    background-color: #f8fafc;
}

/* Page Header Breadcrumbs */
.page-titles {
    background: transparent !important;
    padding: 16px 20px 8px 20px !important;
    margin-bottom: 6px !important;
}
.page-titles h3 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}
.page-titles .breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 13px;
}
.page-titles .breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
}
.page-titles .breadcrumb-item.active a {
    color: #5b52f9;
    font-weight: 600;
}

/* Form Card Styling */
.framework-form-card {
    background: #ffffff !important;
    border: 1.5px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
    margin-bottom: 20px !important;
    overflow: hidden;
}
.framework-form-card .card-body {
    padding: 22px 24px !important;
}
.framework-form-card .col-form-label {
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    margin-bottom: 6px !important;
}
.framework-form-card .form-control {
    height: 42px !important;
    font-size: 14px !important;
    color: #1e293b !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    padding: 8px 14px !important;
    transition: all 0.2s ease !important;
    background-color: #ffffff !important;
    box-sizing: border-box !important;
    width: 100% !important;
    max-width: 100% !important;
}
.framework-form-card select.form-control {
    appearance: none !important;
    -webkit-appearance: none !important;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
    background-repeat: no-repeat !important;
    background-position: right 14px center !important;
    background-size: 12px 12px !important;
    padding-right: 36px !important;
    cursor: pointer !important;
}
.framework-form-card .form-control:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    outline: none !important;
}
.framework-form-card .form-control::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
}

/* Custom Buttons */
.btn-submit-custom {
    background: #5b52f9 !important;
    border: 1px solid #5b52f9 !important;
    color: #ffffff !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    padding: 9px 24px !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 6px rgba(91, 82, 249, 0.25) !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 40px !important;
}
.btn-submit-custom:hover {
    background: #483fe6 !important;
    border-color: #483fe6 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(91, 82, 249, 0.35) !important;
    color: #ffffff !important;
}
.btn-cancel-custom {
    background: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    color: #475569 !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    padding: 9px 20px !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 40px !important;
    text-decoration: none !important;
}
.btn-cancel-custom:hover {
    background: #e2e8f0 !important;
    color: #1e293b !important;
}

/* Filter Card Styling */
.framework-filter-card {
    background: #ffffff !important;
    border: 1.5px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
    margin-bottom: 20px !important;
    overflow: hidden;
}
.framework-filter-card .card-body {
    padding: 18px 24px !important;
}
.filter-card-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}
.filter-card-header i {
    font-size: 15px;
    color: #5b52f9;
}

/* Select2 Multiple Container Overrides */
.framework-filter-card .select2-container {
    width: 100% !important;
    max-width: 100% !important;
    display: block !important;
    box-sizing: border-box !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple {
    background-color: #ffffff !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    min-height: 42px !important;
    padding: 4px 8px !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}
.framework-filter-card .select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    gap: 4px !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #5b52f9 !important;
    color: #ffffff !important;
    border: 1px solid #5b52f9 !important;
    border-radius: 20px !important;
    padding: 4px 26px 4px 10px !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    margin: 2px !important;
    position: relative !important;
    line-height: 1.4 !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff !important;
    position: absolute !important;
    right: 8px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    opacity: 0.85 !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    opacity: 1 !important;
    color: #ffffff !important;
}
.framework-filter-card .select2-container--default .select2-search--inline .select2-search__field {
    margin-top: 4px !important;
    margin-left: 4px !important;
    font-size: 13.5px !important;
    color: #1e293b !important;
    height: 28px !important;
}
.framework-filter-card .select2-container--default .select2-selection--multiple .select2-selection__clear {
    margin-top: 6px !important;
    margin-right: 8px !important;
    color: #94a3b8 !important;
    font-size: 16px !important;
}

/* Select2 Dropdown Options Styling */
.select2-dropdown {
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    z-index: 1050 !important;
    overflow: hidden !important;
}
.select2-container--default .select2-results__option {
    padding: 8px 14px !important;
    font-size: 13.5px !important;
    color: #334155 !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #5b52f9 !important;
    color: #ffffff !important;
}
.select2-container--default .select2-results__option[aria-selected="true"] {
    background-color: #e0e7ff !important;
    color: #4338ca !important;
    font-weight: 600 !important;
}

/* Swipe Hint Banner (Mobile/Tablet) */
.table-scroll-hint {
    display: none !important;
    background: linear-gradient(90deg, #f8fafc 0%, #eef2ff 50%, #f8fafc 100%);
    color: #5b52f9;
    font-size: 11.5px;
    font-weight: 600;
    padding: 8px 14px;
    border-bottom: 1px solid #e0e7ff;
    text-align: center;
    user-select: none;
    letter-spacing: 0.1px;
}
@media (max-width: 991.98px) {
    .table-scroll-hint {
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
}

/* Table Card */
.framework-table-card {
    background: #ffffff !important;
    border: 1.5px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
    overflow: hidden !important;
    margin-bottom: 30px !important;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}
.framework-table-responsive {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}

/* DataTables Controls Standardization */
#framework-table_wrapper {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Top Toolbar: Entries & Search */
#framework-table_wrapper > .row:nth-child(1) {
    margin: 0 !important;
    padding: 14px 20px 12px 20px !important;
    background: #ffffff !important;
    border-bottom: 1px solid #f1f5f9 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
}
#framework-table_wrapper > .row:nth-child(1) > [class*="col-"] {
    padding: 0 !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: 100% !important;
}
#framework-table_wrapper .dataTables_length {
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
}
#framework-table_wrapper .dataTables_length label {
    display: inline-flex !important;
    align-items: center !important;
    margin-bottom: 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    gap: 6px !important;
}
#framework-table_wrapper .dataTables_length select {
    height: 36px !important;
    min-width: 65px !important;
    padding: 2px 24px 2px 10px !important;
    font-size: 13px !important;
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
#framework-table_wrapper .dataTables_length select:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
}
#framework-table_wrapper .dataTables_filter {
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
}
#framework-table_wrapper .dataTables_filter label {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    gap: 8px !important;
}
#framework-table_wrapper .dataTables_filter input {
    height: 36px !important;
    width: 200px !important;
    max-width: 100% !important;
    padding: 6px 12px !important;
    font-size: 13px !important;
    color: #1e293b !important;
    background-color: #ffffff !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 6px !important;
    outline: none !important;
    transition: all 0.2s ease !important;
    box-sizing: border-box !important;
}
#framework-table_wrapper .dataTables_filter input:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    width: 230px !important;
}

/* Table Style Inside Card */
#framework-table {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: collapse !important;
    border: none !important;
}
#framework-table thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 13px 18px !important;
    border-top: none !important;
    border-bottom: 1.5px solid #e2e8f0 !important;
    border-left: none !important;
    border-right: none !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
}
#framework-table tbody td {
    padding: 13px 18px !important;
    vertical-align: middle !important;
    border-top: 1px solid #f1f5f9 !important;
    border-bottom: none !important;
    border-left: none !important;
    border-right: none !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    background: #ffffff !important;
}
#framework-table tbody tr:hover td {
    background-color: #f8fafc !important;
}
.framework-name-cell {
    font-weight: 600 !important;
    color: #1e293b !important;
    min-width: 140px !important;
}
.framework-type-cell {
    min-width: 160px !important;
}
.badge-framework-type {
    display: inline-block;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 20px;
    background-color: #eef2ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
    white-space: nowrap;
}
.framework-time-cell {
    color: #64748b !important;
    font-size: 12.5px !important;
    white-space: nowrap !important;
    min-width: 150px !important;
}

/* Action Button Group */
.action-btn-group {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    flex-wrap: nowrap !important;
    white-space: nowrap !important;
}
.btn-action-edit {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #5b52f9 !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    padding: 6px 12px !important;
    border-radius: 6px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    border: none !important;
    min-height: 28px !important;
}
.btn-action-edit:hover {
    background: #483fe6 !important;
    color: #ffffff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 6px rgba(91, 82, 249, 0.3) !important;
}
.btn-action-delete {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #ef4444 !important;
    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    padding: 6px 12px !important;
    border-radius: 6px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    border: none !important;
    min-height: 28px !important;
}
.btn-action-delete:hover {
    background: #dc2626 !important;
    color: #ffffff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3) !important;
}

/* Bottom Toolbar: Info & Pagination */
#framework-table_wrapper > .row:nth-child(3) {
    margin: 0 !important;
    padding: 14px 20px !important;
    background: #ffffff !important;
    border-top: 1px solid #f1f5f9 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
}
#framework-table_wrapper > .row:nth-child(3) > [class*="col-"] {
    padding: 0 !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: 100% !important;
}
#framework-table_wrapper .dataTables_info {
    font-size: 13px !important;
    color: #64748b !important;
    padding-top: 0 !important;
    margin: 0 !important;
}
#framework-table_wrapper .dataTables_paginate {
    margin: 0 !important;
    padding-top: 0 !important;
}
#framework-table_wrapper .pagination {
    margin: 0 !important;
    display: inline-flex !important;
    gap: 4px !important;
}
#framework-table_wrapper .page-item .page-link {
    border: 1px solid #e2e8f0 !important;
    border-radius: 6px !important;
    color: #475569 !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    padding: 5px 11px !important;
    background: #ffffff !important;
    transition: all 0.15s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 32px !important;
    height: 32px !important;
}
#framework-table_wrapper .page-item:hover .page-link {
    background: #f1f5f9 !important;
    color: #1e293b !important;
    border-color: #cbd5e1 !important;
}
#framework-table_wrapper .page-item.active .page-link {
    background: #5b52f9 !important;
    border-color: #5b52f9 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 6px rgba(91, 82, 249, 0.25) !important;
}
#framework-table_wrapper .page-item.disabled .page-link {
    opacity: 0.5 !important;
    background: #ffffff !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed !important;
}

/* =========================================================
   RESPONSIVE MEDIA QUERIES (Mobile & Tablet)
   ========================================================= */
@media (max-width: 767.98px) {
    .content-body {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .page-titles {
        padding: 12px 14px 4px 14px !important;
        margin-bottom: 4px !important;
    }
    .page-titles h3 {
        font-size: 18px !important;
    }
    .page-titles .breadcrumb {
        font-size: 12px !important;
    }
    .container-fluid {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
    .framework-form-card .card-body {
        padding: 16px 14px !important;
    }
    .framework-filter-card .card-body {
        padding: 14px 14px !important;
    }
    .btn-submit-custom,
    .btn-cancel-custom {
        width: 100% !important;
    }
    .framework-action-btns {
        flex-direction: column !important;
        gap: 8px !important;
    }
    #framework-table_wrapper > .row:nth-child(1) {
        padding: 12px 14px !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
    }
    #framework-table_wrapper > .row:nth-child(1) > [class*="col-"] {
        width: 100% !important;
    }
    #framework-table_wrapper .dataTables_length {
        width: 100% !important;
        justify-content: space-between !important;
    }
    #framework-table_wrapper .dataTables_filter {
        width: 100% !important;
    }
    #framework-table_wrapper .dataTables_filter label {
        width: 100% !important;
    }
    #framework-table_wrapper .dataTables_filter input {
        width: 100% !important;
        flex: 1 1 auto !important;
    }
    #framework-table_wrapper .dataTables_filter input:focus {
        width: 100% !important;
    }
    #framework-table_wrapper > .row:nth-child(3) {
        padding: 12px 14px !important;
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        gap: 10px !important;
    }
    #framework-table_wrapper > .row:nth-child(3) > [class*="col-"] {
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
    }
    #framework-table thead th,
    #framework-table tbody td {
        padding: 10px 12px !important;
    }
}

@media (max-width: 480px) {
    .container-fluid {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
    .framework-form-card .card-body,
    .framework-filter-card .card-body {
        padding: 14px 10px !important;
    }
    #framework-table_wrapper .page-item .page-link {
        padding: 4px 8px !important;
        font-size: 11.5px !important;
        min-width: 28px !important;
        height: 28px !important;
    }
}
</style>

<div class="content-body">
    <!-- Page Header -->
    <div class="row page-titles mx-0">
        <div class="col-12 col-md-6 p-md-0">
            <h3><?php echo $is_edit_mode ? "Edit Framework" : "Add Framework"; ?></h3>
        </div>
        <div class="col-12 col-md-6 p-md-0 mt-2 mt-md-0 d-flex justify-content-md-end">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)"><?php echo $is_edit_mode ? "Edit Framework" : "Add Framework"; ?></a>
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card framework-form-card">
                    <div class="card-body">
                        <div class="form-validation">
                            <!-- Display success or error messages -->
                            <?php echo $message; ?>
                            <form class="form-valide" action="add-framework.php" method="post">
                                <div class="row">
                                    <div class="form-group col-12 col-md-6 col-lg-5">
                                        <label class="col-form-label" for="framework_name">Framework Name <span class="text-danger">*</span></label>
                                        <input type="text" id="framework_name" name="framework_name" class="form-control" placeholder="Enter Framework Name" value="<?php echo htmlspecialchars($framework_name_value); ?>" required>
                                    </div>
                                    <div class="form-group col-12 col-md-6 col-lg-5">
                                        <label class="col-form-label" for="framework_type">Framework Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="framework_type" id="framework_type" required>
                                            <option value="cms" <?php if(isset($framework_type_value) && $framework_type_value == "cms"){ echo "selected"; } ?>>CMS / Website Builder</option>
                                            <option value="backend" <?php if(isset($framework_type_value) && $framework_type_value == "backend"){ echo "selected"; } ?>>Backend Technologies</option>
                                            <option value="frontend" <?php if(isset($framework_type_value) && $framework_type_value == "frontend"){ echo "selected"; } ?>>Frontend Technologies</option>
                                            <option value="hosting" <?php if(isset($framework_type_value) && $framework_type_value == "hosting"){ echo "selected"; } ?>>Hosting / Cloud Services</option>
                                            <option value="other" <?php if(isset($framework_type_value) && $framework_type_value == "other"){ echo "selected"; } ?>>Other Technologies</option>
                                        </select>                                         
                                    </div>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <input type="hidden" name="framework_id" value="<?php echo $edit_id; ?>">
                                    <div class="form-group row mt-2 mb-0">
                                        <div class="col-12 d-flex flex-wrap gap-2 framework-action-btns">
                                            <button type="submit" name="update-framework" class="btn btn-submit-custom">
                                                <i class="fa fa-check mr-1"></i> Update Framework
                                            </button>
                                            <a href="add-framework.php" class="btn btn-cancel-custom ml-md-2">Cancel</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group row mt-2 mb-0">
                                        <div class="col-12">
                                            <button type="submit" name="insert-framework" class="btn btn-submit-custom">
                                                <i class="fa fa-plus-circle mr-1"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section Card -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card framework-filter-card">
                    <div class="card-body">
                        <div class="filter-card-header">
                            <i class="fa fa-filter mr-2"></i>
                            <span>Filter by Framework Type:</span>
                        </div>
                        <div class="filter-select-wrapper">
                            <select id="filter-frameworkType-addFramework" name="filter-frameworkType-addFramework[]" multiple="multiple" class="form-control" style="width: 100%;">            
                                <?php echo call_all_rows_and_append_in_select_option($conn, "framework", "framework_type", $framework_labels); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Framework Table Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card framework-table-card">
                    <div class="table-scroll-hint">
                        <i class="fa fa-arrows-h mr-1"></i> Swipe horizontally to view full table
                    </div>
                    <div class="table-responsive framework-table-responsive" id="frawork_table_print">
                        <table class="table table-striped table-bordered" id="framework-table">
                            <thead>
                                <tr>
                                    <th>Framework Name</th>
                                    <th>Framework Type</th>
                                    <th>Timestamp</th>
                                    <th class="text-center" style="width: 150px; min-width: 140px;">Action</th>
                                </tr>
                             </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM framework ORDER BY id DESC";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $framework_type_label = isset($framework_labels[$row['framework_type']]) ? $framework_labels[$row['framework_type']] : $row['framework_type'];
                                        $raw_type = htmlspecialchars($row['framework_type']);
                                        $escaped_label = htmlspecialchars($framework_type_label);
                                        echo "<tr>
                                                <td class='framework-name-cell'>" . htmlspecialchars($row['framework_name']) . "</td>
                                                <td class='framework-type-cell' data-search='{$raw_type} {$escaped_label}'>
                                                    <span class='badge-framework-type'>{$escaped_label}</span>
                                                </td>
                                                <td class='framework-time-cell'>{$row['created_at']}</td>                                                
                                                <td class='text-center'>
                                                    <div class='action-btn-group'>
                                                        <a href='add-framework.php?edit={$row['id']}' class='btn-action-edit' title='Edit Framework'>
                                                            <i class='fa fa-pencil mr-1'></i>Edit
                                                        </a>
                                                        <a href='add-framework.php?delete={$row['id']}' class='btn-action-delete' onclick=\"return confirm('Are you sure you want to delete this framework?');\" title='Delete Framework'>
                                                            <i class='fa fa-trash mr-1'></i>Delete
                                                        </a>
                                                    </div>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-4 text-muted'>No Framework added yet.</td></tr>";
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

<?php
include_once('footer.php');
$conn->close();
?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = null;
        if ($.fn.DataTable) {
            $.fn.DataTable.ext.pager.numbers_length = 4;
            if ($.fn.DataTable.isDataTable('#framework-table')) {
                $('#framework-table').DataTable().destroy();
            }
            table = $('#framework-table').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: false,
                order: [[2, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search framework...",
                    lengthMenu: "Show _MENU_ entries"
                }
            });
        }

        // Initialize Select2 filter
        if ($.fn.select2) {
            $("#filter-frameworkType-addFramework").select2({ 
                placeholder: "Select Framework Type to filter...", 
                allowClear: true,
                width: '100%'
            });
            
            $("#filter-frameworkType-addFramework").on("change", function () {
                var selectedFrameworkType = $(this).val(); // Array of selected values
                
                if (table) {
                    if (selectedFrameworkType && selectedFrameworkType.length > 0) {
                        var regex = selectedFrameworkType.map(function(val) {
                            return $.fn.dataTable.util.escapeRegex(val);
                        }).join("|");
                        table.column(1).search(regex, true, false).draw();
                    } else {
                        table.column(1).search("").draw();
                    }
                }
            });
        }
    });
</script>



