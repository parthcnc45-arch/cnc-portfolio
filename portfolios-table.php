<?php
require_once 'country_flags_helper.php';

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

if (!function_exists('render_tech_badges')) {
    function render_tech_badges($row) {
        $techs = [];
        $fields = ['programming_language', 'framework', 'frontend_tech', 'backend_tech', 'other_tech'];
        foreach ($fields as $f) {
            if (!empty($row[$f])) {
                $parts = explode(',', $row[$f]);
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p !== '' && !in_array($p, $techs)) {
                        $techs[] = $p;
                    }
                }
            }
        }
        if (empty($techs)) {
            return '<span class="text-muted">-</span>';
        }
        $out = '<div class="badge-cluster">';
        $slice = array_slice($techs, 0, 2);
        foreach ($slice as $t) {
            $out .= '<span class="portfolio-badge badge-tech" title="' . htmlspecialchars($t) . '">' . htmlspecialchars($t) . '</span>';
        }
        if (count($techs) > 2) {
            $extra = count($techs) - 2;
            $moreList = htmlspecialchars(implode(', ', array_slice($techs, 2)));
            $out .= '<span class="portfolio-badge badge-more" title="' . $moreList . '">+' . $extra . '</span>';
        }
        $out .= '</div>';
        return $out;
    }
}

if (!function_exists('render_cms_badges')) {
    function render_cms_badges($row) {
        if (empty($row['cms_plateform']) || trim($row['cms_plateform']) === '') {
            return '<span class="text-muted">-</span>';
        }
        $parts = explode(',', $row['cms_plateform']);
        $out = '<div class="badge-cluster">';
        $slice = array_slice($parts, 0, 1);
        foreach ($slice as $p) {
            $p = trim($p);
            if ($p !== '') {
                $out .= '<span class="portfolio-badge badge-cms" title="' . htmlspecialchars(trim($row['cms_plateform'])) . '">' . htmlspecialchars($p) . '</span>';
            }
        }
        if (count($parts) > 1) {
            $extra = count($parts) - 1;
            $out .= '<span class="portfolio-badge badge-more" title="' . htmlspecialchars(trim($row['cms_plateform'])) . '">+' . $extra . '</span>';
        }
        $out .= '</div>';
        return $out;
    }
}

if (!function_exists('render_industry_badges')) {
    function render_industry_badges($row) {
        if (empty($row['industries']) || trim($row['industries']) === '') {
            return '<span class="text-muted">-</span>';
        }
        $parts = explode(',', $row['industries']);
        $out = '<div class="badge-cluster">';
        $slice = array_slice($parts, 0, 1);
        foreach ($slice as $p) {
            $p = trim($p);
            if ($p !== '') {
                $out .= '<span class="portfolio-badge badge-industry" title="' . htmlspecialchars(trim($row['industries'])) . '">' . htmlspecialchars($p) . '</span>';
            }
        }
        if (count($parts) > 1) {
            $extra = count($parts) - 1;
            $out .= '<span class="portfolio-badge badge-more" title="' . htmlspecialchars(trim($row['industries'])) . '">+' . $extra . '</span>';
        }
        $out .= '</div>';
        return $out;
    }
}
?>
<div class="modern-filter-wrapper">
    <!-- Main Search & Actions Bar (Matching user reference Screenshot 2) -->
    <div class="modern-filter-bar">
        <div class="search-input-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="global-portfolio-search" class="search-input" placeholder="Search by company name, website, industry, technology, country..." autocomplete="off">
            <div id="search-history-dropdown" class="search-history-dropdown" style="display: none;">
                <div class="history-dropdown-header">
                    <span class="history-dropdown-title">Recent Searches</span>
                    <button type="button" id="btn-clear-history" class="btn-clear-history">Clear History</button>
                </div>
                <div id="history-items-container" class="history-items-container">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
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
    </div>
</div>

<input type="file" id="importFile" accept=".xlsx" style="display: none;" />

