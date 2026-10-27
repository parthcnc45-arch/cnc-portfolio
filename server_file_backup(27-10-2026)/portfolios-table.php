<?php
function call_all_rows_and_append_in_select_option($conn, $table_name, $col_name)
{
    $sql = "SELECT id,$col_name FROM $table_name";
    $result = mysqli_query($conn, $sql);
    $options = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . $row[$col_name] . "'>" . htmlspecialchars($row[$col_name]) . "</option>";
    }
    return $options;
}
?>
<div class="all_portfolio_filters hweee">
    <h3>Filter</h3>
    <div class="row">
        <div class="col-xl-2">
            <div class="field-wrapper">
                <label for="filter-type">Type</label>
                <div class="field-wrap">
                    <select id="filter-type" name="filter-type" multiple class="form-control">
                        <option value="Portfolio">Portfolio</option>
                        <option value="Case Study">Case Study</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-xl-2">
            <div class="field-wrapper">
                <label for="filter-country">Country</label>
                <div class="field-wrap">
                    <select id="filter-country" name="filter-country" multiple class="form-control"></select>
                </div>
            </div>
        </div>

        <div class="col-xl-2">
            <div class="field-wrapper">
                <label for="filter-industries">Industries</label>
                <div class="field-wrap">
                    <select id="filter-industries" name="filter-industries" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "industries", "industrie_name"); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-xl-2">
            <div class="field-wrapper">
                <label for="filter-prog_lang">Programming Language</label>
                <div class="field-wrap">
                    <select id="filter-prog_lang" name="filter-prog_lang" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "programming_language", "programming_language_name"); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-xl-2">
            <div class="field-wrapper">
                <label for="filter-framework-portfolioedit">Framework</label>
                <div class="field-wrap">
                    <select id="filter-framework-portfolioedit" name="filter-framework-portfolioedit" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "framework", "framework_name"); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-xl-2">
            <div class="field-wrapper file-button-group">                
                <div class="field-wrap">
                    <button id="exportSelected" class="btn btn-success">Export Selected</button>
                </div>
                <div class="field-wrap">
                    <input type="file" id="importFile" accept=".xlsx" style="display: none;" />
                    <button id="importSelected" class="btn btn-success">Import&nbsp;<img class="loader-btn" src="/images/loader1.gif" alt="loader" style="width: 20px;display:none;"></button>
                </div>
            </div>
        </div>       
    </div>
    
    <!-- <label>Type:
        <select id="filter-type" name="filter-type" multiple class="form-control">
            <option value="Portfolio">Portfolio</option>
            <option value="Case Study">Case Study</option>
        </select>
    </label> -->
    <!-- <label>Country:
        <select id="filter-country" name="filter-country" multiple class="form-control"></select>
    </label> -->
    <!-- <label>Industries:
        <select id="filter-industries" name="filter-industries" multiple class="form-control">
            <?php //echo call_all_rows_and_append_in_select_option($conn, "industries", "industrie_name"); ?>
        </select>
    </label> -->
    <!-- <label>Programming Language:
        <select id="filter-prog_lang" name="filter-prog_lang" multiple class="form-control">
            <?php //echo call_all_rows_and_append_in_select_option($conn, "programming_language", "programming_language_name"); ?>
        </select>
    </label> -->
    <!-- <label>Framework:
        <select id="filter-framework-portfolioedit" name="filter-framework-portfolioedit" multiple class="form-control">
            <?php //echo call_all_rows_and_append_in_select_option($conn, "framework", "framework_name"); ?>
        </select>
    </label> -->
   
