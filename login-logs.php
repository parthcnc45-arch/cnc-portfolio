<?php
include_once('header.php');
include_once('sidebar.php');
require_once('country_flags_helper.php');
?>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.7.0/dist/multiple-select.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<style>
    /* =========================================================
       MODERN PAGE HEADER & LAYOUT (Matching portfolios.php)
       ========================================================= */
    .content-body {
        background-color: #f7f8fb !important;
        min-height: 100vh;
        padding-bottom: 40px;
    }
    .portfolio-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        padding-top: 4px;
        gap: 15px;
    }
    .portfolio-header-left {
        flex: 1 1 auto;
        min-width: 0;
    }
    .portfolio-header-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }
    .portfolio-page-title {
        font-size: 21px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: -0.3px;
        font-family: inherit;
    }
    .portfolio-count-badge {
        font-size: 19px;
        font-weight: 600;
        color: #1e293b;
    }
    .portfolio-page-desc {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    /* =========================================================
       MODERN TABLE CARD & STYLES (Matching portfolios.php)
       ========================================================= */
    .portfolio-table-card {
        background: #ffffff;
        border: 1.5px solid #e8ecf4;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow-x: hidden !important;
        overflow-y: visible !important;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .portfolio-table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        overflow-y: visible !important;
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    #portfolio-table_wrapper,
    .portfolio-table-card .dataTables_wrapper {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
    }
    #portfolio-table_wrapper::after {
        content: "";
        display: table;
        clear: both;
    }

    /* Top Table Controls Toolbar */
    #portfolio-table_wrapper .dataTables_length {
        float: left;
        margin: 12px 16px 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        height: 32px !important;
    }
    #portfolio-table_wrapper .dataTables_length label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        gap: 5px !important;
    }
    #portfolio-table_wrapper .dataTables_length select {
        height: 32px !important;
        min-width: 65px !important;
        padding: 2px 24px 2px 10px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        outline: none !important;
        cursor: pointer !important;
        margin: 0 4px !important;
        transition: all 0.2s ease !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 8px center !important;
        background-size: 10px 10px !important;
    }
    #portfolio-table_wrapper .dataTables_length select:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Right controls in header toolbar: Search & Status */
    #portfolio-table_wrapper .dataTables_filter {
        float: right;
        margin: 12px 16px 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 10px !important;
        height: 32px !important;
    }
    #portfolio-table_wrapper .dataTables_filter label {
        display: inline-flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        gap: 6px !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"] {
        height: 32px !important;
        min-width: 180px !important;
        padding: 4px 12px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
    }
    #portfolio-table_wrapper .dataTables_filter input[type="search"]:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Status Dropdown */
    .custom-status-select,
    #portfolio_status {
        height: 32px !important;
        min-width: 105px !important;
        padding: 2px 24px 2px 10px !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        outline: none !important;
        transition: all 0.2s ease !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMiIgaGVpZ2h0PSIxMiIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM1YjUyZjkiIHN0cm9rZS13aWR0aD0iMi41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==") !important;
        background-repeat: no-repeat !important;
        background-position: right 8px center !important;
        background-size: 10px 10px !important;
    }
    .custom-status-select:focus,
    #portfolio_status:focus {
        border-color: #5b52f9 !important;
        box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12) !important;
    }

    /* Main Table Element */
    #portfolio-table {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
        margin: 0 !important;
        border: none !important;
        background-color: #ffffff !important;
        width: 100% !important;
    }

    /* Header Styling */
    #portfolio-table thead th {
        background-color: #f9f9fd !important;
        color: #475569 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        letter-spacing: 0.2px !important;
        padding: 10px 16px !important;
        vertical-align: middle !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1.5px solid #edf0f7 !important;
        white-space: nowrap !important;
    }

    /* Row & Body Cells */
    #portfolio-table tbody td {
        padding: 10px 16px !important;
        vertical-align: middle !important;
        font-size: 12.5px !important;
        color: #334155 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        background-color: #ffffff !important;
        line-height: 1.4 !important;
        transition: background-color 0.15s ease !important;
    }
    #portfolio-table tbody tr:hover td {
        background-color: #fbfbfe !important;
    }
    #portfolio-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* IP Address Badge */
    .ip-badge {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 12px;
        color: #475569;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 4px;
        display: inline-block;
        white-space: nowrap;
    }

    /* Country Flag & Label Styles (Matching portfolios.php) */
    .country-cell-wrapper {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 3px 5px;
        max-width: 100%;
        line-height: normal;
    }
    .country-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        vertical-align: middle;
        line-height: normal;
    }
    .country-flag-img {
        width: 20px;
        height: 15px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
        vertical-align: middle;
        display: inline-block;
        background-color: #f8fafc;
    }
    .country-name-text {
        font-size: 12px;
        color: #334155;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    /* Empty state styling */
    .empty-table-cell {
        text-align: center !important;
        padding: 50px 20px !important;
        background-color: #ffffff !important;
        border: none !important;
    }
    .empty-state-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .empty-icon {
        font-size: 36px;
        color: #cbd5e1;
    }
    .empty-text {
        font-size: 14.5px;
        font-weight: 600;
        color: #475569;
    }
    .empty-subtext {
        font-size: 12.5px;
        color: #94a3b8;
    }

    /* Table Footer & Pagination */
    #portfolio-table_wrapper .dataTables_info {
        float: left;
        padding: 12px 16px !important;
        font-size: 12.5px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }
    #portfolio-table_wrapper .dataTables_paginate {
        float: right;
        padding: 10px 16px !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    #portfolio-table_paginate .paginate_button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 28px !important;
        height: 28px !important;
        padding: 0 8px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #64748b !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        margin: 0 2px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        outline: none !important;
        box-shadow: none !important;
    }
    #portfolio-table_paginate .paginate_button:hover {
        background-color: #f5f3ff !important;
        border-color: #c7d2fe !important;
        color: #5b52f9 !important;
    }
    #portfolio-table_paginate .paginate_button.current,
    #portfolio-table_paginate .paginate_button.current:hover,
    #portfolio-table_paginate .page-item.active .page-link {
        background: #5b52f9 !important;
        background-color: #5b52f9 !important;
        border-color: #5b52f9 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(91, 82, 249, 0.3) !important;
    }
    #portfolio-table_paginate .paginate_button.disabled,
    #portfolio-table_paginate .paginate_button.disabled:hover {
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        background-color: #f8fafc !important;
        cursor: not-allowed !important;
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
                <?php
                $result = mysqli_query($conn, "SELECT * FROM login_logs ORDER BY id DESC");
                $total_logs = mysqli_num_rows($result);
                ?>
                <!-- Modern Page Header matching portfolios.php -->
                <div class="portfolio-page-header">
                    <div class="portfolio-header-left">
                        <div class="portfolio-header-title-wrap">
                            <h1 class="portfolio-page-title">Login Logs <span class="portfolio-count-badge">(<?php echo $total_logs; ?> found)</span></h1>
                        </div>
                        <p class="portfolio-page-desc">View and monitor user login timestamps, IP addresses, and authentication history.</p>
                    </div>
                </div>

                <!-- Modern Table Card matching portfolios.php -->
                <div class="portfolio-table-card" id="ajax_table_print">
                    <div class="portfolio-table-responsive">
                        <table class="table" id="portfolio-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Username</th>
                                    <th style="width: 20%;">IP Address</th>
                                    <th style="width: 20%;">Country</th>
                                    <th style="width: 20%;">City</th>
                                    <th style="width: 30%;">Login Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                if ($total_logs > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $timestamp = strtotime($row['login_time']);
                                        $dateformat = date("d/m/Y h:i A", $timestamp);
                                ?>
                                        <tr>
                                            <td style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
                                            <td><span class="ip-badge"><?php echo htmlspecialchars($row['ip_address'] ?? ''); ?></span></td>
                                            <td class="col-country"><?php echo render_country_flags($row['country'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($row['city'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td style="color: #64748b; font-size: 12.5px;"><?php echo $dateformat; ?></td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="empty-table-cell">
                                            <div class="empty-state-wrap">
                                                <i class="fas fa-history empty-icon"></i>
                                                <div class="empty-text">No Login Logs Found</div>
                                                <div class="empty-subtext">No user activity recorded yet.</div>
                                            </div>
                                        </td>
                                    </tr>
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
            backgroundColor: '#10b981',
        }).showToast();
    }
    $(document).ready(function() {
        $(document).on('change', '.fav_change_inp', function() {
            var _fav_website = $('#fav_websites').val();
            var _fav_design = $('#fav_designs').val();
            $.ajax({
                url: 'ajax_functions.php',
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
                    console.error('Error:', error);
                }
            });
        });
        $(document).on('click', '.toggle-favorite', function() {
            var itemId = $(this).data('id');
            var isFavorite = $(this).hasClass('fas') ? 0 : 1;
            $.ajax({
                url: 'ajax_functions.php',
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
                    console.error('Error:', error);
                }
            });
            $(this).toggleClass('far fas');
        });
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
        var table = $('#portfolio-table').DataTable({
            "autoWidth": false,
            "aaSorting": [],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "pageLength": 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search logs...",
                lengthMenu: "Show _MENU_ entries"
            }
        });
        table.on('draw', function() {
            $('#portfolio-table').css('width', '100%');
        });
        $('#portfolio-table_filter input[type="search"]').on('input', function() {
            var keyword = $(this).val();
            $('#portfolio-table').unmark();
            $('#portfolio-table').mark(keyword, {});
        });

        var customDropdown =
            '<label><select id="portfolio_status" class="custom-status-select">' +
            '<option value="0">Published</option>' +
            '<option value="1">Deleted</option>' +
            '</select></label>';
        $('#portfolio-table_wrapper .dataTables_filter').append(customDropdown);
        $(document).on('change', '#portfolio_status', function() {
            var selectedValue = $(this).val();
            $.ajax({
                url: 'ajax_functions.php',
                method: 'GET',
                data: {
                    action: 'get_portfolios',
                    value: selectedValue
                },
                success: function(response) {
                    $('#ajax_table_print').html(response);
                    var newTable = $('#portfolio-table').DataTable({
                        "autoWidth": false,
                        "aaSorting": [],
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        "pageLength": 10,
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search logs...",
                            lengthMenu: "Show _MENU_ entries"
                        }
                    });
                    newTable.on('draw', function() {
                        $('#portfolio-table').css('width', '100%');
                    });
                    var customDropdown =
                        '<label><select id="portfolio_status" class="custom-status-select">' +
                        '<option value="0">Published</option>' +
                        '<option value="1">Deleted</option>' +
                        '</select></label>';
                    var $dropdown = $(customDropdown);
                    $dropdown.find('option').each(function() {
                        if ($(this).val() === selectedValue) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#portfolio-table_wrapper .dataTables_filter').append($dropdown);
                    table = newTable;
                },
                error: function(error) {
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
            Toastify({
                text: 'Copied to clipboard!',
                duration: 3000,
                close: true,
                gravity: 'top-right',
                backgroundColor: '#10b981',
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
            }
        });
    });
</script>