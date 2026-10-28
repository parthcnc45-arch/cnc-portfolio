<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php'); // This file contains your DB connection (e.g., $conn)
// set timezone to India (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');
$message = "";
$is_edit_mode = false;
$industrie_name_value = "";

// -----------------------
// Handle Delete Request
// -----------------------
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM industries WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Industry Deleted successfully!</div>';
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
    $stmt = $conn->prepare("SELECT * FROM industries WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $industrie_name_value = $row['industrie_name'];
        $is_edit_mode = true;
    } else {
        $message = '<div class="alert alert-danger">Industry not found!</div>';
    }
    $stmt->close();
}

// -----------------------
// Handle Form Submission
// -----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*1. Insert New Industry
    if (isset($_POST['insert-industrie'])) {
        $industrie_name = trim($_POST['industrie_name']);
        if (empty($industrie_name)) {
            $message = '<div class="alert alert-danger">Industry name is required!</div>';
        } else {
            $current_time = date("Y-m-d H:i:s");
            // Insert both the industry name and the timestamp
            $stmt = $conn->prepare("INSERT INTO industries (industrie_name, created_at) VALUES (?, ?)");
            $stmt->bind_param("ss", $industrie_name, $current_time);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Industry added successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }*/
    // 1. Insert New Industry
    if (isset($_POST['insert-industrie'])) {
        $industrie_name = trim($_POST['industrie_name']);
    
        if (empty($industrie_name)) {
            $message = '<div class="alert alert-danger">Industry name is required!</div>';
        } else {
            // Check if industry name already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM industries WHERE industrie_name = ?");
            $stmt->bind_param("s", $industrie_name);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
    
            if ($count > 0) {
                $message = '<div class="alert alert-warning">This industry already exists!</div>';
            } else {
                $current_time = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO industries (industrie_name, created_at) VALUES (?, ?)");
                $stmt->bind_param("ss", $industrie_name, $current_time);
    
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Industry added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }
        }
    }
    
    // 2. Update Existing Industry
    elseif (isset($_POST['update-industrie'])) {
        $industrie_name = trim($_POST['industrie_name']);
        $industrie_id = intval($_POST['industrie_id']);
        if (empty($industrie_name)) {
            $message = '<div class="alert alert-danger">Industry name is required!</div>';
        } else {
            $stmt = $conn->prepare("UPDATE industries SET industrie_name = ? WHERE id = ?");
            $stmt->bind_param("si", $industrie_name, $industrie_id);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Industry updated successfully!</div>';
                // Reset edit mode after update
                $is_edit_mode = false;
                $industrie_name_value = "";
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
   ADD INDUSTRIES RESPONSIVE & MODERN UI STYLES
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
.industry-form-card {
    background: #ffffff !important;
    border: 1.5px solid #eef2f6 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03) !important;
    margin-bottom: 20px !important;
    overflow: hidden;
}
.industry-form-card .card-body {
    padding: 22px 24px !important;
}
.industry-form-card .col-form-label {
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    margin-bottom: 6px !important;
}
.industry-form-card .form-control {
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
.industry-form-card .form-control:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    outline: none !important;
}
.industry-form-card .form-control::placeholder {
    color: #94a3b8 !important;
    font-size: 13.5px !important;
}

/* Buttons */
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
.industry-table-card {
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
.industry-table-responsive {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}

/* DataTables Controls Standardization */
#indistrie-table_wrapper {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
}

/* Row 1: Top Toolbar (Show entries + Search) */
#indistrie-table_wrapper > .row:nth-child(1) {
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
#indistrie-table_wrapper > .row:nth-child(1) > [class*="col-"] {
    padding: 0 !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: 100% !important;
}
#indistrie-table_wrapper .dataTables_length {
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
}
#indistrie-table_wrapper .dataTables_length label {
    display: inline-flex !important;
    align-items: center !important;
    margin-bottom: 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    gap: 6px !important;
}
#indistrie-table_wrapper .dataTables_length select {
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
#indistrie-table_wrapper .dataTables_length select:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
}
#indistrie-table_wrapper .dataTables_filter {
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
}
#indistrie-table_wrapper .dataTables_filter label {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #64748b !important;
    gap: 8px !important;
}
#indistrie-table_wrapper .dataTables_filter input {
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
#indistrie-table_wrapper .dataTables_filter input:focus {
    border-color: #5b52f9 !important;
    box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    width: 230px !important;
}

