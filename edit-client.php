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
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
<style>
    .bootstrap-select .dropdown-toggle .filter-option {
        background-color: #fff;
        border: 1px solid #ced4da;
    }

    .tags-input-wrapper .tag a {
        margin: 0 7px 3px;
        display: inline-block;
        cursor: pointer;
    }

    .tags-input-wrapper .tag {
        display: inline-block;
        background-color: #7571f9;
        color: white;
        border-radius: 40px;
        padding: 0px 3px 0px 7px;
        margin-right: 5px;
        margin-bottom: 5px;
        box-shadow: 0 5px 15px -2px rgb(117, 113, 249)
    }

    .tags-input-wrapper input {
        border: none;
        background: transparent;
        outline: none;
        width: 140px;
        margin-left: 8px;
    }

    .tags-input-wrapper {
        background: transparent;
        padding: 10px;
        border-radius: 4px;
        max-width: 400px;
        border: 1px solid #ccc
    }

    .company_img {
        width: 100%;
        height: 100px;
        display: none;
    }

    .autocomplete {
        position: relative;
        display: inline-block;
    }


    .autocomplete-items {
        position: absolute;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        z-index: 99;
    }

    .autocomplete-item {
        padding: 10px;
        cursor: pointer;
    }

    .autocomplete-item:hover {
        background-color: #e0e0e0;
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
    <!-- row -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <form class="form-valide" action="" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="website_url">Website URL<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="website_url" name="website_url"
                                            value="<?php echo $row['website_url'];?>" placeholder="Enter Website url"
                                            autofocus>
                                        <div class="web_loader" style="display: none;">
                                            <svg class="circular" viewBox="25 25 50 50">
                                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                                                    stroke-miterlimit="10" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="image_preview"><img src="<?php echo $row['site_preview_image'];?>"
                                            id="company_image" class="company_img" style="display:block;"></div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="country">Country<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select id="countrySelect" class="select2 form-control" name="country"
                                            data-selected="<?php echo $row['country'];?>">
                                            <option value="">Select a country</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="company_name">Company Name <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="company_name"
                                            value="<?php echo $row['company_name'];?>" name="company_name"
                                            placeholder="Enter Company Name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="industries">Industries <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <div class="autocomplete">
                                            <input type="text" class="form-control" id="industries"
                                                value="<?php echo $row['industries'];?>" name="industries"
                                                placeholder="Enter Industry Name">
                                            <div class="autocomplete-items" id="industryList"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="framework">Framework<br>(Note: Please
                                        use ENTER key to add multiple tags)

                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" id="framework" name="framework"
                                            value="<?php echo $row['framework'];?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="current_status">Current status
                                    </label>
                                    <div class="col-lg-6">
                                        <select class="form-control" id="current_status" name="current_status">
                                            <option value="">Please select</option>
                                            <option value="working"
                                                <?php echo ($row['current_status'] == 'working')?'selected':'';?>>
                                                Working</option>
                                            <option value="not-working"
                                                <?php echo ($row['current_status'] == 'not-working')?'selected':'';?>>
                                                Not-working</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="tags">Tags<br>(Note: Please use ENTER
                                        key to add multiple tags)
                                    </label>
                                    <div class="col-lg-6">
                                        <div class="bs-example">
                                            <input type="text" id="tags" name="tags" value="<?php echo $row['tags'];?>">
                                            <input type="hidden" id="input_company_image"
                                                value="<?php echo $row['site_preview_image'];?>" name="company_image">
                                            <input type="hidden" value="<?php echo $row['id'];?>" name="id">
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="design_url">Design URL
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="url" class="form-control" id="design_url" name="design_url" value="<?php echo $row['design_url'];?>" placeholder="Enter Design Url" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="portfolio_type">Portfolio Type (Website, App, etc.) <span
                                            class="text-danger">*</span><br>(Note: Please use ENTER key to add multiple Portfolios)
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="portfolio_type" name="portfolio_type"
                                            placeholder="Enter Portfolio Type" value="<?php echo $row['portfolio_type'];?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="about_business">About Business  <span
                                            class="text-danger">*</span><br>(Brief description of the company)
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="about_business" name="about_business"
                                            placeholder="Enter About Business"><?php echo $row['about_business'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="niche">Niche  <span
                                            class="text-danger">*</span><br>(Subcategory of industry)
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="niche" name="niche"
                                            placeholder="Enter Niche" ><?php echo $row['niche'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="technology">Technology  <span
                                            class="text-danger">*</span><br>(Tools and technologies used)
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="technology" name="technology"
                                            placeholder="Enter technology"><?php echo $row['technology'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="role">Role  <span
                                            class="text-danger">*</span><br>(e.g., "UI/UX design, front-end development, optimization for performance and SEO")
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="role" name="role"
                                            placeholder="Enter Role"><?php echo $row['role'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="features">Features  <span
                                            class="text-danger">*</span><br>(e.g., "Responsive design, fast load times, integrated contact form")
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="features" name="features"
                                            placeholder="Enter Features"><?php echo $row['features'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="team_associates">Team Associates  <span
                                            class="text-danger">*</span><br>(Note: Please use ENTER key to add multiple Team Associates)
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="team_associates" name="team_associates"
                                            placeholder="Enter Team Associates" value="<?php echo $row['team_associates'];?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="comments">Comments  <span
                                            class="text-danger">*</span><br>(Additional notes)
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="comments" name="comments"
                                            placeholder="Enter Team Associates"><?php echo $row['comments'];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-12 col-form-label">Optional Sections (if relevant):
                                    </label>
                                    <label class="col-lg-4 col-form-label" for="github_repository">GitHub Repository<br>(for code, if applicable): Repository URL
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="url" class="form-control" id="github_repository" name="github_repository"
                                            placeholder="Enter GitHub Repository" value="<?php echo $row['github_repository'];?>">
                                    </div>
                                    <label class="col-lg-4 col-form-label" for="demo_video">Demo Video<br>(Link to a demo or presentation)
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="url" class="form-control" id="demo_video" name="demo_video"
                                            placeholder="Enter Demo Video Link" value="<?php echo $row['demo_video'];?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8 ml-auto">
                                        <button type="submit" name="update_portfolio"
                                            class="btn btn-primary">Submit</button>
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
    $("#tags").tagsinput('items');
    $("#framework").tagsinput('items');
    $("#portfolio_type").tagsinput('items');
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
        const countries = [
            "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina",
            "Armenia", "Australia",
            "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium",
            "Belize", "Benin",
            "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
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
        const selectedCountry = $("#countrySelect").data('selected');
        $("#countrySelect").select2({
            data: countries.map(function(country) {
                return {
                    id: country,
                    text: country,
                    selected: country ===
                        selectedCountry // Mark the option as selected if it matches the data-selected attribute
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
        $("#industries").autocomplete({
            source: availableTags
        });
    });
</script>