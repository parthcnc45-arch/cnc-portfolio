<?php
if(!isset($_REQUEST['id'])){
    header('location: index.php');
    exit;
}
include_once('header.php');
include_once('sidebar.php');
$id = $_REQUEST['id'];
$result = mysqli_query($conn,"SELECT * FROM portfolio WHERE `id`=$id");
$count = mysqli_num_rows($result);
if($count < 1){
    echo "<script> location.href='index.php'; </script>";
        exit;
}
$row = mysqli_fetch_assoc($result);

$selectedIndustries             = explode(', ', $row['industries']);
$selectedFramework              = explode(', ', $row['framework']);   
$selectedCMSPlatform            = explode(', ', $row['cms_plateform']); 
$selectedBackendTech            = explode(', ', $row['backend_tech']); 
$selectedFrontendTech           = explode(', ', $row['frontend_tech']); 
$selectedHostingTech            = explode(', ', $row['hosting_tech']); 
$selectedOtherTech              = explode(', ', $row['other_tech']); 
$selectedProgrammingLanguage    = explode(', ', $row['programming_language']);
$selectedPortfolioType          = explode(', ', $row['portfolio_type']);
$selectedTechnology             = explode(', ', $row['technology']);
$selectedCountry = explode(',', $row['country']);
$deliverables_outlined = !empty($row['deliverables_outlined']) ? json_decode($row['deliverables_outlined'], true) : [];

$unique_or_interactive_elements = json_decode($row['unique_or_interactive_elements'], true);
if (!is_array($unique_or_interactive_elements)) {
    $unique_or_interactive_elements = [];
}
$unique_elements_image = $unique_or_interactive_elements['unique_elements_image'] ?? [];
$unique_elements_title = $unique_or_interactive_elements['unique_elements_title'] ?? [];
$unique_elements_content = $unique_or_interactive_elements['unique_elements_content'] ?? [];

$success_story_elem = json_decode($row['success_story_list'], true);
if (!is_array($success_story_elem)) {
    $success_story_elem = [];
}
$success_image = $success_story_elem['success_image'] ?? [];
$success_number = $success_story_elem['success_number'] ?? [];
$success_subtitle = $success_story_elem['success_subtitle'] ?? [];