/* Table Style Inside Card */
#indistrie-table {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: collapse !important;
    border: none !important;
}
#indistrie-table thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 12px 16px !important;
    border-top: none !important;
    border-bottom: 1.5px solid #e2e8f0 !important;
    border-left: none !important;
    border-right: none !important;
    vertical-align: middle !important;
}
#indistrie-table tbody tr {
    transition: background-color 0.15s ease !important;
}
#indistrie-table tbody tr:hover {
    background-color: #f8fafc !important;
}
#indistrie-table tbody td {
    padding: 12px 16px !important;
    vertical-align: middle !important;
    font-size: 13.5px !important;
    color: #334155 !important;
    border-top: 1px solid #f1f5f9 !important;
    border-bottom: 1px solid #f1f5f9 !important;
    border-left: none !important;
    border-right: none !important;
}
.industry-name-cell {
    font-weight: 500 !important;
    color: #1e293b !important;
}
.industry-time-cell {
    font-size: 12.5px !important;
    color: #64748b !important;
    white-space: nowrap !important;
}

/* Action Buttons */
.action-btn-group {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    white-space: nowrap !important;
}
.btn-action-edit {
    display: inline-flex !important;
    align-items: center !important;
    padding: 5px 11px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    background-color: #5b52f9 !important;
    border: 1px solid #5b52f9 !important;
    border-radius: 6px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}
.btn-action-edit:hover {
    background-color: #483fe6 !important;
    border-color: #483fe6 !important;
    color: #ffffff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 6px rgba(91, 82, 249, 0.3) !important;
}
.btn-action-delete {
    display: inline-flex !important;
    align-items: center !important;
    padding: 5px 11px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    background-color: #ef4444 !important;
    border: 1px solid #ef4444 !important;
    border-radius: 6px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}
.btn-action-delete:hover {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    color: #ffffff !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3) !important;
}

/* Row 3: Bottom Toolbar (Info + Pagination) */
#indistrie-table_wrapper > .row:last-child {
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
#indistrie-table_wrapper > .row:last-child > [class*="col-"] {
    padding: 0 !important;
    flex: 0 0 auto !important;
    width: auto !important;
    max-width: 100% !important;
}
#indistrie-table_wrapper .dataTables_info {
    margin: 0 !important;
    padding: 0 !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    color: #64748b !important;
}
#indistrie-table_wrapper .dataTables_paginate {
    margin: 0 !important;
    padding: 0 !important;
}
#indistrie-table_wrapper .pagination {
    margin: 0 !important;
    gap: 3px !important;
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
}
#indistrie-table_wrapper .page-item .page-link {
    border-radius: 6px !important;
    border: 1px solid #e2e8f0 !important;
    color: #475569 !important;
    font-size: 12.5px !important;
    font-weight: 500 !important;
    padding: 6px 11px !important;
    line-height: 1.3 !important;
    background-color: #ffffff !important;
    transition: all 0.15s ease !important;
    outline: none !important;
}
#indistrie-table_wrapper .page-item .page-link:hover {
    background-color: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}
#indistrie-table_wrapper .page-item.active .page-link {
    background-color: #5b52f9 !important;
    border-color: #5b52f9 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 6px rgba(91, 82, 249, 0.3) !important;
}
#indistrie-table_wrapper .page-item.disabled .page-link {
    color: #94a3b8 !important;
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed !important;
}

/* =========================================================
   RESPONSIVE MEDIA QUERIES (Mobile < 768px)
   ========================================================= */
@media (max-width: 767.98px) {
    .content-body {
        padding: 12px 8px 24px !important;
        overflow-x: clip !important;
    }
    .page-titles {
        padding: 10px 8px 6px 8px !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 4px !important;
    }
    .page-titles h3 {
        font-size: 18px !important;
    }
    .container-fluid {
        padding-left: 6px !important;
        padding-right: 6px !important;
    }
    .industry-form-card .card-body {
        padding: 16px 12px !important;
    }
    .btn-submit-custom, .btn-cancel-custom {
        width: 100% !important;
    }
    #indistrie-table_wrapper > .row:nth-child(1) {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 10px !important;
        padding: 12px 10px !important;
    }
    #indistrie-table_wrapper .dataTables_length,
    #indistrie-table_wrapper .dataTables_filter {
        width: 100% !important;
        display: block !important;
    }
    #indistrie-table_wrapper .dataTables_length label,
    #indistrie-table_wrapper .dataTables_filter label {
        width: 100% !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    #indistrie-table_wrapper .dataTables_filter input {
        width: 100% !important;
        flex: 1 1 auto !important;
    }
    #indistrie-table_wrapper > .row:last-child {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center !important;
        gap: 10px !important;
        padding: 12px 8px !important;
    }
    #indistrie-table_wrapper .dataTables_info {
        text-align: center !important;
        width: 100% !important;
    }
    #indistrie-table_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
    }
}
</style>

