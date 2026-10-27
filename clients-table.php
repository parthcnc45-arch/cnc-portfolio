<?php
require_once 'country_flags_helper.php';
?>
<div class="portfolio-table-responsive">
    <table class="table" id="portfolio-table">
        <thead>
            <tr>
                <th class="col-client-name" title="Client Name">Client Name</th>
                <th class="col-email" title="Email Address">Email Address</th>
                <th class="col-phone" title="Phone Number">Phone Number</th>
                <th class="col-city" title="City">City</th>
                <th class="col-country" title="Country">Country</th>
                <th class="col-company" title="Company Name">Company Name</th>
                <th class="col-website" title="Company Website">Company Website</th>
                <th class="col-lead-type" title="Lead Type">Lead Type</th>
                <th class="col-status" title="Client Status">Client Status</th>
                <th class="col-action" style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            if (isset($total_clients) && $total_clients > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $client_name = $row['client_name'] ?? '';
                    $email_address = $row['email_address'] ?? '';
                    $phone_number = $row['phone_number'] ?? '';
                    $city = $row['city'] ?? '';
                    $company_name = $row['company_name'] ?? '';
                    $company_website = $row['company_website'] ?? '';
                    $lead_type = $row['lead_type'] ?? '';
                    $client_status = $row['client_status'] ?? '';
            ?>
                    <tr>
                        <td class="col-client-name company_name" style="font-weight: 600; color: #1e293b;" title="<?php echo htmlspecialchars($client_name); ?>">
                            <span class="cell-truncate"><?php echo htmlspecialchars($client_name); ?></span>
                        </td>
                        <td class="col-email" title="<?php echo htmlspecialchars($email_address); ?>">
                            <?php if (!empty($email_address)): ?>
                                <a href="mailto:<?php echo htmlspecialchars($email_address); ?>" class="cell-email-link" title="<?php echo htmlspecialchars($email_address); ?>">
                                    <?php echo htmlspecialchars($email_address); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-phone" title="<?php echo htmlspecialchars($phone_number); ?>">
                            <?php if (!empty($phone_number)): ?>
                                <span class="cell-truncate"><?php echo htmlspecialchars($phone_number); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-city" title="<?php echo htmlspecialchars($city); ?>">
                            <?php if (!empty($city)): ?>
                                <span class="cell-truncate"><?php echo htmlspecialchars($city); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-country" title="<?php echo htmlspecialchars($row['country'] ?? ''); ?>"><?php echo render_country_flags($row['country'] ?? ''); ?></td>
                        <td class="col-company" title="<?php echo htmlspecialchars($company_name); ?>">
                            <?php if (!empty($company_name)): ?>
                                <span class="cell-truncate"><?php echo htmlspecialchars($company_name); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-website" title="<?php echo htmlspecialchars($company_website); ?>">
                            <?php if (!empty($company_website)): ?>
                                <a href="<?php echo htmlspecialchars($company_website); ?>" target="_blank" rel="noopener noreferrer" class="cell-website-link" title="<?php echo htmlspecialchars($company_website); ?>">
                                    <span class="cell-website-text"><?php echo htmlspecialchars($company_website); ?></span>
                                    <i class="fas fa-external-link-alt cell-website-icon"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-lead-type" title="<?php echo htmlspecialchars($lead_type); ?>">
                            <?php if (!empty($lead_type)): ?>
                                <span class="portfolio-badge badge-cms" title="<?php echo htmlspecialchars($lead_type); ?>"><?php echo htmlspecialchars($lead_type); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-status" title="<?php echo htmlspecialchars($client_status); ?>">
                            <?php if (!empty($client_status)): ?>
                                <span class="portfolio-badge badge-tech" title="<?php echo htmlspecialchars($client_status); ?>"><?php echo htmlspecialchars($client_status); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-action" style="text-align: center; white-space: nowrap;">
                            <div class="table-actions-inline" style="justify-content: center; gap: 4px;">
                                <a href="edit-client.php?id=<?php echo $row['id']; ?>" class="btn-table-action btn-table-view" title="Edit Client">
                                    <i class="fas fa-edit"></i> <span>Edit</span>
                                </a>
                                <button type="button" class="btn-table-action btn-table-delete delete-btn" data-id="<?php echo $row['id']; ?>" title="Delete Client">
                                    <i class="fas fa-trash-alt"></i> <span>Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
            <?php
                    $i++;
                }
            } else {
            ?>
                <tr>
                    <td colspan="10" class="empty-table-cell">
                        <div class="empty-state-wrap">
                            <i class="fas fa-users empty-icon"></i>
                            <div class="empty-text">No Clients Found</div>
                            <div class="empty-subtext">Click the "+ Add Client" button above to add your first client.</div>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
