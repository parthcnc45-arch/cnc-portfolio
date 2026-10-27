<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php');

date_default_timezone_set('Asia/Kolkata');

if (!isset($_REQUEST['id'])) {
    header('location: clients.php');
    exit;
}
$id = (int)$_REQUEST['id'];

$message = '';
$form_data = [];

$result = mysqli_query($conn, "SELECT * FROM clients WHERE `id`=$id AND `deleted`='0'");
$count = mysqli_num_rows($result);
if ($count < 1) {
    echo "<script> location.href='clients.php'; </script>";
    exit;
}
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-client'])) {
    $client_name       = trim($_POST['client_name'] ?? '');
    $email_address     = trim($_POST['email_address'] ?? '');
    $phone_number      = trim($_POST['phone_number'] ?? '');
    $city              = trim($_POST['city'] ?? '');
    $state             = trim($_POST['state'] ?? '');
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

    $form_data = $_POST;
    if (!empty($country)) {
        $form_data['country'] = $country;
    }

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
        $stmt = $conn->prepare("UPDATE clients SET
            client_name=?, client_type=?, company_name=?, company_website=?, industry=?, niche=?, social_profiles=?,
            associate_manager=?, lead_source=?, lead_type=?, client_status=?, contract_start_date=?, contract_end_date=?,
            notes=?, client_feedback=?, phone_number=?, email_address=?, state=?, city=?, country=?
            WHERE id=?");
        if ($stmt) {
            $stmt->bind_param(
                "ssssssssssssssssssssi",
                $client_name, $client_type, $company_name, $company_website, $industry, $niche, $social_profiles,
                $associate_manager, $lead_source, $lead_type, $client_status, $contract_start_date, $contract_end_date,
                $notes, $client_feedback, $phone_number, $email_address, $state, $city, $country, $id
            );
            if ($stmt->execute()) {
                echo "<script>location.href='clients.php?success=updated';</script>";
                exit;
            } else {
                error_log("Failed to update client: " . $stmt->error);
                $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <i class="fa fa-exclamation-circle mr-2"></i> <strong>Error:</strong> Failed to update client data.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>';
            }
            $stmt->close();
        }
    }
}

if (empty($form_data)) {
    $form_data = $row;
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<style>
    /* reuse add-client styles */
</style>
<?php
// include add-client styles by reading file? instead just output minimal; reuse via include not possible. We'll rely on same classes; but styles absent. Better: copy style block from add-client via output? Use include of a css file? None. We'll just include the style by reading add-client and extracting.
$add = file_get_contents('add-client.php');
if (preg_match('/<style>(.*?)<\/style>/s', $add, $m)) {
    echo "<style>" . $m[1] . "</style>";
}
?>
<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <h3>Edit Client</h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="clients.php">Clients</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Client</a></li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <a href="clients.php" class="btn-back-clients">
            <i class="fa fa-arrow-left"></i> <span>Back to Clients</span>
        </a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card client-form-card">
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="mb-4"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <div class="form-validation">
                            <form id="editClientForm" action="edit-client.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <?php
                                    // Reuse add-client form fields by generating with $form_data values
                                    $fields = [
                                        ['client_name','Client Name','text',true],
                                    ];
                                    ?>
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
                                            $industries = ["Agriculture, Forestry, Fishing, and Hunting","Mining, Quarrying, and Oil & Gas Extraction","Construction","Manufacturing","Wholesale Trade","Retail Trade","Transportation and Warehousing","Information Technology and Communications","Finance and Insurance","Professional, Scientific, and Technical Services","Healthcare and Social Assistance","Education Services","Arts, Entertainment, and Recreation","Accommodation and Food Services"];
                                            ?>
                                            <select id="industry" name="industry" class="select2 form-control">
                                                <?php foreach ($industries as $ind): ?>
                                                    <option value="<?php echo htmlspecialchars($ind); ?>" <?php echo ($current_industry === $ind) ? 'selected' : ''; ?>><?php echo htmlspecialchars($ind); ?></option>
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
                                            <?php $current_lead_source = $form_data['lead_source'] ?? 'Referral'; $lead_sources = ['Referral','Website','Cold Call','Offline','Upwork']; ?>
                                            <select id="lead_source" name="lead_source" class="select2 form-control">
                                                <?php foreach ($lead_sources as $src): ?>
                                                    <option value="<?php echo htmlspecialchars($src); ?>" <?php echo ($current_lead_source === $src) ? 'selected' : ''; ?>><?php echo htmlspecialchars($src); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="lead_type">Lead Type</label>
                                        <div class="form-control-group">
                                            <?php $current_lead_type = $form_data['lead_type'] ?? 'Inbound'; $lead_types = ['Inbound','Outbound']; ?>
                                            <select id="lead_type" name="lead_type" class="select2 form-control">
                                                <?php foreach ($lead_types as $lt): ?>
                                                    <option value="<?php echo htmlspecialchars($lt); ?>" <?php echo ($current_lead_type === $lt) ? 'selected' : ''; ?>><?php echo htmlspecialchars($lt); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-12">
                                        <label class="col-form-label" for="client_status">Client Status</label>
                                        <div class="form-control-group">
                                            <?php $current_client_status = $form_data['client_status'] ?? 'Active'; $client_statuses = ['Active','Lost']; ?>
                                            <select id="client_status" name="client_status" class="select2 form-control">
                                                <?php foreach ($client_statuses as $cs): ?>
                                                    <option value="<?php echo htmlspecialchars($cs); ?>" <?php echo ($current_client_status === $cs) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cs); ?></option>
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
                                        <button type="submit" name="update-client" class="btn-submit-client">
                                            <i class="fa fa-save"></i> <span>Update</span>
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
</div>
<?php include_once('footer.php'); ?>
<script src="./plugins/validation/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ width: '100%' });
    const countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Bulgaria","Burkina Faso","Burundi","Côte d'Ivoire","Cabo Verde","Cambodia","Cameroon","Canada","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo (Congo-Brazzaville)","Costa Rica","Croatia","Cuba","Cyprus","Czechia (Czech Republic)","Democratic Republic of the Congo","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini (fmr. 'Swaziland')","Ethiopia","Fiji","Finland","France","Gabon","Gambia","Georgia","Germany","Ghana","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Holy See","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Myanmar (formerly Burma)","Namibia","Nauru","Nepal","Netherlands","New Zealand","Nicaragua","Niger","Nigeria","North Korea","North Macedonia","Norway","Oman","Pakistan","Palau","Palestine State","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Qatar","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Yemen","Zambia","Zimbabwe"];
    $("#countrys").autocomplete({ source: countries, minLength: 1 });
});
</script>
