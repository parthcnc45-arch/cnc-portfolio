<?php
if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
    header('location: portfolios.php');
    exit;
}

include_once('header.php');
include_once('sidebar.php');

$id = intval($_REQUEST['id']);
$result = mysqli_query($conn, "SELECT * FROM portfolio WHERE `id`=$id AND `deleted`='0'");
$count = mysqli_num_rows($result);

if ($count < 1) {
    echo "<script> alert('Portfolio not found!'); window.location.href='portfolios.php'; </script>";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Parse JSON fields
$deliverables_outlined = !empty($row['deliverables_outlined']) ? json_decode($row['deliverables_outlined'], true) : [];
if (!is_array($deliverables_outlined) && !empty($row['deliverables_outlined'])) {
    $deliverables_outlined = [$row['deliverables_outlined']];
}

$unique_or_interactive_elements = !empty($row['unique_or_interactive_elements']) ? json_decode($row['unique_or_interactive_elements'], true) : [];
if (!is_array($unique_or_interactive_elements)) {
    $unique_or_interactive_elements = [];
}
$unique_elements_image = $unique_or_interactive_elements['unique_elements_image'] ?? [];
$unique_elements_title = $unique_or_interactive_elements['unique_elements_title'] ?? [];
$unique_elements_content = $unique_or_interactive_elements['unique_elements_content'] ?? [];

$success_story_elem = !empty($row['success_story_list']) ? json_decode($row['success_story_list'], true) : [];
if (!is_array($success_story_elem)) {
    $success_story_elem = [];
}
$success_image = $success_story_elem['success_image'] ?? [];
$success_number = $success_story_elem['success_number'] ?? [];
$success_subtitle = $success_story_elem['success_subtitle'] ?? [];

function render_pill_list($str, $empty_placeholder = '-') {
    if (empty(trim((string)$str))) {
        return '<span class="text-muted">' . $empty_placeholder . '</span>';
    }
    $items = array_map('trim', explode(',', $str));
    $out = '<div class="d-flex flex-wrap" style="gap: 5px;">';
    $found = false;
    foreach ($items as $item) {
        if (!empty($item)) {
            $found = true;
            $out .= '<span class="view-pill">' . htmlspecialchars($item) . '</span>';
        }
    }
    $out .= '</div>';
    return $found ? $out : '<span class="text-muted">' . $empty_placeholder . '</span>';
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* View Portfolio Styles matching Edit/Add Form */
    .view-portfolio-container {
        padding-bottom: 40px;
    }
    .view-hero-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 18px 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
    }
    /* Modern Hero Action Buttons */
    .view-btn-action {
        height: 38px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 18px;
        font-size: 13.5px;
        font-weight: 600;
        border-radius: 6px;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        line-height: 1;
    }
    .view-btn-edit {
        background: linear-gradient(135deg, #7571f9 0%, #605af7 100%);
        color: #ffffff !important;
        border: 1px solid #605af7;
        box-shadow: 0 4px 12px rgba(117, 113, 249, 0.3);
    }
    .view-btn-edit:hover {
        background: linear-gradient(135deg, #605af7 0%, #4a43f5 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(117, 113, 249, 0.45);
    }
    .view-btn-live {
        background-color: #ffffff;
        color: #7571f9 !important;
        border: 1.5px solid #7571f9;
        box-shadow: 0 2px 6px rgba(117, 113, 249, 0.1);
    }
    .view-btn-live:hover {
        background-color: #7571f9;
        color: #ffffff !important;
        border-color: #7571f9;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(117, 113, 249, 0.35);
    }
    .view-btn-live i, .view-btn-edit i {
        font-size: 13.5px;
    }
    .view-main-card {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        background: #ffffff;
    }
    .view-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1.5px solid #f1f5f9;
        padding-bottom: 8px;
        margin-bottom: 18px;
    }
    .view-section-title h3 {
        font-size: 16.5px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .view-section-num {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #eef2ff;
        color: #7571f9;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .view-label {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        padding: 0;
        line-height: 1.4;
        display: block;
    }
    .view-readonly-box {
        min-height: 44px;
        background-color: #ffffff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 8px 14px;
        font-size: 13.5px;
        color: #334155;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        word-break: break-word;
        transition: all 0.15s ease;
    }
    .view-readonly-box:hover {
        border-color: #a5b4fc;
        background-color: #fafbfc;
    }
    .view-readonly-box.textarea-box {
        min-height: 56px;
        align-items: flex-start;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    .view-readonly-box .text-muted {
        color: #94a3b8 !important;
        font-style: italic;
        font-size: 13px;
    }
    .view-pill {
        display: inline-flex;
        align-items: center;
        background-color: #7571f9;
        color: #ffffff !important;
        border-radius: 4px;
        padding: 3px 10px;
        font-size: 12.5px;
        font-weight: 500;
        line-height: 1.4;
        margin: 2px 0;
        box-shadow: 0 2px 6px rgba(117, 113, 249, 0.25);
    }
</style>

<div class="content-body">
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="portfolios.php">Portfolio</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">View Portfolio</a></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid view-portfolio-container">
        <!-- Hero Header Card with Title and Actions -->
        <div class="view-hero-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 15px;">
                <div class="d-flex align-items-center" style="gap: 16px;">
                    <?php if (!empty($row['site_preview_image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['site_preview_image']); ?>" alt="Logo" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1.5px solid #edf0f5;">
                    <?php elseif (!empty($row['logo'])): ?>
                        <img src="<?php echo htmlspecialchars($row['logo']); ?>" alt="Logo" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1.5px solid #edf0f5;">
                    <?php else: ?>
                        <div style="width: 56px; height: 56px; border-radius: 8px; background: #eef2ff; color: #7571f9; font-size: 24px; font-weight: 700; display: flex; align-items: center; justify-content: center; border: 1.5px solid #e0e7ff;">
                            <?php echo strtoupper(substr($row['company_name'] ?? 'P', 0, 1)); ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <h2 style="font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 6px 0; line-height: 1.2;">
                            <?php echo htmlspecialchars($row['company_name'] ?? 'Untitled Portfolio'); ?>
                        </h2>
                        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <span class="badge" style="background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 12px; font-weight: 600; padding: 4px 10px;">
                                <i class="fas fa-layer-group mr-1"></i> <?php echo htmlspecialchars($row['type'] ?? 'Portfolio'); ?>
                            </span>
                            <?php if (!empty($row['country'])): ?>
                                <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; font-size: 12px; font-weight: 500; padding: 4px 10px;">
                                    <i class="fas fa-globe mr-1"></i> <?php echo htmlspecialchars($row['country']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center" style="gap: 12px;">
                    <a href="edit-portfolio.php?id=<?php echo $id; ?>" class="view-btn-action view-btn-edit">
                        <i class="far fa-edit"></i> <span>Edit Portfolio</span>
                    </a>
                    <?php if (!empty($row['website_url'])): ?>
                        <a href="<?php echo htmlspecialchars($row['website_url']); ?>" target="_blank" class="view-btn-action view-btn-live">
                            <i class="fas fa-external-link-alt"></i> <span>Live Site</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Card Form Wrapper (Continuous single-page view) -->
        <div class="card view-main-card">
            <div class="card-body p-4">

                <!-- 1. Basic Information -->
                <div class="view-section-title">
                    <span class="view-section-num">1</span>
                    <h3>Basic Information</h3>
                </div>
                <div class="form-group row">
                    <!-- Type -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Type</label>
                        <div class="view-readonly-box">
                            <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 13px; font-weight: 600; padding: 4px 10px;">
                                <?php echo htmlspecialchars($row['type'] ?? 'Portfolio'); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Website URL -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Website URL</label>
                        <div class="view-readonly-box d-flex justify-content-between align-items-center">
                            <?php if (!empty($row['website_url'])): ?>
                                <a href="<?php echo htmlspecialchars($row['website_url']); ?>" target="_blank" class="text-primary font-weight-500 d-inline-flex align-items-center text-truncate mr-2" style="max-width: calc(100% - 70px); gap: 6px;">
                                    <span class="text-truncate"><?php echo htmlspecialchars($row['website_url']); ?></span>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                            <?php if (!empty($row['site_preview_image'])): ?>
                                <img src="<?php echo htmlspecialchars($row['site_preview_image']); ?>" alt="Preview" style="max-height: 32px; max-width: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Company Name -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Company Name</label>
                        <div class="view-readonly-box font-weight-bold">
                            <?php echo htmlspecialchars($row['company_name'] ?? '-'); ?>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Country</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['country'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Industries -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Industries</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['industries'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Niche (Subcategory) -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Niche <span class="text-muted font-weight-normal">(Subcategory of industry)</span></label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['niche'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Title</label>
                        <div class="view-readonly-box">
                            <?php echo !empty(trim($row['title'] ?? '')) ? htmlspecialchars($row['title']) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Sector -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Sector</label>
                        <div class="view-readonly-box">
                            <?php echo !empty(trim($row['sector'] ?? '')) ? htmlspecialchars($row['sector']) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Featured Video -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Featured Video</label>
                        <div class="view-readonly-box">
                            <?php if (!empty($row['featured_video'])): ?>
                                <a href="<?php echo htmlspecialchars($row['featured_video']); ?>" target="_blank" class="text-primary font-weight-500 d-inline-flex align-items-center" style="gap: 6px;">
                                    <i class="fas fa-play-circle text-danger"></i> <span>Watch Video</span>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Featured Image</label>
                        <div class="view-readonly-box d-flex justify-content-between align-items-center">
                            <?php if (!empty($row['featured_image'])): ?>
                                <a href="<?php echo htmlspecialchars($row['featured_image']); ?>" target="_blank" class="text-primary text-truncate mr-2" style="max-width: calc(100% - 60px);">
                                    <i class="fas fa-image mr-1"></i> <?php echo htmlspecialchars($row['featured_image']); ?>
                                </a>
                                <img src="<?php echo htmlspecialchars($row['featured_image']); ?>" alt="Featured Image" style="max-height: 32px; max-width: 50px; border-radius: 4px; border: 1px solid #e2e8f0; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Logo</label>
                        <div class="view-readonly-box d-flex justify-content-between align-items-center">
                            <?php if (!empty($row['logo'])): ?>
                                <a href="<?php echo htmlspecialchars($row['logo']); ?>" target="_blank" class="text-primary text-truncate mr-2" style="max-width: calc(100% - 60px);">
                                    <i class="fas fa-image mr-1"></i> <?php echo htmlspecialchars($row['logo']); ?>
                                </a>
                                <img src="<?php echo htmlspecialchars($row['logo']); ?>" alt="Logo" style="max-height: 32px; max-width: 50px; border-radius: 4px; border: 1px solid #e2e8f0; object-fit: contain;">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tag Logo -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Tag Logo</label>
                        <div class="view-readonly-box d-flex justify-content-between align-items-center">
                            <?php if (!empty($row['tag_logo'])): ?>
                                <a href="<?php echo htmlspecialchars($row['tag_logo']); ?>" target="_blank" class="text-primary text-truncate mr-2" style="max-width: calc(100% - 60px);">
                                    <i class="fas fa-image mr-1"></i> <?php echo htmlspecialchars($row['tag_logo']); ?>
                                </a>
                                <img src="<?php echo htmlspecialchars($row['tag_logo']); ?>" alt="Tag Logo" style="max-height: 32px; max-width: 50px; border-radius: 4px; border: 1px solid #e2e8f0; object-fit: contain;">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 2. Business Overview -->
                <div class="view-section-title mt-4">
                    <span class="view-section-num">2</span>
                    <h3>Business Overview</h3>
                </div>
                <div class="form-group row">
                    <!-- Current Status -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Current Status</label>
                        <div class="view-readonly-box">
                            <?php if (($row['current_status'] ?? '') === 'working'): ?>
                                <span class="badge" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 13px; font-weight: 600; padding: 4px 12px;">
                                    <i class="fas fa-check-circle mr-1"></i> Working
                                </span>
                            <?php elseif (($row['current_status'] ?? '') === 'not-working'): ?>
                                <span class="badge" style="background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; font-size: 13px; font-weight: 600; padding: 4px 12px;">
                                    <i class="fas fa-times-circle mr-1"></i> Not Working
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Tags</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['tags'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- About Business (full width) -->
                    <div class="col-12 mb-3">
                        <label class="col-form-label view-label">About Business <span class="text-muted font-weight-normal">(Brief description of the company)</span></label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['about_business'] ?? '')) ? nl2br(htmlspecialchars($row['about_business'])) : '<span class="text-muted">No description provided</span>'; ?>
                        </div>
                    </div>
                </div>

                <!-- 3. Tech Stack -->
                <div class="view-section-title mt-4">
                    <span class="view-section-num">3</span>
                    <h3>Tech Stack</h3>
                </div>
                <div class="form-group row">
                    <!-- Programming Language -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">Programming Language</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['programming_language'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- CMS / Website Builder -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">CMS / Website Builder</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['cms_plateform'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Backend Tech -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">Backend Technologies</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['backend_tech'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Frontend Tech -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">Frontend Technologies</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['frontend_tech'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Hosting Tech -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">Hosting / Cloud Services</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['hosting_tech'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Other Tech -->
                    <div class="col-xl-4 col-md-6 mb-3">
                        <label class="col-form-label view-label">Other Technologies</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['other_tech'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="col-12 mb-3">
                        <label class="col-form-label view-label">Role <span class="text-muted font-weight-normal">(UI/UX design, front-end development, SEO, etc.)</span></label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['role'] ?? '')) ? nl2br(htmlspecialchars($row['role'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="col-12 mb-3">
                        <label class="col-form-label view-label">Features <span class="text-muted font-weight-normal">(Responsive design, fast load times, contact form, etc.)</span></label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['features'] ?? '')) ? nl2br(htmlspecialchars($row['features'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>
                </div>

                <!-- 4. Portfolio Details -->
                <div class="view-section-title mt-4">
                    <span class="view-section-num">4</span>
                    <h3>Portfolio Details</h3>
                </div>
                <div class="form-group row">
                    <!-- Portfolio Type -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Portfolio Type</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['portfolio_type'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Design URL -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Design URL</label>
                        <div class="view-readonly-box">
                            <?php if (!empty($row['design_url'])): ?>
                                <a href="<?php echo htmlspecialchars($row['design_url']); ?>" target="_blank" class="text-primary font-weight-500 d-inline-flex align-items-center" style="gap: 6px;">
                                    <i class="fas fa-paint-brush"></i> <span>View Design</span>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- GitHub Repository -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">GitHub Repository</label>
                        <div class="view-readonly-box">
                            <?php if (!empty($row['github_repository'])): ?>
                                <a href="<?php echo htmlspecialchars($row['github_repository']); ?>" target="_blank" class="text-primary font-weight-500 d-inline-flex align-items-center" style="gap: 6px;">
                                    <i class="fab fa-github"></i> <span>Open Repository</span>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Demo Video -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Demo Video</label>
                        <div class="view-readonly-box">
                            <?php if (!empty($row['demo_video'])): ?>
                                <a href="<?php echo htmlspecialchars($row['demo_video']); ?>" target="_blank" class="text-primary font-weight-500 d-inline-flex align-items-center" style="gap: 6px;">
                                    <i class="fas fa-video"></i> <span>Watch Demo</span>
                                    <i class="fas fa-external-link-alt" style="font-size: 11px;"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- 5. Team Associates & Additional Notes -->
                <div class="view-section-title mt-4">
                    <span class="view-section-num">5</span>
                    <h3>Team Associates & Additional Notes</h3>
                </div>
                <div class="form-group row">
                    <!-- Team Associates -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Team Associates</label>
                        <div class="view-readonly-box">
                            <?php echo render_pill_list($row['team_associates'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Comments <span class="text-muted font-weight-normal">(Additional notes)</span></label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['comments'] ?? '')) ? nl2br(htmlspecialchars($row['comments'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>
                </div>

                <!-- 6. Other Information (Case Study) -->
                <div class="view-section-title mt-4">
                    <span class="view-section-num">6</span>
                    <h3>Other Information (Case Study)</h3>
                </div>
                <div class="form-group row">
                    <!-- Initial Goal -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Initial Goal or Purpose Content</label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['initial_goal'] ?? '')) ? nl2br(htmlspecialchars($row['initial_goal'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Pain Area -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Pain Area Content</label>
                        <div class="view-readonly-box textarea-box d-flex flex-column justify-content-between">
                            <div>
                                <?php echo !empty(trim($row['pain_area'] ?? '')) ? nl2br(htmlspecialchars($row['pain_area'])) : '<span class="text-muted">-</span>'; ?>
                            </div>
                            <?php if (!empty($row['pain_area_image'])): ?>
                                <div class="mt-2 pt-2 border-top w-100">
                                    <a href="<?php echo htmlspecialchars($row['pain_area_image']); ?>" target="_blank" class="d-inline-flex align-items-center text-primary" style="gap: 8px;">
                                        <img src="<?php echo htmlspecialchars($row['pain_area_image']); ?>" alt="Pain Area" style="max-height: 40px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                        <span><i class="fas fa-external-link-alt mr-1"></i> View Pain Area Image</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Development Approach -->
                    <div class="col-12 mb-3">
                        <label class="col-form-label view-label">Development Approach Content</label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['development_approach'] ?? '')) ? nl2br(htmlspecialchars($row['development_approach'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Deliverables Outlined -->
                    <div class="col-12 mb-3">
                        <label class="col-form-label view-label">Deliverables Outlined Content</label>
                        <?php if (!empty($deliverables_outlined) && count(array_filter($deliverables_outlined)) > 0): ?>
                            <div class="d-flex flex-column" style="gap: 8px;">
                                <?php foreach ($deliverables_outlined as $deliv): ?>
                                    <?php if (!empty(trim((string)$deliv))): ?>
                                        <div class="view-readonly-box" style="line-height: 1.5; white-space: pre-wrap;">
                                            <i class="fas fa-check-circle text-success mr-2 mt-1 align-self-start"></i>
                                            <div class="flex-grow-1"><?php echo nl2br(htmlspecialchars($deliv)); ?></div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="view-readonly-box"><span class="text-muted">-</span></div>
                        <?php endif; ?>
                    </div>

                    <!-- Challenges Faced -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Challenges Faced During Development</label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['challenges_faced_during_development'] ?? '')) ? nl2br(htmlspecialchars($row['challenges_faced_during_development'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>

                    <!-- Solution -->
                    <div class="col-xl-6 col-lg-6 mb-3">
                        <label class="col-form-label view-label">Solution</label>
                        <div class="view-readonly-box textarea-box">
                            <?php echo !empty(trim($row['solution'] ?? '')) ? nl2br(htmlspecialchars($row['solution'])) : '<span class="text-muted">-</span>'; ?>
                        </div>
                    </div>
                </div>

                <!-- Unique or Interactive Elements -->
                <?php if (!empty($unique_elements_title) && count($unique_elements_title) > 0): ?>
                    <div class="view-section-title mt-4">
                        <span class="view-section-num">7</span>
                        <h3>Unique or Interactive Elements</h3>
                    </div>
                    <div class="row">
                        <?php foreach ($unique_elements_title as $idx => $uTitle): ?>
                            <?php 
                                $uImg = $unique_elements_image[$idx] ?? '';
                                $uContent = $unique_elements_content[$idx] ?? '';
                            ?>
                            <div class="col-md-6 mb-3">
                                <div class="card border h-100 shadow-none" style="background: #fafafa; border-color: #e2e8f0 !important;">
                                    <div class="card-body p-3">
                                        <h5 class="font-weight-bold text-dark mb-1" style="font-size: 14.5px;"><?php echo htmlspecialchars($uTitle); ?></h5>
                                        <p class="text-muted mb-2" style="font-size: 13px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($uContent)); ?></p>
                                        <?php if (!empty($uImg)): ?>
                                            <a href="<?php echo htmlspecialchars($uImg); ?>" target="_blank">
                                                <img src="<?php echo htmlspecialchars($uImg); ?>" alt="Preview" style="max-height: 80px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Success Story -->
                <?php if (!empty($success_number) && count($success_number) > 0): ?>
                    <div class="view-section-title mt-4">
                        <span class="view-section-num"><?php echo (!empty($unique_elements_title) && count($unique_elements_title) > 0) ? '8' : '7'; ?></span>
                        <h3>Success Story</h3>
                    </div>
                    <div class="d-flex flex-wrap" style="gap: 15px;">
                        <?php foreach ($success_number as $sIdx => $sNum): ?>
                            <div class="p-3 border rounded text-center" style="min-width: 140px; background: #fafafa; border-color: #e2e8f0 !important;">
                                <?php if (!empty($success_image[$sIdx])): ?>
                                    <img src="<?php echo htmlspecialchars($success_image[$sIdx]); ?>" style="width: 32px; height: 32px; object-fit: contain; margin-bottom: 6px;">
                                <?php endif; ?>
                                <div style="font-size: 22px; font-weight: 700; color: #7571f9;"><?php echo htmlspecialchars($sNum); ?></div>
                                <div style="font-size: 12.5px; color: #64748b; font-weight: 500;"><?php echo htmlspecialchars($success_subtitle[$sIdx] ?? ''); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>
