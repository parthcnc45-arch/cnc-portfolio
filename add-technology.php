<?php
include_once('header.php');
include_once('sidebar.php');
include_once('db.php'); // This file contains your DB connection (e.g., $conn)
// set timezone to India (Asia/Kolkata)
date_default_timezone_set('Asia/Kolkata');
$message = "";
$is_edit_mode = false;
$technology_name_value = "";

// -----------------------
// Handle Delete Request
// -----------------------
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM technology WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Technology Deleted successfully!</div>';
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
    $stmt = $conn->prepare("SELECT * FROM technology WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $technology_name_value = $row['technology_name'];
        $is_edit_mode = true;
    } else {
        $message = '<div class="alert alert-danger">Technology not found!</div>';
    }
    $stmt->close();
}

// -----------------------
// Handle Form Submission
// -----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Insert New technology
    if (isset($_POST['insert-technology'])) {
        $technology_name = trim($_POST['technology_name']);
        if (empty($technology_name)) {
            $message = '<div class="alert alert-danger">Technology name is required!</div>';
        } else {           

            $stmt = $conn->prepare("SELECT COUNT(*) FROM technology WHERE technology_name = ?");
            $stmt->bind_param("s", $technology_name);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $message = '<div class="alert alert-warning">This Technology already exists!</div>';
            } else {
                // Insert both the technology name and the timestamp
                $current_time = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO technology (technology_name, created_at) VALUES (?, ?)");
                $stmt->bind_param("ss", $technology_name, $current_time);
                if ($stmt->execute()) {
                    $message = '<div class="alert alert-success">Technology added successfully!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                }
                $stmt->close();
            }       
            
        }
    }
    // 2. Update Existing technology
    elseif (isset($_POST['update-technology'])) {
        $technology_name = trim($_POST['technology_name']);
        $technology_id = intval($_POST['technology_id']);
        if (empty($technology_name)) {
            $message = '<div class="alert alert-danger">Technology name is required!</div>';
        } else {
            $stmt = $conn->prepare("UPDATE technology SET technology_name = ? WHERE id = ?");
            $stmt->bind_param("si", $technology_name, $technology_id);
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Technology updated successfully!</div>';
                // Reset edit mode after update
                $is_edit_mode = false;
                $technology_name_value = "";
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
            <h3><?php echo $is_edit_mode ? "Edit Technology" : "Add Technology"; ?></h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">
                    <a href="javascript:void(0)"><?php echo $is_edit_mode ? "Edit Technology" : "Add Technology"; ?></a>
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
                            <form class="form-valide" action="add-technology.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="technology_name">Technology Name<span class="text-danger">*</span></label>
                                        <div class="form-control-group">
                                            <input type="text" id="technology_name" name="technology_name" class="form-control" placeholder="Enter Technology Name" value="<?php echo htmlspecialchars($technology_name_value); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($is_edit_mode): ?>
                                    <input type="hidden" name="technology_id" value="<?php echo $edit_id; ?>">
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="update-technology" class="btn btn-primary">Update Technology</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <button type="submit" name="insert-technology" class="btn btn-primary">Submit</button>
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
                    <div class="card-body px-0" id="technology_table_print">
                        <table class="table table-striped table-bordered" id="technology-table">
                            <thead>
                                <tr>
                                    <th>Technology Name</th>
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                </tr>
                             </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM technology ORDER BY id DESC";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['technology_name']}</td>
                                                 <td>{$row['created_at']}</td>
                                                <td>
                                                    <a href='add-technology.php?edit={$row['id']}' class='btn btn-flat btn-primary btn-sm'>Edit</a>
                                                    <a href='add-technology.php?delete={$row['id']}' class='btn btn-flat btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this Technology?');\">Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No Technology added yet.</td></tr>";
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