<div class="content-body">
    <!-- Page Header -->
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <h3><?php echo $is_edit_mode ? "Edit Industry" : "Add Industries"; ?></h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)"><?php echo $is_edit_mode ? "Edit Industry" : "Add Industries"; ?></a>
                </li>
            </ol>
        </div>
    </div>

    <!-- Form Section -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card industry-form-card">
                    <div class="card-body">
                        <div class="form-validation">
                            <!-- Display success or error messages -->
                            <?php echo $message; ?>
                            <form class="form-valide" action="add-industries.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-12 col-md-6 col-lg-4">
                                        <label class="col-form-label" for="industrie_name">Industrie Name<span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <input type="text" id="industrie_name" name="industrie_name" class="form-control" placeholder="Enter Industrie Name" value="<?php echo htmlspecialchars($industrie_name_value); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <input type="hidden" name="industrie_id" value="<?php echo $edit_id; ?>">
                                    <div class="form-group row mb-0">
                                        <div class="col-12 d-flex flex-wrap align-items-center" style="gap: 10px;">
                                            <button type="submit" name="update-industrie" class="btn btn-primary btn-submit-custom">Update Industry</button>
                                            <a href="add-industries.php" class="btn btn-cancel-custom">Cancel</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group row mb-0">
                                        <div class="col-12">
                                            <button type="submit" name="insert-industrie" class="btn btn-primary btn-submit-custom">Submit</button>
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

    <!-- Industries Table Section -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card industry-table-card">
                    <div class="card-body px-0 py-0" id="indistrie_table_print">
                        <div class="table-scroll-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="12" x2="6" y2="12"></line><polyline points="10 18 4 12 10 6"></polyline></svg>
                            <span>Scroll horizontally to view columns</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="18" y2="12"></line><polyline points="14 6 20 12 14 18"></polyline></svg>
                        </div>
                        <div class="table-responsive industry-table-responsive">
                            <table class="table table-striped table-bordered" id="indistrie-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 140px;">Industrie Name</th>
                                        <th style="min-width: 160px;">Timestamp</th>
                                        <th style="min-width: 120px; text-align: center;">Action</th>
                                    </tr>
                                 </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM industries ORDER BY id DESC";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td class='industry-name-cell'>{$row['industrie_name']}</td>
                                                    <td class='industry-time-cell'>{$row['created_at']}</td>
                                                    <td class='text-center'>
                                                        <div class='action-btn-group'>
                                                            <a href='add-industries.php?edit={$row['id']}' class='btn-action-edit' title='Edit Industry'>
                                                                <i class='fa fa-pencil mr-1'></i>Edit
                                                            </a>
                                                            <a href='add-industries.php?delete={$row['id']}' class='btn-action-delete' onclick=\"return confirm('Are you sure you want to delete this industry?');\" title='Delete Industry'>
                                                                <i class='fa fa-trash mr-1'></i>Delete
                                                            </a>
                                                        </div>
                                                    </td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center py-4 text-muted'>No industries added yet.</td></tr>";
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
</div>

<?php
include_once('footer.php');
$conn->close();
?>
<script>
    $(document).ready(function () {
        // Industry duplicate check on blur
        $("#industrie_name").on("blur", function () {
            let industrie_name = $(this).val().trim();
            if (industrie_name !== "") {
                $.ajax({
                    url: "ajax_functions.php",
                    type: "POST",
                    data: { industrie_name: industrie_name },
                    success: function (response) {
                        if (response === "exists") {
                            alert("This industry already exists!");
                            $("#industrie_name").val("");
                        }
                    }
                });
            }
        });

        // Initialize / Enhance DataTable
        if ($.fn.DataTable) {
            $.fn.DataTable.ext.pager.numbers_length = 4;
            if ($.fn.DataTable.isDataTable('#indistrie-table')) {
                $('#indistrie-table').DataTable().destroy();
            }
            $('#indistrie-table').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: false,
                order: [[1, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search industries...",
                    lengthMenu: "Show _MENU_ entries"
                }
            });
        }
    });
</script>

