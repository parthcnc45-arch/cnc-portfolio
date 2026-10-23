<?php
include_once('header.php');
include_once('sidebar.php');?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/assets/app.css">
<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">

<style>
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
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .bootstrap-tagsinput {display: block; width: 100%; min-height: 43px; padding: 0.375rem 0.75rem; font-size: 0.875rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; overflow: hidden;border-radius: 0.25rem;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection ul.select2-selection__rendered{padding: 0 5px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2.select2-container .selection .select2-selection .select2-selection__rendered li input{margin: 0;padding: 0 12px;font-size: 0;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap textarea {min-height: 90px;height: 100%;padding: 10px 12px;}
    .form-validation .form-valide .form-group button{padding: 15px;min-width:170px;font-size: 20px; font-weight: 500;}
    #current_status{-webkit-appearance: none; appearance: none;}
    #select2-selection__arrow{height: 26px; position: absolute; top: 9px; right: 9px; width: 20px;}
    #select2-selection__arrow b{    border-color: #888 transparent transparent transparent; border-style: solid; border-width: 5px 4px 0 4px; height: 0; left: 50%; margin-left: -4px; margin-top: -2px; position: absolute; top: 50%; width: 0;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap{position: relative;}
    #role{padding: 0.375rem 0.75rem;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .bootstrap-tagsinput .twitter-typeahead{line-height:2;}
    .form-control{border-radius: 0.25rem;}
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #7571f9;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__rendered li.select2-selection__choice {min-width: 60px;font-size: 14px;margin: 8px 5px; background: #7571f9; color: #fff;position: relative;padding: 0px 30px 0px 5px;}
    .form-validation .form-valide .form-group .field-wrapper .field-wrap .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {position: absolute;right: 10px;color: #fff;}
    @media (max-width:1700px) {
        .form-validation .form-valide .form-group .field-wrapper .col-form-label{font-size:13px;font-weight:500;}
        .form-validation .form-valide .form-group .field-wrapper.field-radio .col-form-label{font-size: 16px;}
        .form-validation .form-valide .form-group .field-wrapper.field-radio  .form-check  .form-check-label{font-weight:500;}
    }
    @media (max-width:991px) {
        .form-validation .form-valide h3 {font-size: 22px;}
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
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <form class="form-valide" action="" method="post" enctype="multipart/form-data">
                                <h3>1. Basic Information</h3>
                                <div class="form-group row">
                                    <div class="col-xl-6">
                                        <div class="field-wrapper field-radio">
                                            <label class="col-form-label" for="type">Type</label>
                                            <div class="field-wrap">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type" id="typePortfolio" value="Portfolio" checked>
                                                    <label class="form-check-label" for="typePortfolio">Portfolio</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type" id="typeCaseStudy" value="Case Study">
                                                    <label class="form-check-label" for="typeCaseStudy">Case Study</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="website_url">Website URL<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="field-wrap">
                                                <input type="text" class="form-control" id="website_url" name="website_url"
                                                    placeholder="Enter Website url" autofocus>
                                                <div class="web_loader" style="display: none;">
                                                    <svg class="circular" viewBox="25 25 50 50">
                                                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                                                            stroke-miterlimit="10" />
                                                    </svg>
                                                </div>
                                                <div class="image_preview"><img src="" id="company_image" class="company_img"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="company_name">Company Name <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="field-wrap">
                                                <input type="text" class="form-control" id="company_name" name="company_name"
                                                    placeholder="Enter Company Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="country">Country</label>
                                            <div class="field-wrap">
                                                <!-- <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country Name" > -->
                                                <select id="countrySelect" class="select2 form-control" name="country" >
                                                    <option value="" selected>Select a country</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="industries">Industries <span class="text-danger">*</span></label>
                                            <div class="field-wrap"> 
                                                <select id="industries" class="form-control" name="industries[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" class="form-control" for="niche">Niche  <span class="text-danger">*</span> (Subcategory of industry) </label>
                                            <div class="field-wrap">
                                                <input type="text" id="niche" name="niche" data-role="tagsinput">                                        
                                            </div>
                                         </div>    
                                    </div>
                                </div>
                                <h3>2. Business Overview</h3>
                                <div class="form-group row">
                                    <div class="col-xxl-6 col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="about_business">About Business  <span class="text-danger">*</span>(Brief description of the company) </label>
                                            <div class="field-wrap">
                                                <textarea class="form-control" id="about_business" name="about_business" placeholder="Enter About Business"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="current_status">Current status </label>
                                            <div class="field-wrap">
                                                <select class="form-control" id="current_status" name="current_status">
                                                    <option value="">Please select</option>
                                                    <option value="working">Working</option>
                                                    <option value="not-working">Not-working</option>
                                                </select>
                                                <span id="select2-selection__arrow" class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="tags">Tags (Note: Please use ENTER key to add multiple tags) </label>
                                            <div class="bs-example field-wrap">
                                                <input type="text" id="tags" name="tags">
                                                <input type="hidden" id="input_company_image" name="company_image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>3. Technical Details</h3>
                                <div class="form-group row">
                                    <!-- <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-xl-4 col-form-label" for="framework">Framework</label>
                                            <div class="field-wrap">                                        
                                                <select id="framework" class="form-control" name="framework[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="programming_language">Programming Language</label>
                                            <div class="field-wrap">                                        
                                                <select id="programming_language" class="form-control" name="programming_language[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="cmc_plateform">CMS / Website Builder (If Applicable)</label>
                                            <div class="field-wrap">                                        
                                                <select id="cmc_plateform" class="form-control" name="cmc_plateform[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="backend_tech">Backend Technologies</label>
                                            <div class="field-wrap">                                        
                                                <select id="backend_tech" class="form-control" name="backend_tech[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="frontend_tech">Frontend Technologies</label>
                                            <div class="field-wrap">                                        
                                                <select id="frontend_tech" class="form-control" name="frontend_tech[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="hosting_tech">Hosting / Cloud Services</label>
                                            <div class="field-wrap">                                        
                                                <select id="hosting_tech" class="form-control" name="hosting_tech[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="other_tech">Other Technologies</label>
                                            <div class="field-wrap">                                        
                                                <select id="other_tech" class="form-control" name="other_tech[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-xl-4">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="technology ">Technology  <span class="text-danger">*</span>(Tools and technologies used) </label>
                                            <div class="field-wrap">
                                                <select id="technology" class="form-control" name="technology[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-xl-6 col-xxl-12">
                                        <div class="field-wrapper">
                                           <label class="col-form-label" for="role">Role  <span class="text-danger">*</span> (e.g., "UI/UX design, front-end development, optimization for performance and SEO") </label>
                                            <div class="field-wrap">
                                                <textarea class="form-control" id="role" name="role"
                                                    placeholder="Enter Role"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-12">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="features">Features  <span class="text-danger">*</span> (e.g., "Responsive design, fast load times, integrated contact form") </label>
                                            <div class="field-wrap">
                                                <textarea class="form-control" id="features" name="features"
                                                    placeholder="Enter Features"></textarea>
                                            </div>       
                                        </div>
                                    </div>
                                </div>
                                <h3>4. Portfolio Details</h3>
                                <div class="form-group row">
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="portfolio_type">Portfolio Type (Website, App, etc.) <span class="text-danger">*</span> </label>
                                            <div class="field-wrap">                                       
                                                <select id="portfolio_type" class="form-control" name="portfolio_type[]" multiple></select>   
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="design_url">Design URL </label>
                                            <div class="field-wrap">
                                                <input type="url" class="form-control" id="design_url" name="design_url" placeholder="Enter Design Url" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <!-- <label class="col-form-label">Optional Sections (if relevant): </label> -->
                                            <label class="col-form-label" for="github_repository">GitHub Repository (for code, if applicable): Repository URL </label>
                                            <div class="field-wrap">
                                                <input type="url" class="form-control" id="github_repository" name="github_repository"
                                                    placeholder="Enter GitHub Repository">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="demo_video">Demo Video (Link to a demo or presentation) </label>
                                            <div class="field-wrap">
                                                <input type="url" class="form-control" id="demo_video" name="demo_video"
                                                    placeholder="Enter Demo Video Link">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>5. Team & Additional Notes</h3>
                                <div class="form-group row">
                                    <div class="col-xl-6 col-xxl-12">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="team_associates">Team Associates <span class="text-danger">*</span> (Note: Please use ENTER key to add multiple Team Associates) </label>
                                            <div class="field-wrap">
                                                <input type="text" class="form-control" id="team_associates" name="team_associates"
                                                    placeholder="Enter Team Associates">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-12">
                                        <div class="field-wrapper">
                                            <label class="col-form-label" for="comments">Comments  <span class="text-danger">*</span> (Additional notes) </label>
                                            <div class="field-wrap">
                                                <textarea class="form-control" id="comments" name="comments"
                                                    placeholder="Enter Comments"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <!-- <div class="form-group row">
                                    <label class="col-xl-6 col-form-label" for="type">Type</label>
                                    <div class="col-xl-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="typePortfolio" value="Portfolio" checked>
                                            <label class="form-check-label" for="typePortfolio">Portfolio</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="typeCaseStudy" value="Case Study">
                                            <label class="form-check-label" for="typeCaseStudy">Case Study</label>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="country">Country
                                    </label>
                                    <div class="col-xl-6">
                                        <select id="countrySelect" class="select2 form-control" name="country" >
                                            <option value="" selected>Select a country</option>
                                        </select>
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="company_name">Company Name <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                            placeholder="Enter Company Name">
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="industries">Industries <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-xl-6"> 
                                        <select id="industries" class="form-control" name="industries[]" multiple></select>   
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="framework">Framework</label>
                                    <div class="col-xl-6">                                        
                                        <select id="framework" class="form-control" name="framework[]" multiple></select>   
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="programming_language">Programming Language</label>
                                    <div class="col-xl-6">                                        
                                        <select id="programming_language" class="form-control" name="programming_language[]" multiple></select>   
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="current_status">Current status
                                    </label>
                                    <div class="col-xl-6">
                                        <select class="form-control" id="current_status" name="current_status">
                                            <option value="">Please select</option>
                                            <option value="working">Working</option>
                                            <option value="not-working">Not-working</option>
                                        </select>
                                    </div>
                                </div> -->

                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="tags">Tags<br>(Note: Please use ENTER
                                        key to add multiple tags)
                                    </label>
                                    <div class="col-xl-6">
                                        <div class="bs-example">
                                            <input type="text" id="tags" name="tags">
                                            <input type="hidden" id="input_company_image" name="company_image">
                                        </div>

                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="design_url">Design URL
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="url" class="form-control" id="design_url" name="design_url" placeholder="Enter Design Url" >
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="portfolio_type">Portfolio Type (Website, App, etc.) <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-xl-6">                                       
                                        <select id="portfolio_type" class="form-control" name="portfolio_type[]" multiple></select>   
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="about_business">About Business  <span
                                    class="text-danger">*</span><br>(Brief description of the company)
                                    </label>
                                    <div class="col-xl-6">
                                        <textarea class="form-control" id="about_business" name="about_business"
                                            placeholder="Enter About Business"></textarea>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="niche">Niche  <span
                                    class="text-danger">*</span><br>(Subcategory of industry)
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="text" id="niche" name="niche" data-role="tagsinput">                                        
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="technology ">Technology  <span
                                    class="text-danger">*</span><br>(Tools and technologies used)
                                    </label>
                                    <div class="col-xl-6">
                                        <select id="technology" class="form-control" name="technology[]" multiple></select>   
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="role">Role  <span
                                    class="text-danger">*</span><br>(e.g., "UI/UX design, front-end development, optimization for performance and SEO")
                                    </label>
                                    <div class="col-xl-6">
                                        <textarea class="form-control" id="role" name="role"
                                            placeholder="Enter Role"></textarea>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="features">Features  <span
                                    class="text-danger">*</span><br>(e.g., "Responsive design, fast load times, integrated contact form")
                                    </label>
                                    <div class="col-xl-6">
                                        <textarea class="form-control" id="features" name="features"
                                            placeholder="Enter Features"></textarea>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="team_associates">Team Associates  <span
                                    class="text-danger">*</span><br>(Note: Please use ENTER key to add multiple Team Associates)
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="text" class="form-control" id="team_associates" name="team_associates"
                                            placeholder="Enter Team Associates">
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="comments">Comments  <span
                                    class="text-danger">*</span><br>(Additional notes)
                                    </label>
                                    <div class="col-xl-6">
                                        <textarea class="form-control" id="comments" name="comments"
                                            placeholder="Enter Comments"></textarea>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-12 col-form-label">Optional Sections (if relevant):
                                    </label>
                                    <label class="col-xl-4 col-form-label" for="github_repository">GitHub Repository<br>(for code, if applicable): Repository URL
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="url" class="form-control" id="github_repository" name="github_repository"
                                            placeholder="Enter GitHub Repository">
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-xl-4 col-form-label" for="demo_video">Demo Video<br>(Link to a demo or presentation)
                                    </label>
                                    <div class="col-xl-6">
                                        <input type="url" class="form-control" id="demo_video" name="demo_video"
                                            placeholder="Enter Demo Video Link">
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <div class="col-xl-8 m-auto text-center">
                                        <button type="submit" name="insert_portfolio" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
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
<script src="./plugins/validation/jquery.validate.min.js"></script>
<script src="./plugins/validation/jquery.validate-init.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script>      
   // $("#niche").tagsinput('items'); 
    $("#tags").tagsinput('items');
    //$("#framework").tagsinput('items');
    //$("#portfolio_type").tagsinput('items');
    $("#team_associates").tagsinput('items');

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
    $(document).ready(function() {
        $("#niche").tagsinput(); // Initialize tags input            
        $.ajax({
            url: "ajax_functions.php?action=fetch_niche_tags",
            type: "GET",
            dataType: "json",
            success: function (data) {
                console.log("Fetched Niche Tags:", data); // Debugging

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
                var nicheTags = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace("text"),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: uniqueTags // Use only unique tags
                });

                // Initialize Typeahead with Bloodhound
                $(".bootstrap-tagsinput input").typeahead(
                    {
                        minLength: 1,
                        highlight: true,
                        hint: true,
                        delay: 200
                    },
                    {
                        name: "nicheTags",
                        displayKey: "text",
                        source: nicheTags
                    }
                ).on("typeahead:selected", function (event, suggestion) {
                    // Add selected tag if not already added
                    if ($("#niche").tagsinput("items").indexOf(suggestion.text) === -1) {
                        $("#niche").tagsinput("add", suggestion.text);
                    }
                    $(this).typeahead("val", ""); // Clear input after adding
                });
            },
            error: function () {
                console.error("Error fetching niche tags.");
            }
        });
   
        const countries = [
            "Afghanistan", "Albania", "Axleria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina",
            "Armenia", "Australia",
            "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Bexlium",
            "Belize", "Benin",
            "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Buxlaria",
            "Burkina Faso", "Burundi",
            "Côte d'Ivoire", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic",
            "Chad", "Chile", "China",
            "Colombia", "Comoros", "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus",
            "Czechia (Czech Republic)",
            "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
            "Ecuador", "Egypt",
            "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini (fmr. 'Swaziland')",
            "Ethiopia", "Fiji", "Finland",
            "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala",
            "Guinea", "Guinea-Bissau",
            "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran",
            "Iraq", "Ireland",
            "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kuwait",
            "Kyrgyzstan",
            "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania",
            "Luxembourg", "Madagascar",
            "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
            "Mauritius", "Mexico", "Micronesia",
            "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
            "Myanmar (formerly Burma)", "Namibia", "Nauru",
            "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea",
            "North Macedonia", "Norway",
            "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay",
            "Peru", "Philippines",
            "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe",
            "Saudi Arabia", "Senegal", "Serbia",
            "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia",
            "South Africa",
            "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden",
            "Switzerland", "Syria", "Tajikistan",
            "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia",
            "Turkey", "Turkmenistan",
            "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom",
            "United States of America", "Uruguay",
            "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
        ];

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

        $("#framework").select2({
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
        $("#cmc_plateform").select2({
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
        }); 
        $("#other_tech").select2({
            placeholder: "Select Other Technologies",
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
                
        $("#countrySelect").select2({
            data: countries.map(function(country) {
                return {
                    id: country,
                    text: country
                };
            }),
            placeholder: "Select a country",
            allowClear: true,
        });
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