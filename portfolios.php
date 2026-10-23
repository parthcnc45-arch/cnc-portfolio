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
    .all_portfolio_filters{padding: 0 30px;}
    .all_portfolio_filters .row{align-items: flex-end;}
    .all_portfolio_filters h3{margin-bottom: 20px; background: #7571f9; padding: 10px; color: #fff;border-radius: 0.25rem;}
    .all_portfolio_filters  .field-wrapper label{max-width:100%;width: 100%;font-size: 16px;font-weight:bold;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection { box-shadow: none;display: block; width: 100%; min-height: 45px; padding: 0; font-size: 0rem; line-height: 1.5; color: #495057; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem;}
    .all_portfolio_filters  .field-wrapper .field-wrap  .select2.select2-container .selection .select2-selection .select2-selection__rendered{line-height:30px;padding-left: 12px;padding-right: 30px;padding-top: 5px;padding-bottom: 5px;font-size: 14px;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection ul.select2-selection__rendered{padding: 5px;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection .select2-selection__rendered li input{margin: 0;padding: 0 12px;font-size: 0;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__rendered li.select2-selection__choice {min-width: 60px;font-size: 14px;margin: 0 5px 0 0; background: #7571f9; color: #fff;position: relative;padding: 0px 30px 0px 5px;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {position: absolute;right: 10px;color: #fff;}
    .all_portfolio_filters  .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection ul.select2-selection__rendered .select2-selection__clear{display: none;}
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #7571f9; color: #fff; }
    .file-button-group{display: flex; align-items: center; gap: 10px;}
    .file-button-group button{padding: 7px 18px; font-size: 14px; background-color: #7571f9; color: #fff; border-color: #7571f9 !important;}
    .file-button-group button:focus, .file-button-group button:active{background-color: #7571f9 !important; color: #fff !important;}
    .file-button-group button:hover{background-color: transparent !important; color: #7571f9 !important;}
    @media (max-width:767px) {
        .all_portfolio_filters{ padding: 0.9375rem 0.9375rem 0; }
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
<?php
include_once('footer.php');
?>
<script>
    function insertMultiDeleteButton() {
        // Avoid inserting multiple times
        if ($('#portfolio-table_wrapper #deleteSelected').length > 0) return;

        const multiDelButton = `
            <div class="row">
                <div class="col-xl-12">
                    <div class="field-wrapper">                
                        <div class="field-wrap">
                            <button id="deleteSelected" class="btn btn-danger">Multiple Delete</button>
                        </div>
                    </div>    
                </div>
            </div>
        `;

        // Find the row containing pagination (usually last row inside .dataTables_wrapper)
        const $wrapper = $('#portfolio-table_wrapper');
        const $paginationRow = $wrapper.find('.row').last();

        $paginationRow.after(multiDelButton);
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
        
        $("#selectAll").on("click", function() {
            $(".rowCheckbox").prop("checked", this.checked);
        });

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

        
        $("#filter-country").select2({ placeholder: "Select Country", allowClear: true });
        $("#filter-type").select2({ placeholder: "Select Type", allowClear: true });
        $("#filter-industries").select2({ placeholder: "Select Industries", allowClear: true });
        $("#filter-prog_lang").select2({ placeholder: "Select Programming Language", allowClear: true });
        $("#filter-framework-portfolioedit").select2({ placeholder: "Select Framework", allowClear: true });
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
            scrollY:"600px",
            scrollX: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], // Add "All" option
            //"pageLength": 10, // Default entries to show
            "pageLength": -1, // Default entries to show
            // searchHighlight: true
            columnDefs: [
                { 
                    targets: [
                        7,  // Framework
                        15, // Tags
                        16, // Design URL
                        18, // About Business
                        19, // Niche
                        20, // Role
                        21, // Features
                        22, // Team Associates
                        23, // Comments
                        24, // GitHub Repository
                        25, // Demo Video
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
       
        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
        });

        var table1 = $('#fav-portfolio-table').dataTable({
            "aaSorting": [],
            scrollY:"600px",
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
                        scrollY:"600px",
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