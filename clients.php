<?php
include_once('header.php');
include_once('sidebar.php'); ?>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.7.0/dist/multiple-select.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
                <div class="card">
                    <div class="card-body px-0" id="ajax_table_print">
                        <a href="add-client.php" class="btn float-right mb-3 mr-2 btn-primary">Add Client</a>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM clients WHERE `deleted`='0' ORDER BY created_at DESC");
                        ?>
                        <table class="table table-striped table-bordered" id="portfolio-table">
                            <thead>
                                <tr>
                                    <th>Client Name</th>
                                    <th>Email Address</th>
                                    <th>Phone Number</th>
                                    <th>City</th>
                                    <th>Country</th>
                                    <th>Company Name</th>
                                    <th>Company Website</th>
                                    <th>Industry</th>
                                    <th>Lead Type</th>
                                    <th>Client Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $timestamp = strtotime($row['created_at']);
                                        $dateformat = date("d/m/Y h:i A", $timestamp);
                                ?>
                                        <tr>
                                            <td class="company_name"><?php echo $row['client_name']; ?></td>
                                            <td><a href="mailto:<?php echo $row['email_address']; ?>"><?php echo $row['email_address']; ?></td>
                                            <td><?php echo $row['phone_number']; ?></td>
                                            <td><?php echo $row['city']; ?></td>
                                            <td><?php echo $row['country']; ?></td>
                                            <td><?php echo $row['company_name']; ?></td>
                                            <td><?php echo $row['company_website']; ?></td>
                                            <td><?php echo $row['industry']; ?></td>
                                            <td><?php echo $row['lead_type']; ?></td>
                                            <td><?php echo $row['client_status']; ?></td>
                                            <td><?php echo $dateformat; ?></td>
                                            <td><a href="edit-client.php?id=<?php echo $row['id']; ?>" class="btn mb-1 btn-warning btn-sm" type="button">Edit</a>
                                                <button type="button" class="btn mb-1 btn-flat btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <td colspan="12" style="text-align:center"><?php echo 'No Data Found'; ?></td>
                                <?php
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
            <div class="modal-body">
                Are you sure you want to delete this portfolio?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
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
            backgroundColor: '#51A351',
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
                    action: 'delet_portfolio',
                    portfolio_id: _id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
            $('#deleteConfirmationModal').modal('hide');
        });
        var table = $('#portfolio-table').dataTable({
            "aaSorting": [],
            scrollY: "600px",
            scrollX: true
            // searchHighlight: true
        });
        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
        });

        var table1 = $('#fav-portfolio-table').dataTable({
            "aaSorting": [],
            scrollY: "600px",
            scrollX: true,
            searchHighlight: true
        });
        $('#fav-portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#fav-portfolio-table').unmark();
            $('#fav-portfolio-table').mark(keyword, {});
        });
        var customDropdown =
            '<label><select id="portfolio_status" class="form-control form-control-sm custom-select">' +
            '<option value="0">Published</option>' +
            '<option value="1">Deleted</option>' +
            '</select></label>';
        $('#portfolio-table_wrapper .dataTables_filter').append(customDropdown);
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
                        scrollY: "600px",
                        scrollX: true,
                        "aaSorting": []
                    });
                    var customDropdown =
                        '<label><select id="portfolio_status" class="form-control form-control-sm custom-select">' +
                        '<option value="0">Published</option>' +
                        '<option value="1">Deleted</option>' +
                        '</select></label>';
                    var $dropdown = $(customDropdown);
                    // Loop through options to set selected based on selectedValue
                    $dropdown.find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#portfolio-table_wrapper .dataTables_filter').append($dropdown);
                },
                error: function(error) {
                    // Handle the error if the AJAX call fails
                    console.error('Error:', error);
                }
            });
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