<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php'); // This file contains your DB connection (e.g., $conn)
// set timezone to India (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');
$message = "";
$is_edit_mode = false;
$portfolio_type_name_value = "";

// -----------------------
// Handle Delete Request
// -----------------------
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM application_type WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Portfolio Type Deleted successfully!</div>';
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
    $stmt = $conn->prepare("SELECT * FROM application_type WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $portfolio_type_name_value = $row['application_type_name'];
        $is_edit_mode = true;
    } else {
        $message = '<div class="alert alert-danger">Portfolio Type not found!</div>';
    }
    $stmt->close();
}

// -----------------------
// Handle Form Submission
// -----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Insert New portfolio_type
    if (isset($_POST['insert-portfolio_type'])) {
        $portfolio_type_name = trim($_POST['portfolio_type_name']);
        if (empty($portfolio_type_name)) {
            $message = '<div class="alert alert-danger">Portfolio Type name is required!</div>';
        } else {           

            $stmt = $conn->prepare("SELECT COUNT(*) FROM application_type WHERE application_type_name = ?");
            $stmt->bind_param("s", $portfolio_type_name);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $message = '<div class="alert alert-warning">This Portfolio Type already exists!</div>';
            } else {
                // Insert both the portfolio_type name and the timestamp
                $current_time = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO application_type (application_type_name, created_at) VALUES (?, ?)");
                $stmt->bind_param("ss", $portfolio_type_name, $current_time);
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Portfolio Type added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }       
            
        }
    }
    // 2. Update Existing portfolio_type
    elseif (isset($_POST['update-portfolio_type'])) {
        $portfolio_type_name = trim($_POST['portfolio_type_name']);
        $portfolio_type_id = intval($_POST['portfolio_type_id']);
        if (empty($portfolio_type_name)) {
            $message = '<div class="alert alert-danger">Portfolio Type name is required!</div>';
        } else {
            $stmt = $conn->prepare("UPDATE application_type SET application_type_name = ? WHERE id = ?");
            $stmt->bind_param("si", $portfolio_type_name, $portfolio_type_id);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Portfolio Type updated successfully!</div>';
                // Reset edit mode after update
                $is_edit_mode = false;
                $portfolio_type_name_value = "";
            } else {
                $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
}
?>
<!-- Include external CSS files -->
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
</style>

<div class="content-body">
    <!-- Page Header -->
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <h3><?php echo $is_edit_mode ? "Edit Portfolio Type" : "Add Portfolio Type"; ?></h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)"><?php echo $is_edit_mode ? "Edit Portfolio Type" : "Add Portfolio Type"; ?></a>
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
                            <form class="form-valide" action="add-portfolio_type.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="portfolio_type_name">Portfolio Type Name<span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <input type="text" id="portfolio_type_name" name="portfolio_type_name" class="form-control" placeholder="Enter Portfolio Type Name" value="<?php echo htmlspecialchars($portfolio_type_name_value); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <input type="hidden" name="portfolio_type_id" value="<?php echo $edit_id; ?>">
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="update-portfolio_type" class="btn btn-primary">Update Portfolio Type</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="insert-portfolio_type" class="btn btn-primary">Submit</button>
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

    <!-- Programming Language Table Section -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body px-0" id="portfolio_type_table_print">
                        <table class="table table-striped table-bordered" id="portfolio_type-table">
                            <thead>
                                <tr>
                                    <th>Portfolio Type Name</th>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                </tr>
                             </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM application_type ORDER BY id DESC";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['application_type_name']}</td>
                                                 <td>{$row['created_at']}</td>
                                                <td>
                                                    <a href='add-portfolio_type.php?edit={$row['id']}' class='btn btn-flat btn-primary btn-sm'>Edit</a>
                                                    <a href='add-portfolio_type.php?delete={$row['id']}' class='btn btn-flat btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this Portfolio Type?');\">Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No Portfolio Type added yet.</td></tr>";
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