<div class="portfolio-table-card">
    <div class="portfolio-table-responsive">
        <table class="table" id="portfolio-table">
            <colgroup>
                <col style="width: 30px;">
                <col style="width: 16%;">
                <col style="width: 17%;">
                <col style="width: 9%;">
                <col style="width: 12%;">
                <col style="width: 15%;">
                <col style="width: 11%;">
                <col style="width: 132px;">
            </colgroup>
            <thead>
                <tr>
                    <th class="col-checkbox"><input type="checkbox" id="selectAll"></th>
                    <th class="col-company">Company Name</th>
                    <th class="col-website">Website URL</th>
                    <th class="col-country">Country</th>
                    <th class="col-industries">Industries</th>
                    <th class="col-technologies">Technologies</th>
                    <th class="col-cms">CMS / Builder</th>
                    <th class="col-actions">Actions</th>

                    <!-- Hidden columns preserving all existing database fields and functionality -->
                    <th>Type</th>
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
                    <th>Role</th>
                    <th>Features</th>
                    <th>Team Associates</th>
                    <th>Comments</th>
                    <th>GitHub Repository</th>
                    <th>Demo Video</th>
                    <th>Timestamp</th>
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
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $timestamp = strtotime($row['timestamp']);
                        $dateformat = date("d/m/Y h:i A", $timestamp);
                ?>
                        <tr>
                            <td class="col-checkbox"><input type='checkbox' class='rowCheckbox' value='<?php echo $row["id"]; ?>'></td>
                            <td class="company_name" title="<?php echo htmlspecialchars($row['company_name'] ?? ''); ?>"><?php echo htmlspecialchars($row['company_name'] ?? ''); ?></td>
                            <td class="col-website">
                                <?php if (!empty($row['website_url'])): ?>
                                    <div class="website-url-wrapper">
                                        <a target="_blank" href="<?php echo htmlspecialchars($row['website_url']); ?>" data-id="<?php echo $row['id']; ?>" title="<?php echo htmlspecialchars($row['website_url']); ?> (Right-click to copy)" class="website-url-link copy-link">
                                            <span class="website-url-text"><?php echo htmlspecialchars($row['website_url']); ?></span>
                                            <span class="external-icon-wrap" title="Open website">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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
                            <td class="col-country">
                                <?php echo render_country_flags($row['country'] ?? ''); ?>
                            </td>
                            <td class="col-industries"><?php echo render_industry_badges($row); ?></td>
                            <td class="col-technologies"><?php echo render_tech_badges($row); ?></td>
                            <td class="col-cms"><?php echo render_cms_badges($row); ?></td>
                            <td class="col-actions">
                                <div class="table-actions-inline">
                                    <?php if (!empty($row['website_url'])): ?>
                                        <button type="button" class="btn-table-action btn-table-copy" data-id="<?php echo $row['id']; ?>" data-url="<?php echo htmlspecialchars($row['website_url']); ?>" title="Copy website link">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                            <span>Copy</span>
                                        </button>
                                        <a href="<?php echo htmlspecialchars($row['website_url']); ?>" target="_blank" class="btn-table-action btn-table-view" title="Open website in new tab">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                            <span>View</span>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="btn-table-action btn-table-copy disabled" disabled title="No website URL">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                            </svg>
                                            <span>Copy</span>
                                        </button>
                                        <a href="view-portfolio.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn-table-action btn-table-view" title="View details">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                            <span>View</span>
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="action-dots-btn" data-id="<?php echo $row['id']; ?>" title="More Actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Hidden columns for data retention and DataTables filtering -->
                            <td><?php echo $row['type']; ?></td>
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
                    }
                } else {
                    ?>
                    <tr><td colspan="8" style="text-align:center; padding: 30px; color: #64748b;">No Data Found</td></tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="./js/countries.js"></script>
<script>
    if (window.countries && document.getElementById("filter-country")) {
        const select = document.getElementById("filter-country");
        select.innerHTML = '';
        window.countries.forEach(country => {
            let option = document.createElement("option");
            option.value = country;
            option.textContent = country;
            select.appendChild(option);
        });
    }
</script>