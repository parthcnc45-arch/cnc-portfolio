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
<!-- Include external CSS files -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

<style>
    .bootstrap-select .dropdown-toggle .filter-option {
        background-color: #fff;
        border: 1px solid #ced4da;
    }
    .tags-input-wrapper .tag a {
        margin: 0 7px 3px;
        display: inline-block;
        cursor: pointer;
    }
    .tags-input-wrapper .tag {
        display: inline-block;
        background-color: #7571f9;
        color: white;
        border-radius: 40px;
        padding: 0px 3px 0px 7px;
        margin-right: 5px;
        margin-bottom: 5px;
        box-shadow: 0 5px 15px -2px rgb(117, 113, 249);
    }
    .tags-input-wrapper input {
        border: none;
        background: transparent;
        outline: none;
        width: 140px;
        margin-left: 8px;
    }
    .tags-input-wrapper {
        background: transparent;
        padding: 10px;
        border-radius: 4px;
        max-width: 400px;
        border: 1px solid #ccc;
    }
    .company_img {
        width: 100%;
        height: 100px;
        display: none;
    }
    .autocomplete {
        position: relative;
        display: inline-block;
    }
    .autocomplete-items {
        position: absolute;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        z-index: 99;
    }
    .autocomplete-item {
        padding: 10px;
        cursor: pointer;
    }
    .autocomplete-item:hover {
        background-color: #e0e0e0;
    }
    .all_framework_filters label{max-width:100%;width: 100%;font-size: 16px;font-weight:bold;}
    .all_framework_filters .select2.select2-container .selection .select2-selection { box-shadow: none;display: block; width: 100%; min-height: 45px; padding: 0; font-size: 0rem; line-height: 1.5; color: #495057; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem;}
    .all_framework_filters  .select2.select2-container .selection .select2-selection .select2-selection__rendered{line-height:38px;padding-left: 12px;padding-right: 30px;padding-top: 5px;padding-bottom: 5px;font-size: 14px;}
    .all_framework_filters .select2.select2-container .selection .select2-selection ul.select2-selection__rendered{padding: 5px;}
    .all_framework_filters .select2.select2-container .selection .select2-selection .select2-selection__rendered li input{margin: 0;padding: 0 12px;font-size: 0;}
    .all_framework_filters .select2-container--default .select2-selection--multiple .select2-selection__rendered li.select2-selection__choice {min-width: 60px;font-size: 14px;margin: 0 5px 0 0; background: #7571f9; color: #fff;position: relative;padding: 0px 30px 0px 5px;}
    .all_framework_filters .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {position: absolute;right: 10px;color: #fff;}
    .all_framework_filters .select2.select2-container .selection .select2-selection ul.select2-selection__rendered .select2-selection__clear{display: none;}
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #7571f9; color: #fff; }
</style>

<script>
    $(document).ready(function() {
        var table = $('#framework-table').DataTable();
        $("#filter-frameworkType-addFramework").select2({ placeholder: "Select Framework Type", allowClear: true });
        
        $("#filter-frameworkType-addFramework").on("change", function () {
            var selectedFrameworkType = $(this).val(); // Get selected values
            
            if (selectedFrameworkType.length > 0) {
                var regex = selectedFrameworkType.join("|"); // Create regex for OR filtering
                table.column(1).search(regex, true, false).draw();
            } else {
                table.column(1).search("").draw(); // Reset filter if no selection
            }
        });
    });
</script>

<div class="content-body">
    <!-- Page Header -->
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <h3><?php echo $is_edit_mode ? "Edit Framework" : "Add Framework"; ?></h3>
        </div>
        <div class="col p-md-0">
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
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <!-- Display success or error messages -->
                            <?php echo $message; ?>
                            <form class="form-valide" action="add-framework.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="framework_name">Framework Name<span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <input type="text" id="framework_name" name="framework_name" class="form-control" placeholder="Enter Framework Name" value="<?php echo htmlspecialchars($framework_name_value); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="framework_type">Framework Type<span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <select class="form-control" name="framework_type" id="framework_type" required>
                                                <option value="cms" <?php if(isset($framework_type_value) && $framework_type_value == "cms"){ echo "selected"; } ?>>CMS / Website Builder</option>
                                                <option value="backend" <?php if(isset($framework_type_value) && $framework_type_value == "backend"){ echo "selected"; } ?>>Backend Technologies</option>
                                                <option value="frontend" <?php if(isset($framework_type_value) && $framework_type_value == "frontend"){ echo "selected"; } ?>>Frontend Technologies</option>
                                                <option value="hosting" <?php if(isset($framework_type_value) && $framework_type_value == "hosting"){ echo "selected"; } ?>>Hosting / Cloud Services</option>
                                                <option value="other" <?php if(isset($framework_type_value) && $framework_type_value == "other"){ echo "selected"; } ?>>Other Technologies</option>
                                            </select>                                         
                                        </div>
                                    </div>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <input type="hidden" name="framework_id" value="<?php echo $edit_id; ?>">
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="update-framework" class="btn btn-primary">Update Framework</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="insert-framework" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="all_framework_filters">        
        <label>Filter by Framework Type:
            <select id="filter-frameworkType-addFramework" name="filter-frameworkType-addFramework" multiple class="form-control">            
                <?php echo call_all_rows_and_append_in_select_option($conn, "framework", "framework_type",$framework_labels); ?>
            </select>
        </label>    
    </div>
    </div>
    <!-- Framework Table Section -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body px-0" id="frawork_table_print">
                        <table class="table table-striped table-bordered" id="framework-table">
                            <thead>
                                <tr>
                                    <th>Framework Name</th>
                                    <th>Framework Type</th>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                </tr>
                             </thead>
                            <tbody>
                                <?php
                                
                                $sql = "SELECT * FROM framework ORDER BY id DESC";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $framework_type_label = isset($framework_labels[$row['framework_type']]) ? $framework_labels[$row['framework_type']] : ""; // Get label
                                        echo "<tr>
                                                <td>{$row['framework_name']}</td>
                                                <td>{$framework_type_label}</td>
                                                <td>{$row['created_at']}</td>                                                
                                                <td>
                                                    <a href='add-framework.php?edit={$row['id']}' class='btn btn-flat btn-primary btn-sm'>Edit</a>
                                                    <a href='add-framework.php?delete={$row['id']}' class='btn btn-flat btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this framework?');\">Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No Framework added yet.</td></tr>";
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
?>

<?php
include_once('footer.php');
$conn->close();
?>