function selected_option_caller($conn,$selectedIndustries,$table_name,$col_name){
    $options = "";
    $Result_getter = mysqli_query($conn, "SELECT id, $col_name FROM $table_name");
    while ($main_adapter = mysqli_fetch_assoc($Result_getter)) {
        $adapterID = $main_adapter['id'];
        $adapterName = $main_adapter[$col_name];

        // Check if this industry is in the selected industries array
        $selected_Adapter = in_array($adapterName, $selectedIndustries) ? 'selected' : '';
        $options .= "<option value='$adapterID' $selected_Adapter>$adapterName</option>";       
        
    }
    return $options;
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" defer></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/assets/app.css">
<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/marcellov7/tex@main/src/tex.min.css">
<script src="https://cdn.jsdelivr.net/gh/marcellov7/tex@main/src/tex.min.js"></script>
<style>
   .label_tooltip {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
    .tooltip {
        position: static;
        display: inline-block;
        opacity: 1;
        z-index: auto;
    }

    .tooltip-icon {
        background: #76838f;
        color: #ffffff;
        border-radius: 50%;
        font-size: 10px;
        font-weight: bold;
        cursor: pointer;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        margin-bottom: 0;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 25vw;
        background-color: black;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 10px;
        position: absolute;
        z-index: 2;
        right: 0;
        top: 30px;
        overflow: hidden;
        display: inline-block;
        transition: ease 0.3s all;
    }
    .tooltip .tooltiptext img{width: 100%; height: auto; object-fit: cover;}

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
   .bootstrap-select .dropdown-toggle .filter-option { background-color: #fff; border: 1px solid #ced4da; }
    .tags-input-wrapper .tag a { margin: 0 7px 3px; display: inline-block; cursor: pointer; }
    .tags-input-wrapper .tag { display: inline-block; background-color: #7571f9; color: white; border-radius: 40px; padding: 0px 3px 0px 7px; margin-right: 5px; margin-bottom: 5px; box-shadow: 0 5px 15px -2px rgb(117, 113, 249) }
    .tags-input-wrapper input { border: none; background: transparent; outline: none; width: 140px; margin-left: 8px; }
    .tags-input-wrapper { background: transparent; padding: 10px; border-radius: 4px; max-width: 400px; border: 1px solid #ccc }
    .company_img { width: 100%; height: 100px; display: none; }
    .autocomplete { position: relative; display: inline-block; }
    .autocomplete-items { position: absolute; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; z-index: 99; }
    .autocomplete-item { padding: 10px; cursor: pointer; }
    .autocomplete-item:hover { background-color: #e0e0e0; }
    .form-validation .form-valide h3{margin-bottom: 20px; background: #7571f9; padding: 10px; color: #fff;border-radius: 0.25rem;}
    .form-validation .form-valide .form-group .field-wrapper{margin-bottom: 15px;}
    .form-validation .form-valide .form-group .field-wrapper .col-form-label {max-width:100%;width: 100%;font-size: 16px;font-weight:bold;}
    .form-validation .form-valide .form-group .field-wrapper.field-radio  .form-check.form-check-inline:first-child{margin-right:20px;}
    .form-validation .form-valide .form-group .field-wrapper.field-radio  .form-check  .form-check-label{font-size: 16px;font-weight:bold;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection { box-shadow: none;display: block; width: 100%; min-height: 45px; padding: 0; font-size: 0rem; line-height: 1.5; color: #495057; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection .select2-selection__rendered{line-height:38px;padding-left: 12px;padding-right: 30px;padding-top: 5px;padding-bottom: 5px;font-size: 14px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection .select2-selection__rendered .select2-selection__placeholder{font-size: 0.875rem; line-height: 1.5;     color: #7b7777;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection  .select2-selection__arrow{top: 11px;right: 12px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .bootstrap-tagsinput {display: block; width: 100%; min-height: 43px; padding: 0.375rem 0.75rem; font-size: 0.875rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da;border-radius: 0.25rem;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection ul.select2-selection__rendered{padding: 0 5px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection ul.select2-selection__rendered .select2-search.select2-search--inline{width: 100%;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection .select2-selection__rendered li input{margin: 0;padding: 0 12px;width: 100% !important;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap textarea {min-height: 90px;height: 100%;padding: 10px 12px;}
    .form-validation .form-valide .form-group button , #deliverablesContainer .deliverable-item .btn{padding: 10px 15px;min-width:60px;font-size: 18px; font-weight: 500;}
    #current_status{-webkit-appearance: none; appearance: none;}
    #select2-selection__arrow{height: 26px; position: absolute; top: 9px; right: 9px; width: 20px;}
    #select2-selection__arrow b{    border-color: #888 transparent transparent transparent; border-style: solid; border-width: 5px 4px 0 4px; height: 0; left: 50%; margin-left: -4px; margin-top: -2px; position: absolute; top: 50%; width: 0;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap.image-brand{position: relative;display:flex;gap: 10px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap.image-brand .form-control , .form-validation .form-valide .form-group .field-wrapper .field-wrap.image-brand .image_preview{max-width:50%;width: 100%;}
    #role{padding: 0.375rem 0.75rem;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .deliverable-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #successContainer .success-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #uniqueelementContainer .uniqueelement-item{align-items: flex-start;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .bootstrap-tagsinput .twitter-typeahead{line-height:2;}
    .form-control{border-radius: 0.25rem;}
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #7571f9;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__rendered li.select2-selection__choice {min-width: 60px;font-size: 14px;margin: 8px 5px; background: #7571f9; color: #fff;position: relative;padding: 0px 30px 0px 5px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {position: absolute;right: 10px;color: #fff;}
    .content-body nav .nav-tabs { border-bottom: 1px solid #7571f9; padding-bottom: 10px;gap: 10px;}
    .content-body nav  .nav-tabs .nav-link {border: 1px solid #7571f9;border-radius: 0.25rem;cursor: pointer;}
    .content-body nav .nav-tabs .nav-link.active, .content-body nav .nav-tabs .nav-item.show .nav-link ,  .content-body nav  .nav-tabs .nav-link:hover{ color: #ffffff; background-color: #7571f9;border-color: #7571f9;transition: all 0.3s ease;}
    .content-body nav .nav-tabs .nav-link:focus, .content-body nav .nav-tabs .nav-item.show .nav-link:focus{outline: none;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap #deliverablesContainer .deliverable-item ,  .form-validation .form-valide .form-group .field-wrapper .field-wrap #uniqueelementContainer .uniqueelement-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #successContainer .success-item{margin-bottom: 15px;} 
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .deliverable-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #uniqueelementContainer .uniqueelement-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #successContainer .success-item{display:flex;gap: 15px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .deliverable-item textarea ,  .form-validation .form-valide .form-group .field-wrapper .field-wrap #uniqueelementContainer .uniqueelement-item textarea{min-height: 8px;padding: 5px 10px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .add-more{text-align:right;}
    #company_image{width: auto;max-width:100%;height: auto;margin: auto;}
    .tex-container .htmlContent { min-height: 300px !important; }
    #deliverablesContainer .deliverable-item .tex-container {width: 100%;}
    @media (max-width:1700px) {
        .form-validation .form-valide .form-group .field-wrapper .col-form-label{font-size:13px;font-weight:500;}
        .form-validation .form-valide .form-group .field-wrapper.field-radio .col-form-label{font-size: 16px;}
        .form-validation .form-valide .form-group .field-wrapper.field-radio  .form-check  .form-check-label{font-weight:500;}
    }

    @media (max-width:1440px){
        .tooltip .tooltiptext {width: 35vw;}
    }
    @media (max-width:1200px){
        .tooltip .tooltiptext {width: 80vw;}
    }
    @media (max-width:991px) {
        .form-validation .form-valide h3 {font-size: 22px;}
        .form-validation .form-valide .form-group .field-wrapper .field-wrap .deliverable-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #uniqueelementContainer .uniqueelement-item , .form-validation .form-valide .form-group .field-wrapper .field-wrap #successContainer .success-item{flex-direction:column;align-items: flex-end;}
    }
    .btn-back-custom {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #5b52f9 !important;
        background-color: #ede9fe;
        border: 1px solid #ddd6fe;
        border-radius: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        line-height: normal;
        cursor: pointer;
    }
    .btn-back-custom:hover {
        background-color: #5b52f9;
        border-color: #5b52f9;
        color: #ffffff !important;
        transform: translateX(-2px);
        box-shadow: 0 3px 8px rgba(91, 82, 249, 0.25);
    }
    .btn-back-custom svg {
        transition: transform 0.2s ease;
    }
    .btn-back-custom:hover svg {
        transform: translateX(-2px);
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
    <!-- row -->
    <div class="container-fluid">
        <div class="mb-3">
            <a href="portfolios.php" class="btn-back-custom" id="btnBackToPortfolios">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Back</span>
            </a>
        </div>
        <nav>
			<div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-baisc-tab" data-bs-toggle="tab" data-bs-target="#nav-basic" type="button" role="tab" aria-controls="nav-basic" aria-selected="true">Basic Details</button>
				<button class="nav-link" id="nav-other-tab" data-bs-toggle="tab" data-bs-target="#nav-other" type="button" role="tab" aria-controls="nav-other" aria-selected="false">Other Details</button>				
			</div>
		</nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="row justify-content-center">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-validation">
                                <form class="form-valide" action="" method="post" enctype="multipart/form-data">
                                    <div class="tab-pane fade active show" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">
                                        <h3>1. Basic Information</h3>
                                        <div class="form-group row">
                                            <div class="col-xl-6">
                                                <div class="field-wrapper field-radio">
                                                    <label class="col-form-label" for="type">Type</label>
                                                    <div class="field-wrap">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="type" id="typePortfolio" value="Portfolio"
                                                                <?php echo ($row['type'] == 'Portfolio') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="typePortfolio">Portfolio</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="type" id="typeCaseStudy" value="Case Study"
                                                                <?php echo ($row['type'] == 'Case Study') ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="typeCaseStudy">Case Study</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="website_url">Website URL<!--<span
                                                    class="text-danger">*</span>-->
                                                    </label>
                                                    <div class="field-wrap image-brand">
                                                        <input type="text" class="form-control" id="website_url" name="website_url"
                                                            value="<?php echo $row['website_url'];?>" placeholder="Enter Website url"
                                                            autofocus>
                                                        <div class="web_loader" style="display: none;">
                                                            <svg class="circular" viewBox="25 25 50 50">
                                                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                                                                    stroke-miterlimit="10" />
                                                            </svg>
                                                        </div>
                                                        <div class="image_preview"><img src="<?php echo $row['site_preview_image'];?>"
                                                            id="company_image" class="company_img" style="display:block;"></div>
                                                    </div>                                            
                                                </div>
                                            </div> 

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="company_name">Company Name <!--<span
                                                    class="text-danger">*</span>-->
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input type="text" class="form-control" id="company_name"
                                                            value="<?php echo $row['company_name'];?>" name="company_name"
                                                            placeholder="Enter Company Name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="country">Country<!--<span
                                                    class="text-danger">*</span>-->
                                                    </label>
                                                    <div class="field-wrap">
                                                        <select id="countrySelect" class="select2 form-control" name="country[]"  multiple data-selected='<?php echo json_encode($selectedCountry); ?>'>                                                      
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="industries">Industries <!--<span
                                                    class="text-danger">*</span>-->
                                                    </label>
                                                    <div class="field-wrap">                                       
                                                        <select id="industries" class="form-control" name="industries[]" multiple>
                                                        <?php
                                                            $table_name = "industries";
                                                            $col_name = "industrie_name";                                           
                                                            echo selected_option_caller($conn,$selectedIndustries,$table_name,$col_name);                                        
                                                        ?>
                                                        </select> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="niche">Niche  <!--<span
                                                    class="text-danger">*</span>--><br>(Subcategory of industry)
                                                    </label>
                                                    <div class="field-wrap">                                
                                                        <input type="text" id="niche" name="niche" value="<?php echo $row['niche'];?>" data-role="tagsinput" placeholder="Add Niche"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="featured_image">Featured Image  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="featured_image" name="featured_image" class="form-control" value="<?php echo $row['featured_image'];?>" placeholder="Enter Featured Image URL">                                        
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="logo">Logo  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="logo" name="logo" class="form-control" value="<?php echo $row['logo'];?>" placeholder="Enter Logo URL">                                        
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="title">Title  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="title" name="title" class="form-control" value="<?php echo $row['title'];?>" placeholder="Enter Title">                                        
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="sector">Sector  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="sector" name="sector" class="form-control" value="<?php echo $row['sector'];?>" placeholder="Enter Sector">                                        
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="featured_video">Featured Video  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="featured_video" name="featured_video" class="form-control" value="<?php echo $row['featured_video'];?>" placeholder="Enter Featured Video URL">                                        
                                                    </div>
                                                </div>    
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" class="form-control" for="tag_logo">Tag Logo  <!--<span
                                                    class="text-danger">*</span>--></label>
                                                    <div class="field-wrap">
                                                        <input type="text" id="tag_logo" name="tag_logo" class="form-control" value="<?php echo $row['tag_logo'];?>" placeholder="Enter Tag Logo URL">                                        
                                                    </div>
                                                </div>    
                                            </div>
                                        </div>

                                        <h3>2. Business Overview</h3> 
                                        <div class="form-group row">
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="about_business">About Business  <!--<span
                                                    class="text-danger">*</span>--><br>(Brief description of the company)
                                                    </label>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="about_business" name="about_business"
                                                            placeholder="Enter About Business"><?php echo $row['about_business'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">                                                    
                                                    <label class="col-form-label" for="current_status">Current status
                                                    </label>
                                                    <div class="field-wrap">
                                                        <select class="form-control" id="current_status" name="current_status">
                                                            <option value="">Please select</option>
                                                            <option value="working"
                                                                <?php echo ($row['current_status'] == 'working')?'selected':'';?>>
                                                                Working</option>
                                                            <option value="not-working"
                                                                <?php echo ($row['current_status'] == 'not-working')?'selected':'';?>>
                                                                Not-working</option>
                                                        </select>
                                                        <span id="select2-selection__arrow" class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="tags">Tags<br>(Note: Please use ENTER
                                                    key to add multiple tags)
                                                    </label>                                            
                                                    <div class="bs-example field-wrap">
                                                        <input type="text" id="tags" name="tags" value="<?php echo $row['tags'];?>">
                                                        <input type="hidden" id="input_company_image"
                                                            value="<?php echo $row['site_preview_image'];?>" name="company_image">
                                                        <input type="hidden" value="<?php echo $row['id'];?>" name="id">
                                                    </div>                                          
                                                </div>
                                            </div>                                    
                                        </div>
                                    
                                        <h3>3. Tech Stack</h3>
                                        <div class="form-group row">
                                            <!-- <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="framework">Framework</label>
                                                    <div class="field-wrap"> 
                                                        <select id="framework" class="form-control" name="framework[]" multiple>
                                                        <?php
                                                            /*$table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedFramework,$table_name,$col_name);*/                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>  -->    

                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="programming_language">Programming Language</label>
                                                    <div class="field-wrap">
                                                        <select id="programming_language" class="form-control" name="programming_language[]" multiple>
                                                        <?php
                                                            $table_name = "programming_language";
                                                            $col_name = "programming_language_name";                                                        
                                                            echo selected_option_caller($conn,$selectedProgrammingLanguage,$table_name,$col_name);                                        
                                                        ?> 
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="cms_plateform">CMS / Website Builder (If Applicable)</label>
                                                    <div class="field-wrap">
                                                        <select id="cms_plateform" class="form-control" name="cms_plateform[]" multiple>
                                                        <?php
                                                            $table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedCMSPlatform,$table_name,$col_name);                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="backend_tech">Backend Technologies</label>
                                                    <div class="field-wrap">
                                                        <select id="backend_tech" class="form-control" name="backend_tech[]" multiple>
                                                        <?php
                                                            $table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedBackendTech,$table_name,$col_name);                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="frontend_tech">Frontend Technologies</label>
                                                    <div class="field-wrap">
                                                        <select id="frontend_tech" class="form-control" name="frontend_tech[]" multiple>
                                                        <?php
                                                            $table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedFrontendTech,$table_name,$col_name);                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="hosting_tech">Hosting / Cloud Services</label>
                                                    <div class="field-wrap">
                                                        <select id="hosting_tech" class="form-control" name="hosting_tech[]" multiple>
                                                        <?php
                                                            $table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedHostingTech,$table_name,$col_name);                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="other_tech">Other-Technologies</label>
                                                    <div class="field-wrap"> 
                                                        <select id="other_tech" class="form-control" name="other_tech[]" multiple>
                                                        <?php
                                                            $table_name = "framework";
                                                            $col_name = "framework_name";                                                                                   
                                                            echo selected_option_caller($conn,$selectedOtherTech,$table_name,$col_name);                                        
                                                        ?>                                       
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--<div class="col-xl-4">
                                            <div class="field-wrapper">
                                                <label class="col-form-label" for="technology">Technology  <span
                                                        class="text-danger">*</span><br>(Tools and technologies used)
                                                </label>
                                                <div class="field-wrap"> 
                                                    <select id="technology" class="form-control" name="technology[]" multiple>
                                                    <?php
                                                        /*$table_name = "technology";
                                                        $col_name = "technology_name";                                                        
                                                        echo selected_option_caller($conn,$selectedTechnology,$table_name,$col_name);*/                                        
                                                    ?> 
                                                    </select>
                                                </div>
                                            </div>
                                            </div>-->

                                            <div class="col-xl-6 col-xxl-12">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="role">Role  <!--<span
                                                    class="text-danger">*</span>--><br>(e.g., "UI/UX design, front-end development, optimization for performance and SEO")
                                                    </label>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="role" name="role"
                                                            placeholder="Enter Role"><?php echo $row['role'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-xxl-12">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="features">Features  <!--<span
                                                    class="text-danger">*</span>--><br>(e.g., "Responsive design, fast load times, integrated contact form")
                                                    </label>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="features" name="features"
                                                            placeholder="Enter Features"><?php echo $row['features'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                         

                                        <h3>4. Portfolio Details</h3>
                                        <div class="form-group row">
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="portfolio_type">Portfolio Type (Website, App, etc.) <!--<span
                                                    class="text-danger">*</span>-->
                                                    </label>
                                                    <div class="field-wrap">
                                                        <select id="portfolio_type" class="form-control" name="portfolio_type[]" multiple>
                                                        <?php
                                                            $table_name = "application_type";
                                                            $col_name = "application_type_name";                                                        
                                                            echo selected_option_caller($conn,$selectedPortfolioType,$table_name,$col_name);                                        
                                                        ?>                                        
                                                        </select> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="design_url">Design URL
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input type="url" class="form-control" id="design_url" name="design_url" value="<?php echo $row['design_url'];?>" placeholder="Enter Design Url" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <!-- <label class="col-lg-12 col-form-label">Optional Sections (if relevant):</label> -->
                                                    <label class="col-form-label" for="github_repository">GitHub Repository<br>(for code, if applicable): Repository URL
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input type="url" class="form-control" id="github_repository" name="github_repository"
                                                            placeholder="Enter GitHub Repository" value="<?php echo $row['github_repository'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="demo_video">Demo Video<br>(Link to a demo or presentation)
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input type="url" class="form-control" id="demo_video" name="demo_video"
                                                            placeholder="Enter Demo Video Link" value="<?php echo $row['demo_video'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h3>5. Team & Additional Notes</h3>
                                        <div class="form-group row">
                                            <div class="col-xl-6 col-xxl-12">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="team_associates">Team Associates  <!--<span
                                                    class="text-danger">*</span>--><br>(Note: Please use ENTER key to add multiple Team Associates)
                                                    </label>
                                                    <div class="field-wrap">
                                                        <input type="text" class="form-control" id="team_associates" name="team_associates"
                                                            placeholder="Enter Team Associates" value="<?php echo $row['team_associates'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-xxl-12">
                                                <div class="field-wrapper">
                                                    <label class="col-form-label" for="comments">Comments  <!--<span
                                                    class="text-danger">*</span>--><br>(Additional notes)
                                                    </label>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="comments" name="comments"
                                                            placeholder="Enter Team Associates"><?php echo $row['comments'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="nav-other" role="tabpanel" aria-labelledby="nav-other-tab">   
                                        <h3>Other Information</h3>                                     
                                        <div class="form-group row">
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                   <div class="label_tooltip">
                                                        <label class="col-form-label" for="initial_goal">Initial goal or purpose Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/initial_goal.png" alt="Initial goal or purpose"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="initial_goal" name="initial_goal" placeholder="Enter Initial goal or purpose"><?php echo $row['initial_goal'];?></textarea>
                                                    </div>  
                                                </div>
                                            </div>    
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="pain_area">Pain Area Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/pain_area.png" alt="Pain Area"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="pain_area" name="pain_area" placeholder="Enter Pain Area"><?php echo $row['pain_area'];?></textarea>
                                                    </div>                                                   
                                                </div>
                                            </div> 
                                            <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="pain_area_image">Pain Area Image</label>                                                       
                                                    </div>
                                                    <div class="field-wrap">
                                                    <input type="text" name="pain_area_image" id="pain_area_image" class="form-control" value="<?php echo $row['pain_area_image'];?>" placeholder="Enter Pain Area Image URL">                              
                                                    </div>                                                   
                                                </div>
                                            </div> 
                                            <div class="col-xxl-12">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="development_approach">Development Approach Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/dev_approach.png" alt="Development Approach"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="development_approach" name="development_approach" placeholder="Enter Development Approach"><?php echo $row['development_approach'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>   
                                            <!-- <div class="col-xxl-6 col-xl-4">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="technologies_used">Technologies Used</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/tech_used.png" alt="Technologies Used"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="technologies_used" name="technologies_used" placeholder="Enter Technologies Used"><?php //echo $row['technologies_used']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>  --> 
                                            <div class="col-xxl-12">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="deliverables_outlined">Deliverables Outlined Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/deliv_outline.png" alt="Deliverables Outlined"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <?php 
                                                            echo '<div id="deliverablesContainer">';
                                                            if (!empty($deliverables_outlined)) {
                                                                $do = 1;
                                                                foreach ($deliverables_outlined as $key => $deliverable) {
                                                                    echo '<div class="deliverable-item">';
                                                                    echo '<textarea class="form-control deliverables-editor" id="deliverables_outlined_' . $key . '" name="deliverables_outlined[]">' . htmlspecialchars($deliverable) . '</textarea>';
                                                                    if($do != 1):
                                                                        echo '<button type="button" class="btn btn-danger removeDeliverable">x</button>';
                                                                    endif;
                                                                    echo '</div>';
                                                                    $do++;
                                                                }
                                                            } else {
                                                                // Display an empty textarea if there are no existing deliverables
                                                                echo '<div class="deliverable-item">';
                                                                echo '<textarea class="form-control deliverables-editor" id="deliverables_outlined_0" name="deliverables_outlined[]" placeholder="Enter Deliverables Outlined"></textarea>';    
                                                                echo '</div>';
                                                            }                                                          
                                                            echo '</div>';
                                                            echo '<div class="add-more">';
                                                                echo '<button type="button" id="addDeliverable" class="btn btn-primary">Add More</button>';
                                                            echo '</div>'
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-6 col-xl-6">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="challenges_faced_during_development">Challenges Faced During Development Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/chall_face.png" alt="Challenges Faced During Development"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="challenges_faced_during_development" name="challenges_faced_during_development" placeholder="Enter Challenges Faced During Development"><?php echo $row['challenges_faced_during_development'];?></textarea>
                                                    </div>
                                                </div>
                                            </div> 
                                        

                                            <div class="col-xxl-6 col-xl-6">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="solution">Solution Content</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/solution.png" alt="Solution"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <textarea class="form-control" id="solution" name="solution" placeholder="Enter Solution"><?php echo $row['solution'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="col-xxl-12">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="unique_or_interactive_elements">Unique or Interactive Elements</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/unique_element.png" alt="Unique or Interactive Elements"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap"> 
                                                        <div id="uniqueelementContainer">
                                                            <?php 
                                                            // Check if there are any existing elements
                                                            if (!empty($unique_elements_image) || !empty($unique_elements_title) || !empty($unique_elements_content)) {
                                                                $ue = 1;
                                                                foreach ($unique_elements_image as $index => $image) {
                                                                    $title = $unique_elements_title[$index] ?? ''; // Get title (if exists)
                                                                    $content = $unique_elements_content[$index] ?? ''; // Get content (if exists)
                                                            ?>
                                                                    <div class="uniqueelement-item">
                                                                        <input type="text" name="unique_elements_image[]" id="unique_elements_image_<?php echo $index; ?>" class="form-control" placeholder="Unique Element Image URL" value="<?php echo htmlspecialchars($image); ?>">
                                                                        <input type="text" name="unique_elements_title[]" id="unique_elements_title_<?php echo $index; ?>" class="form-control" placeholder="Unique Element Title" value="<?php echo htmlspecialchars($title); ?>">
                                                                        <textarea class="form-control unique_elements_content" id="unique_elements_content_<?php echo $index; ?>" name="unique_elements_content[]" placeholder="Enter Unique Element Content"><?php echo htmlspecialchars($content); ?></textarea>
                                                                        <?php if ($ue != 1): ?>
                                                                            <button type="button" class="btn btn-danger removeuniqueelement">x</button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                            <?php
                                                                    $ue++;
                                                                }
                                                            } else {
                                                            ?>
                                                                <div class="uniqueelement-item">
                                                                    <input type="text" name="unique_elements_image[]" id="unique_elements_image_0" class="form-control" placeholder="Unique Element Image URL">
                                                                    <input type="text" name="unique_elements_title[]" id="unique_elements_title_0" class="form-control" placeholder="Unique Element Title">
                                                                    <textarea class="form-control unique_elements_content" id="unique_elements_content_0" name="unique_elements_content[]" placeholder="Enter Unique Element Content"></textarea>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="add-more">
                                                            <button type="button" id="adduniqueelement" class="btn btn-primary">Add More</button>
                                                        </div>
                                                    </div>                                                 
                                                </div>
                                            </div>  

                                            <div class="col-xxl-12">
                                                <div class="field-wrapper">
                                                    <div class="label_tooltip">
                                                        <label class="col-form-label" for="success_story_list">Success Story List</label>
                                                        <div class="tooltip">
                                                            <p class="tooltip-icon">!</p>
                                                            <span class="tooltiptext"><img src="./images/tooltip_img/success-story.png" alt="Success story"></span>
                                                        </div>
                                                    </div>
                                                    <div class="field-wrap">
                                                        <div id="successContainer">
                                                            <?php 
                                                            // Check if there are any elements and loop through them
                                                            if ( !empty($success_image) || !empty($success_number) || !empty($success_subtitle) ) {
                                                                $s_story = 1;
                                                                foreach ($success_subtitle as $index => $subtitled) {
                                                                    $sucimg = $success_image[$index] ?? ''; // Get title (if exists)
                                                                    $sucnumber = $success_number[$index] ?? ''; // Get content (if exists)
                                                            ?>
                                                                    <div class="success-item">
                                                                        <input type="text" name="success_image[]" class="form-control" placeholder="Success Image URL" value="<?php echo htmlspecialchars($sucimg); ?>">
                                                                        <input type="text" name="success_number[]" class="form-control" placeholder="Enter Success Number" value="<?php echo htmlspecialchars($sucnumber); ?>">
                                                                        <input type="text" name="success_subtitle[]" id="success_subtitle"  class="form-control" placeholder="Success Sub Title" value="<?php echo htmlspecialchars($subtitled); ?>">
                                                                        <?php if( $s_story != 1 ): ?>
                                                                        <button type="button" class="btn btn-danger removeSucessStory">x</button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                            <?php
                                                                $s_story++; }
                                                            } else {
                                                            ?>
                                                                <div class="success-item">
                                                                    <input type="text" name="success_image[]" class="form-control" placeholder="Success Image URL">
                                                                    <input type="text" name="success_number[]" class="form-control" placeholder="Enter Success Number">
                                                                    <input type="text" name="success_subtitle[]" id="success_subtitle" class="form-control" placeholder="Success Sub Title">
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="add-more mb-3">
                                                            <button type="button" id="addsucess" class="btn btn-primary">Add More</button>
                                                        </div>
                                                        <label class="col-form-label" for="success_story">Success Story Content</label>
                                                        <textarea class="form-control" id="success_story" name="success_story" placeholder="Enter Success story"><?php echo $row['success_story'];?></textarea>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                                            
                                    <div class="form-group row">
                                        <div class="col-xl-8 m-auto text-center">
                                            <button type="submit" name="update_portfolio" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <!-- #/ container -->
</div>
<?php
include_once('footer.php');
?>
<script src="./js/countries.js"></script>
<!-- <script src="./plugins/validation/jquery.validate.min.js"></script>
<script src="./plugins/validation/jquery.validate-init.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script>
    //$("#tags").tagsinput('items');
    //$("#framework").tagsinput('items');
    //$("#portfolio_type").tagsinput('items');
    //$("#team_associates").tagsinput('items');
    //$("#niche").tagsinput('items');

    function preview_images() {
        var total_file = document.getElementById("images").files.length;
        for (var i = 0; i < total_file; i++) {
            $('#image_preview').append(`
                <div class='col-md-3'>
                    <img style='width:100%' class='img-responsive' src='${URL.createObjectURL(event.target.files[i])}'>
                </div>`);
        }
    }

    function resetForm() {
        $("#image_preview").html("");
        return true;
    }
</script>

<script>
    const countries = window.countries;
    $(document).ready(function() {
        const tex = window.tex;
        // Function to initialize the editor
        function initTexEditor(element) {
            // Check if the editor is already initialized
            if (element.dataset.editorInitialized === "true") {
                return; // Avoid double initialization
            }

            tex.init({
                element: element,
                buttons: [
                    'fontSize', 'bold', 'italic', 'underline', 'strikethrough', 'heading1', 'heading2',
                    'paragraph', 'removeFormat', 'quote', 'olist', 'ulist', 'code', 'line', 'link',
                    'image', 'html', 'textColor', 'textBackColor', 'indent', 'outdent', 'undo', 'redo',
                    'justifyCenter', 'justifyFull', 'justifyLeft', 'justifyRight'
                ],
                onChange: (content) => {
                    console.log(`Editor for ${element.id}:`, content);
                }
            });

            // Mark the textarea as initialized
            element.dataset.editorInitialized = "true";
        }

        // Initialize editors for existing textareas on page load
        document.querySelectorAll(".deliverables-editor, #about_business, #role, #features, #comments, #initial_goal, #pain_area, #development_approach, #challenges_faced_during_development, .unique_elements_content, #success_story, #solution")
            .forEach(initTexEditor);

        // Observe changes in dynamically added elements
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Ensure it's an element node
                        const textareas = node.querySelectorAll("textarea[name='deliverables_outlined[]'], textarea[name='unique_elements_content[]']");
                        textareas.forEach(initTexEditor); // Initialize the editor for each new textarea
                    }
                });
            });
        });

        // Attach the observer to both containers
        const deliverablesContainer = document.getElementById("deliverablesContainer");
        const uniqueElementContainer = document.getElementById("uniqueelementContainer");

        if (deliverablesContainer) {
            observer.observe(deliverablesContainer, { childList: true, subtree: true });
        }
        if (uniqueElementContainer) {
            observer.observe(uniqueElementContainer, { childList: true, subtree: true });
        }

        // Event listener for adding new "Deliverables Outlined" textarea
        document.getElementById("addDeliverable").addEventListener("click", () => {
            if (deliverablesContainer) {
                const newElement = document.createElement("div");
                newElement.classList.add("deliverable-item");

                // Generate unique ID
                const uniqueId = "deliverables_outlined_" + document.querySelectorAll(".deliverables-editor").length;

                newElement.innerHTML = `
                    <textarea class="form-control deliverables-editor" id="${uniqueId}" name="deliverables_outlined[]" placeholder="Enter Deliverables Outlined"></textarea>
                    <button type="button" class="btn btn-danger removeDeliverable">x</button>
                `;
                deliverablesContainer.appendChild(newElement);

                // Initialize editor for newly added textarea
                initTexEditor(newElement.querySelector("textarea"));
            }
        });

        // Event listener for adding new "Unique or Interactive Elements"
        document.getElementById("adduniqueelement").addEventListener("click", () => {
            if (uniqueElementContainer) {
                const newElement = document.createElement("div");
                newElement.classList.add("uniqueelement-item");

                newElement.innerHTML = `
                    <input type="text" name="unique_elements_image[]" class="form-control" placeholder="Unique Element Image URL">
                    <input type="text" name="unique_elements_title[]" class="form-control" placeholder="Unique Element Title">
                    <textarea class="form-control unique_elements_content" name="unique_elements_content[]" placeholder="Enter Unique Element Content"></textarea>
                    <button type="button" class="btn btn-danger removeuniqueelement">x</button>
                `;
                uniqueElementContainer.appendChild(newElement);

                // Initialize editor for newly added textarea
                initTexEditor(newElement.querySelector("textarea"));
            }
        });


        $("#addsucess").click(function() {
            $("#successContainer").append(`
                <div class="success-item">
                    <input type="text" name="success_image[]" id="success_image"  class="form-control" placeholder="Success Image URL">
                    <input type="text" name="success_number[]" id="success_number"  class="form-control" placeholder="Success Number">
                    <input type="text" name="success_subtitle[]" id="success_subtitle"  class="form-control" placeholder="Success Sub Title">
                    <button type="button" class="btn btn-danger removeSucessStory">x</button>
                </div>
            `);
        });

        // Remove textarea when clicking remove button
        $(document).on("click", ".removeDeliverable", function() {
            $(this).parent(".deliverable-item").remove();
        });

        $(document).on("click", ".removeuniqueelement", function () {
            $(this).closest(".uniqueelement-item").remove();
        });

        $(document).on("click", ".removeSucessStory", function() {
            $(this).parent(".success-item").remove();
        });

        $("#niche").tagsinput(); // Initialize tags input  
        $("#tags").tagsinput(); // Initialize tags input 
        $("#team_associates").tagsinput(); // Initialize tags input        

        function initializeTypeahead(inputSelector, dataUrl) {
            $.ajax({
                url: "ajax_functions.php?action=" + dataUrl,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    console.log("Fetched Data for " + dataUrl + ":", data); // Debugging

                    // Remove duplicate text values from fetched data
                    let uniqueTags = [];
                    let seenTexts = new Set();

                    data.forEach(tag => {
                        if (!seenTexts.has(tag.text)) {
                            seenTexts.add(tag.text);
                            uniqueTags.push(tag);
                        }
                    });

                    // Initialize Bloodhound suggestion engine
                    var tagSuggestions = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        local: uniqueTags // Use only unique tags
                    });

                    // Attach Typeahead ONLY to the specific input field
                    $(inputSelector).tagsinput('input').typeahead(
                        {
                            minLength: 1,
                            highlight: true,
                            hint: true,
                            delay: 200
                        },
                        {
                            name: dataUrl,
                            displayKey: "text",
                            source: tagSuggestions
                        }
                    ).on("typeahead:selected", function (event, suggestion) {
                        // Add selected tag if not already added
                        let fieldId = $(this).closest('.bootstrap-tagsinput').prev().attr('id');
                        if ($("#" + fieldId).tagsinput("items").indexOf(suggestion.text) === -1) {
                            $("#" + fieldId).tagsinput("add", suggestion.text);
                        }
                        $(this).typeahead("val", ""); // Clear input after adding
                    });

                },
                error: function () {
                    console.error("Error fetching data for " + dataUrl);
                }
            });
        }
        // Initialize Typeahead for each field separately
        initializeTypeahead("#niche", "fetch_niche_tags");
        initializeTypeahead("#tags", "fetch_tags");
        initializeTypeahead("#team_associates", "team_associates");
       
        let selectedCountries = $("#countrySelect").data('selected') || [];

        // Normalize selected countries by removing spaces after commas
        selectedCountries = selectedCountries.map(c => c.replace(/,\s*/g, ',')); // Convert "TX, USA" → "TX,USA"

        $("#countrySelect").select2({          
            multiple: true,  
            data: countries.map(function(country) {
                let normalizedCountry = country.replace(/,\s*/g, ','); // Convert "TX, USA" → "TX,USA"
                return {
                    id: normalizedCountry,
                    text: country, // Keep original display text
                    selected: selectedCountries.includes(normalizedCountry) // Compare normalized values
                };
            }),
            placeholder: "Select a country",
            allowClear: true
        });

        // Set selected values after normalizing
        $("#countrySelect").val(selectedCountries).trigger("change");
           
        $("#website_url").change(function() {
            var website = $(this).val();
            $('#website_url').after();
            $.ajax({
                url: "ajax_functions.php",
                type: "POST",
                dataType: "json",
                data: {
                    website: website
                },
                beforeSend: function() {
                    $('.web_loader').show();
                },
                success: function(response) {
                    $("#company_name").val(response.company_name);
                    $("#company_image").attr('src', response.company_image);
                    $("#input_company_image").val(response.company_image);
                    $("#company_image").css("display", "block");
                },
                complete: function() {
                    $('.web_loader').hide();
                }
            });
        });

        $("#industries").select2({
            placeholder: "Select Industries",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "industry_field"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.industrie_name 
                            };
                        })
                    };
                }
            }
        });

        /*$("#framework").select2({
            placeholder: "Select Framework",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        });*/  
        
        $("#cms_plateform").select2({
            placeholder: "Select CMS / Website Builder",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field",
                        query_type_two : "cms"
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        }); 
        
        $("#backend_tech").select2({
            placeholder: "Select Backend Technologies",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field",
                        query_type_two : "backend"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        }); 
        
        $("#frontend_tech").select2({
            placeholder: "Select Frontend Technologies",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field",
                        query_type_two : "frontend"   
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        }); 
        
        $("#hosting_tech").select2({
            placeholder: "Select Hosting / Cloud Services",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field",
                        query_type_two : "hosting"
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        });
        
        $("#other_tech").select2({
            placeholder: "Select Other-Technologies",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "framework_field",
                        query_type_two : "other"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.framework_name 
                            };
                        })
                    };
                }
            }
        });  

        $("#programming_language").select2({
            placeholder: "Select Programming Language",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "programming_language_field"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.programming_language_name 
                            };
                        })
                    };
                }
            }
        });  

        $("#technology").select2({
            placeholder: "Select Technology",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "technology_field"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.technology_name 
                            };
                        })
                    };
                }
            }
        });
        
        $("#portfolio_type").select2({
            placeholder: "Select Portfolio Type",
            multiple: true,             
            ajax: {
                url: "ajax_functions.php",
                type: "GET",
                dataType: "json",
                delay: 250, 
                data: function(params) {                 
                    return {
                        query: params.term || '',
                        query_type : "portfolio_type_field"  
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.application_type_name 
                            };
                        })
                    };
                }
            }
        });  
    });
</script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(function() {
        var availableTags = [
            "IT",
            "Agriculture",
            "Chemical",
            "Construction",
            "Education",
            "Food",
            "Health",
        ];
        // $("#industries").autocomplete({
        //     source: availableTags
        // });
    });
</script>