</div>
<table class="table table-striped table-bordered" id="portfolio-table">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"></th> <!-- Select All Checkbox -->
            <th>#</th>
            <th>Type</th>
            <th>Company Name</th>
            <th>Website Url</th>
            <th>Country</th>
            <th>Industries</th>
            <th>Framework</th>
            <th>CMS / Website Builder</th>
            <th>Backend Technologies</th>
            <th>Frontend Technologies</th>
            <th>Hosting / Cloud Services</th>
            <th>Other-Technologies</th>
            <th>Programming Language</th>
            <th>Current Status</th>
            <th>Tags</th>
            <th>Design URL</th>
            <th>Portfolio Type</th>
            <th>About Business</th>
            <th>Niche</th>
            <!-- <th>Technology</th> -->
            <th>Role</th>
            <th>Features</th>
            <th>Team Associates</th>
            <th>Comments</th>
            <th>GitHub Repository</th>
            <th>Demo Video</th>
            <th>Timestamp</th>
            <!-- <th>Favorite</th> -->
            <th>Action</th>
            <th>Initial goal or purpose</th>
            <th>Pain Area</th>
            <th>Development Approach</th>
            <th>Deliverables Outlined</th>
            <th>Challenges Faced During Development</th>
            <th>Solution</th>
            <th>Unique or Interactive elements</th>
            <th>Success story</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $timestamp = strtotime($row['timestamp']);
                $dateformat = date("d/m/Y h:i A", $timestamp);
                $site_preview = $row['site_preview_image'];
                if ($site_preview) {
                    $img = '<img src=' . $site_preview . ' width=50px height="50px">';
                } else {
                    $img = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/cnc_portfolio/images/no-image.png"; width=50px height="50px">';
                }
        ?>
                <tr>
                    <td><input type='checkbox' class='rowCheckbox' value='<?php echo $row["id"]; ?>'></td>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td class="company_name"><?php echo $row['company_name']; ?><!-- <span id="company_img_<?php echo $i; ?>" style="display: none;"><?php echo $img; ?></span> --></td>
                    <td><a target="_blank" href="<?php echo $row['website_url']; ?>" title="Please right click to copy link." class="copy-link"><?php echo $row['website_url']; ?></td>
                    <td><?php echo $row['country']; ?></td>
                    <td><?php echo $row['industries']; ?></td>
                    <td><?php echo $row['framework']; ?></td>
                    <td><?php echo $row['cms_plateform']; ?></td>
                    <td><?php echo $row['backend_tech']; ?></td>
                    <td><?php echo $row['frontend_tech']; ?></td>
                    <td><?php echo $row['hosting_tech']; ?></td>
                    <td><?php echo $row['other_tech']; ?></td>
                    <td><?php echo $row['programming_language']; ?></td>
                    <td><?php echo $row['current_status']; ?></td>
                    <td><?php echo $row['tags']; ?></td>
                    <td><?php if ($row['design_url'] != '') {
                            echo '<a href="' . $row['design_url'] . '" target="_blank" class="copy-link">Click</a>';
                        } ?></td>
                    <td><?php echo $row['portfolio_type']; ?></td>
                    <td><?php echo $row['about_business']; ?></td>
                    <td><?php echo $row['niche']; ?></td>
                    <!-- <td><?php //echo $row['technology']; 
                                ?></td> -->
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['features']; ?></td>
                    <td><?php echo $row['team_associates']; ?></td>
                    <td><?php echo $row['comments']; ?></td>
                    <td><?php if ($row['github_repository'] != '') {
                            echo '<a href="' . $row['github_repository'] . '" target="_blank" class="copy-link">Click</a>';
                        } ?></td>
                    <td><?php if ($row['demo_video'] != '') {
                            echo '<a href="' . $row['demo_video'] . '" target="_blank" class="copy-link">Click</a>';
                        } ?></td>
                    <td><?php echo $dateformat; ?></td>
                    <!-- <td><i class="<?php echo ($row['favourite'] == '0') ? 'far' : 'fas'; ?> fa-heart toggle-favorite" data-id="<?php echo $row['id']; ?>"></i></td> -->
                    <td><a href="edit-portfolio.php?id=<?php echo $row['id']; ?>" class="btn mb-1 btn-warning btn-sm" type="button">Edit</a>
                        <button type="button" class="btn mb-1 btn-flat btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                    </td>
                    <td><?php echo $row['initial_goal']; ?></td>
                    <td><?php echo $row['pain_area']; ?></td>
                    <td><?php echo $row['development_approach']; ?></td>
                    <td><?php echo $row['deliverables_outlined']; ?></td>
                    <td><?php echo $row['challenges_faced_during_development']; ?></td>
                    <td><?php echo $row['solution']; ?></td>
                    <td><?php echo $row['unique_or_interactive_elements']; ?></td>
                    <td><?php echo $row['success_story']; ?></td>
                </tr>
            <?php
                $i++;
            }
        } else {
            ?>
            <td colspan="8" style="text-align:center"><?php echo 'No Data Found'; ?></td>
        <?php
        }
        ?>
    </tbody>
</table>
<script src="./js/countries.js"></script>
<script>
    const countries = window.countries;
    const select = document.getElementById("filter-country");
    countries.forEach(country => {
        let option = document.createElement("option");
        option.value = country;
        option.textContent = country;
        select.appendChild(option);
    });
</script>