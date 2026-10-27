<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php');

// Set timezone to India (Asia/Kolkata) or system default
date_default_timezone_set('Asia/Kolkata');

$message = '';
$form_data = [];

// ----------------------------------------------------
// Handle Client Form Submission
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert-client'])) {
    $client_name       = trim($_POST['client_name'] ?? '');
    $email_address     = trim($_POST['email_address'] ?? '');
    $phone_number      = trim($_POST['phone_number'] ?? '');
    $city              = trim($_POST['city'] ?? '');
    $state             = trim($_POST['state'] ?? '');
    // Support both 'country' and legacy 'countrys'
    $country           = trim($_POST['country'] ?? $_POST['countrys'] ?? '');
    $client_type       = trim($_POST['client_type'] ?? 'B2B');
    $company_name      = trim($_POST['company_name'] ?? '');
    $company_website   = trim($_POST['company_website'] ?? '');
    $industry          = trim($_POST['industry'] ?? '');
    $niche             = trim($_POST['niche'] ?? '');
    $social_profiles   = trim($_POST['social_profiles'] ?? '');
    $associate_manager = trim($_POST['associate_manager'] ?? '');
    $lead_source       = trim($_POST['lead_source'] ?? 'Referral');
    $lead_type         = trim($_POST['lead_type'] ?? 'Inbound');
    $client_status     = trim($_POST['client_status'] ?? 'Active');
    $contract_start_date = !empty($_POST['contract_start_date']) ? trim($_POST['contract_start_date']) : null;
    $contract_end_date   = !empty($_POST['contract_end_date']) ? trim($_POST['contract_end_date']) : null;
    $notes             = trim($_POST['notes'] ?? '');
    $client_feedback   = trim($_POST['client_feedback'] ?? '');
    $deleted           = 0;
    $created_at        = date('Y-m-d H:i:s');

    // Retain form values in case of validation/submission failure
    $form_data = $_POST;
    if (!empty($country)) {
        $form_data['country'] = $country;
    }

    // Validation
    if (empty($client_name)) {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
            <i class="fa fa-exclamation-triangle mr-2"></i> <strong>Validation Error:</strong> Client Name is required.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
    } elseif (!empty($email_address) && !filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
            <i class="fa fa-exclamation-triangle mr-2"></i> <strong>Validation Error:</strong> Please enter a valid email address.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>';
    } else {
        // Handle document file uploads
        $documents_str = null;
        if (isset($_FILES['documents_upload']) && !empty($_FILES['documents_upload']['name'])) {
            $upload_dir = __DIR__ . '/uploads/client_documents/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }

            $saved_files = [];
            $names = $_FILES['documents_upload']['name'];
            $tmps  = $_FILES['documents_upload']['tmp_name'];
            $errs  = $_FILES['documents_upload']['error'];

            if (is_array($names)) {
                for ($k = 0; $k < count($names); $k++) {
                    if (isset($errs[$k]) && $errs[$k] === UPLOAD_ERR_OK && !empty($tmps[$k])) {
                        $orig_name = basename($names[$k]);
                        $clean_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $orig_name);
                        $target_filename = time() . '_' . uniqid() . '_' . $clean_name;
                        $destination = $upload_dir . $target_filename;
                        if (move_uploaded_file($tmps[$k], $destination)) {
                            $saved_files[] = 'uploads/client_documents/' . $target_filename;
                        }
                    }
                }
            } else {
                if ($errs === UPLOAD_ERR_OK && !empty($tmps)) {
                    $orig_name = basename($names);
                    $clean_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $orig_name);
                    $target_filename = time() . '_' . uniqid() . '_' . $clean_name;
                    $destination = $upload_dir . $target_filename;
                    if (move_uploaded_file($tmps, $destination)) {
                        $saved_files[] = 'uploads/client_documents/' . $target_filename;
                    }
                }
            }

            if (!empty($saved_files)) {
                $documents_str = implode(',', $saved_files);
            }
        }

        // Insert into database using prepared statement
        $stmt = $conn->prepare("INSERT INTO clients (
            client_name,
            client_type,
            company_name,
            company_website,
            industry,
            niche,
            social_profiles,
            associate_manager,
            lead_source,
            lead_type,
            client_status,
            contract_start_date,
            contract_end_date,
            documents_upload,
            notes,
            client_feedback,
            phone_number,
            email_address,
            state,
            city,
            country,
            deleted,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param(
                "sssssssssssssssssssssis",
                $client_name,
                $client_type,
                $company_name,
                $company_website,
                $industry,
                $niche,
                $social_profiles,
                $associate_manager,
                $lead_source,
                $lead_type,
                $client_status,
                $contract_start_date,
                $contract_end_date,
                $documents_str,
                $notes,
                $client_feedback,
                $phone_number,
                $email_address,
                $state,
                $city,
                $country,
                $deleted,
                $created_at
            );

            if ($stmt->execute()) {
                echo "<script>location.href='clients.php?success=added';</script>";
                exit;
            } else {
                error_log("Failed to insert client: " . $stmt->error);
                $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fa fa-exclamation-circle mr-2"></i> <strong>Error:</strong> Failed to save client data. Please check your inputs and try again.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare client insert statement: " . $conn->error);
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fa fa-exclamation-circle mr-2"></i> <strong>Error:</strong> An internal database error occurred. Please try again later.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>';
        }
    }
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<style>
    /* =========================================================
       STANDARDIZED CLIENT FORM STYLING
       ========================================================= */
    .content-body {
        background-color: #f7f8fb !important;
        min-height: 100vh;
        padding-bottom: 40px;
    }
    .btn-back-clients {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 14px;
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.15s ease;
        margin-bottom: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .btn-back-clients:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .client-form-card {
        background: #ffffff;
        border: 1.5px solid #e8ecf4 !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
    }
    .client-form-card .card-body {
        padding: 28px 32px !important;
    }

    /* Form Grid & Group Spacing */
    .form-validation .form-group {
        margin-bottom: 20px !important;
    }

    /* Consistent Labels */
    .form-validation .col-form-label,
    .form-validation label {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        margin-bottom: 7px !important;
        padding: 0 !important;
        display: inline-block !important;
        line-height: 1.3 !important;
    }
    .form-validation label .text-danger {
        color: #ef4444 !important;
        font-weight: 700;
    }

    /* Standard Single-Line Input Controls (Height: 40px) */
    .form-validation input[type="text"].form-control,
    .form-validation input[type="email"].form-control,
    .form-validation input[type="tel"].form-control,
    .form-validation input[type="date"].form-control,
    .form-validation select.form-control {
        height: 40px !important;
        min-height: 40px !important;
        padding: 8px 12px !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        box-sizing: border-box !important;
        width: 100% !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }

    /* Native Select Chevron */
    .form-validation select.form-control {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        padding-right: 30px !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM2NDc0OGIiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 10px center !important;
        background-size: 10px 10px !important;
        cursor: pointer !important;
    }

    /* File Input Styling */
    .form-validation input[type="file"].form-control {
        height: 40px !important;
        min-height: 40px !important;
        padding: 4px 6px !important;
        font-size: 13px !important;
        color: #475569 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        box-sizing: border-box !important;
        width: 100% !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        overflow: hidden !important;
    }
    .form-validation input[type="file"].form-control::-webkit-file-upload-button {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 500;
        margin-right: 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        height: 30px;
        line-height: 18px;
    }
    .form-validation input[type="file"].form-control:hover::-webkit-file-upload-button {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* Textarea Styling (Notes & Client Feedback) */
    .form-validation textarea.form-control {
        height: 95px !important;
        min-height: 95px !important;
        padding: 10px 12px !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        box-sizing: border-box !important;
        width: 100% !important;
        line-height: 1.5 !important;
        resize: vertical !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }

    /* Focus States for all form elements */
    .form-validation input[type="text"].form-control:focus,
    .form-validation input[type="email"].form-control:focus,
    .form-validation input[type="tel"].form-control:focus,
    .form-validation input[type="date"].form-control:focus,
    .form-validation input[type="file"].form-control:focus,
    .form-validation select.form-control:focus,
    .form-validation textarea.form-control:focus,
    .form-validation .select2-container--default.select2-container--focus .select2-selection--single,
    .form-validation .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.15) !important;
        outline: none !important;
        background-color: #ffffff !important;
    }

    /* Placeholders */
    .form-validation .form-control::placeholder {
        color: #94a3b8 !important;
        font-size: 13px !important;
    }

    /* Select2 Container Standardization */
    .form-validation .select2-container {
        width: 100% !important;
        max-width: 100% !important;
    }
    .form-validation .select2-container--default .select2-selection--single {
        height: 40px !important;
        min-height: 40px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 12px !important;
        box-sizing: border-box !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease !important;
    }
    .form-validation .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 0 !important;
        padding-right: 20px !important;
        color: #1e293b !important;
        font-size: 13.5px !important;
        font-weight: 400 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .form-validation .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
        right: 8px !important;
        top: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .form-validation .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #64748b transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
    }
    .form-validation .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #64748b transparent !important;
        border-width: 0 4px 5px 4px !important;
    }
    .select2-dropdown {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 7px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        font-size: 13.5px !important;
        z-index: 1060 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5b52f9 !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f1f5f9 !important;
        color: #5b52f9 !important;
        font-weight: 600 !important;
    }

    /* Action Buttons */
    .btn-submit-client {
        height: 40px;
        padding: 0 24px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 7px;
        background: #5b52f9;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border: none;
        box-shadow: 0 2px 8px rgba(91, 82, 249, 0.25);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-submit-client:hover {
        background: #483fe6;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(91, 82, 249, 0.35);
    }
    .btn-cancel-client {
        height: 40px;
        padding: 0 22px;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 7px;
        background: #ffffff;
        color: #475569 !important;
        border: 1.5px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }
    .btn-cancel-client:hover {
        background: #f8fafc;
        color: #0f172a !important;
        border-color: #cbd5e1;
    }

    /* Validation Errors */
    .invalid-feedback {
        display: block;
        font-size: 12px;
        color: #ef4444;
        margin-top: 4px;
    }
    .form-group.is-invalid input,
    .form-group.is-invalid select,
    .form-group.is-invalid textarea,
    .form-group.is-invalid .select2-selection {
        border-color: #ef4444 !important;
    }
    .form-group.is-invalid input:focus,
    .form-group.is-invalid select:focus,
    .form-group.is-invalid textarea:focus,
    .form-group.is-invalid .select2-selection:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    }
    .ui-autocomplete {
        max-height: 220px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1050 !important;
        font-size: 13px;
        border-radius: 6px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
    }

    /* Responsive Grid Layout */
    @media (min-width: 992px) {
        .form-validation .col-xl-3,
        .form-validation .col-lg-3 {
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        .form-validation .col-xl-6,
        .form-validation .col-lg-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
    }
    @media (min-width: 576px) and (max-width: 991.98px) {
        .form-validation .col-md-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        .form-validation .col-md-12 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
    @media (max-width: 575.98px) {
        .form-validation .form-group {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
        .client-form-card .card-body {
            padding: 20px 16px !important;
        }
    }
</style>

<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <h3>Add Client</h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="clients.php">Clients</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Client</a></li>
            </ol>
        </div>
    </div>

    <!-- Container -->
    <div class="container-fluid">
        <a href="clients.php" class="btn-back-clients">
            <i class="fa fa-arrow-left"></i> <span>Back to Clients</span>
        </a>

        <div class="row">
            <div class="col-lg-12">
                <div class="card client-form-card">
                    <div class="card-body">
                        <!-- Alert / Notification Message -->
                        <?php if (!empty($message)): ?>
                            <div class="mb-4">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-validation">
                            <form id="addClientForm" action="add-client.php" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="client_name">Client Name <span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Enter Client Name" value="<?php echo htmlspecialchars($form_data['client_name'] ?? ''); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="email_address">Email Address</label>
                                        <div class="form-control-group">
                                            <input type="email" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address" value="<?php echo htmlspecialchars($form_data['email_address'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="phone_number">Phone Number</label>
                                        <div class="form-control-group">
                                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter Phone Number" value="<?php echo htmlspecialchars($form_data['phone_number'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="city">City</label>
                                        <div class="form-control-group">
                                            <input type="text" id="city" name="city" class="form-control" placeholder="Enter City" value="<?php echo htmlspecialchars($form_data['city'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="state">State</label>
                                        <div class="form-control-group">
                                            <input type="text" id="state" name="state" class="form-control" placeholder="Enter State" value="<?php echo htmlspecialchars($form_data['state'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="countrys">Country</label>
                                        <div class="form-control-group">
                                            <input type="text" id="countrys" name="country" class="form-control" placeholder="Enter Country" value="<?php echo htmlspecialchars($form_data['country'] ?? $form_data['countrys'] ?? ''); ?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="client_type">Client Type</label>
                                        <div class="form-control-group">
                                            <select id="client_type" name="client_type" class="select2 form-control">
                                                <option value="B2B" <?php echo (($form_data['client_type'] ?? 'B2B') === 'B2B') ? 'selected' : ''; ?>>B2B</option>
                                                <option value="B2C" <?php echo (($form_data['client_type'] ?? '') === 'B2C') ? 'selected' : ''; ?>>B2C</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="company_name">Company Name</label>
                                        <div class="form-control-group">
                                            <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name" value="<?php echo htmlspecialchars($form_data['company_name'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="company_website">Company Website</label>
                                        <div class="form-control-group">
                                            <input type="text" id="company_website" name="company_website" class="form-control" placeholder="Enter Company Website" value="<?php echo htmlspecialchars($form_data['company_website'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="industry">Industry</label>
                                        <div class="form-control-group">
                                            <?php
                                            $current_industry = $form_data['industry'] ?? 'Agriculture, Forestry, Fishing, and Hunting';
                                            $industries = [
                                                "Agriculture, Forestry, Fishing, and Hunting",
                                                "Mining, Quarrying, and Oil & Gas Extraction",
                                                "Construction",
                                                "Manufacturing",
                                                "Wholesale Trade",
                                                "Retail Trade",
                                                "Transportation and Warehousing",
                                                "Information Technology and Communications",
                                                "Finance and Insurance",
                                                "Professional, Scientific, and Technical Services",
                                                "Healthcare and Social Assistance",
                                                "Education Services",
                                                "Arts, Entertainment, and Recreation",
                                                "Accommodation and Food Services"
                                            ];
                                            ?>
                                            <select id="industry" name="industry" class="select2 form-control">
                                                <?php foreach ($industries as $ind): ?>
                                                    <option value="<?php echo htmlspecialchars($ind); ?>" <?php echo ($current_industry === $ind) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($ind); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="niche">Niche</label>
                                        <div class="form-control-group">
                                            <input type="text" id="niche" name="niche" class="form-control" placeholder="Enter Niche" value="<?php echo htmlspecialchars($form_data['niche'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="social_profiles">Social Profiles</label>
                                        <div class="form-control-group">
                                            <input type="text" id="social_profiles" name="social_profiles" class="form-control" placeholder="Enter Social Profiles" value="<?php echo htmlspecialchars($form_data['social_profiles'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="associate_manager">Associate Manager</label>
                                        <div class="form-control-group">
                                            <input type="text" id="associate_manager" name="associate_manager" class="form-control" placeholder="Enter Associate Manager" value="<?php echo htmlspecialchars($form_data['associate_manager'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="lead_source">Lead Source</label>
                                        <div class="form-control-group">
                                            <?php
                                            $current_lead_source = $form_data['lead_source'] ?? 'Referral';
                                            $lead_sources = ['Referral', 'Website', 'Cold Call', 'Offline', 'Upwork'];
                                            ?>
                                            <select id="lead_source" name="lead_source" class="select2 form-control">
                                                <?php foreach ($lead_sources as $src): ?>
                                                    <option value="<?php echo htmlspecialchars($src); ?>" <?php echo ($current_lead_source === $src) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($src); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="lead_type">Lead Type</label>
                                        <div class="form-control-group">
                                            <?php
                                            $current_lead_type = $form_data['lead_type'] ?? 'Inbound';
                                            $lead_types = ['Inbound', 'Outbound'];
                                            ?>
                                            <select id="lead_type" name="lead_type" class="select2 form-control">
                                                <?php foreach ($lead_types as $lt): ?>
                                                    <option value="<?php echo htmlspecialchars($lt); ?>" <?php echo ($current_lead_type === $lt) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($lt); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="client_status">Client Status</label>
                                        <div class="form-control-group">
                                            <?php
                                            $current_client_status = $form_data['client_status'] ?? 'Active';
                                            $client_statuses = ['Active', 'Lost'];
                                            ?>
                                            <select id="client_status" name="client_status" class="select2 form-control">
                                                <?php foreach ($client_statuses as $cs): ?>
                                                    <option value="<?php echo htmlspecialchars($cs); ?>" <?php echo ($current_client_status === $cs) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($cs); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="contract_start_date">Contract Start Date</label>
                                        <div class="form-control-group">
                                            <input type="date" id="contract_start_date" name="contract_start_date" class="form-control" value="<?php echo htmlspecialchars($form_data['contract_start_date'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="contract_end_date">Contract End Date</label>
                                        <div class="form-control-group">
                                            <input type="date" id="contract_end_date" name="contract_end_date" class="form-control" value="<?php echo htmlspecialchars($form_data['contract_end_date'] ?? ''); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-3 col-lg-3 col-md-12 col-12">
                                        <label class="col-form-label" for="documents_upload">Documents Upload</label>
                                        <div class="form-control-group">
                                            <input type="file" id="documents_upload" name="documents_upload[]" class="form-control" multiple>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">
                                        <label class="col-form-label" for="notes">Notes</label>
                                        <div class="form-control-group">
                                            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Enter notes"><?php echo htmlspecialchars($form_data['notes'] ?? ''); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-12">
                                        <label class="col-form-label" for="client_feedback">Client Feedback</label>
                                        <div class="form-control-group">
                                            <textarea id="client_feedback" name="client_feedback" class="form-control" rows="3" placeholder="Enter Client Feedback"><?php echo htmlspecialchars($form_data['client_feedback'] ?? ''); ?></textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row mt-3 mb-0">
                                    <div class="col-lg-12 d-flex align-items-center">
                                        <button type="submit" name="insert-client" class="btn-submit-client">
                                            <i class="fa fa-save"></i> <span>Submit</span>
                                        </button>
                                        <a href="clients.php" class="btn-cancel-client ml-3">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>

<?php
include_once('footer.php');
?>
<!-- Validation & Autocomplete Plugins -->
<script src="./plugins/validation/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 dropdowns
    $('.select2').select2({
        width: '100%'
    });

    // Country List for Autocomplete
    const countries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina",
        "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados",
        "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana",
        "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", "Cambodia",
        "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
        "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)",
        "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini (fmr. 'Swaziland')",
        "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece",
        "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras",
        "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica",
        "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia",
        "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar",
        "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius",
        "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
        "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua",
        "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau",
        "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland",
        "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
        "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia",
        "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia",
        "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka",
        "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Tajikistan", "Tanzania", "Thailand",
        "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan",
        "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America",
        "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    ];

    $("#countrys").autocomplete({
        source: countries,
        minLength: 1
    });

    // Form Client-Side Validation
    $("#addClientForm").validate({
        ignore: [],
        errorClass: "invalid-feedback animated fadeInDown",
        errorElement: "div",
        errorPlacement: function(error, element) {
            $(element).closest(".form-control-group").append(error);
        },
        highlight: function(element) {
            $(element).closest(".form-group").addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).closest(".form-group").removeClass("is-invalid");
        },
        rules: {
            "client_name": {
                required: true,
                minlength: 2
            },
            "email_address": {
                email: true
            }
        },
        messages: {
            "client_name": {
                required: "Please enter the client name",
                minlength: "Client name must be at least 2 characters long"
            },
            "email_address": {
                email: "Please enter a valid email address"
            }
        }
    });

    <?php if (!empty($message) && strpos($message, 'alert-success') !== false): ?>
    // Toast notification on success
    if (typeof Toastify === "function") {
        Toastify({
            text: "Client saved successfully!",
            duration: 3500,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#10b981",
            stopOnFocus: true
        }).showToast();
    }
    <?php endif; ?>
});
</script>