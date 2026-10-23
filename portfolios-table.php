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
<div class="modern-filter-wrapper">
    <!-- Main Search & Actions Bar (Matching user screenshot) -->
    <div class="modern-filter-bar">
        <div class="search-input-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="global-portfolio-search" class="search-input" placeholder="Search by company name, website, industry, technology, country..." autocomplete="off">
        </div>
        <div class="filter-actions-group">
            <button type="button" id="btn-global-search" class="btn-filter-action btn-search-primary">
                <i class="fas fa-search"></i>
                <span>Search</span>
            </button>
            <button type="button" id="btn-toggle-advanced-filters" class="btn-filter-action btn-advanced-toggle">
                <i class="fas fa-filter"></i>
                <span>Advanced Filters</span>
            </button>
            <button type="button" id="btn-clear-filters" class="btn-filter-action btn-clear-filters">
                <i class="fas fa-times"></i>
                <span>Clear</span>
            </button>
        </div>
    </div>

    <!-- Advanced Filters Collapsible Panel -->
    <div id="advanced-filters-panel" class="advanced-filters-panel" style="display: none;">
        <div class="advanced-filters-grid">
            <div class="adv-filter-col">
                <label for="filter-type">Type</label>
                <div class="adv-select-wrap">
                    <select id="filter-type" name="filter-type" multiple class="form-control">
                        <option value="Portfolio">Portfolio</option>
                        <option value="Case Study">Case Study</option>
                    </select>
                </div>
            </div>

            <div class="adv-filter-col">
                <label for="filter-country">Country</label>
                <div class="adv-select-wrap">
                    <select id="filter-country" name="filter-country" multiple class="form-control"></select>
                </div>
            </div>

            <div class="adv-filter-col">
                <label for="filter-industries">Industries</label>
                <div class="adv-select-wrap">
                    <select id="filter-industries" name="filter-industries" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "industries", "industrie_name"); ?>
                    </select>
                </div>
            </div>

            <div class="adv-filter-col">
                <label for="filter-prog_lang">Programming Language</label>
                <div class="adv-select-wrap">
                    <select id="filter-prog_lang" name="filter-prog_lang" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "programming_language", "programming_language_name"); ?>
                    </select>
                </div>
            </div>

            <div class="adv-filter-col">
                <label for="filter-framework-portfolioedit">Framework</label>
                <div class="adv-select-wrap">
                    <select id="filter-framework-portfolioedit" name="filter-framework-portfolioedit" multiple class="form-control">
                        <?php echo call_all_rows_and_append_in_select_option($conn, "framework", "framework_name"); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="advanced-filters-footer">
            <div class="file-button-group">                
                <button type="button" id="exportSelected" class="btn btn-export">
                    <i class="fas fa-file-export mr-1"></i> Export Selected
                </button>
                <input type="file" id="importFile" accept=".xlsx" style="display: none;" />
                <button type="button" id="importSelected" class="btn btn-import">
                    <i class="fas fa-file-import mr-1"></i> Import&nbsp;<img class="loader-btn" src="/images/loader1.gif" alt="loader" style="width: 20px;display:none;">
                </button>
            </div>
        </div>
    </div>
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
                    <td class="company_name"><?php echo $row['company_name']; ?></td>
                    <td>
                        <?php if (!empty($row['website_url'])): ?>
                            <div class="website-url-wrapper">
                                <a target="_blank" href="<?php echo htmlspecialchars($row['website_url']); ?>" title="<?php echo htmlspecialchars($row['website_url']); ?> (Right-click to copy)" class="website-url-link copy-link">
                                    <span class="website-url-text"><?php echo htmlspecialchars($row['website_url']); ?></span>
                                    <span class="external-icon-wrap" title="Open website">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
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
                    <td class="text-center">
                        <button type="button" class="action-dots-btn" data-id="<?php echo $row['id']; ?>" title="Actions">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
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