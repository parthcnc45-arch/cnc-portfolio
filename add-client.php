<?php
include_once('header.php');
include_once('sidebar.php');
$row = [];
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
            <h3>Add Client</h3>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Client</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <form class="form-valide" action="" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="client_name">Client Name<span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Enter Client Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="email_address">Email Address
                                        </label>
                                        <div class="form-control-group">
                                            <input type="email" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="phone_number">Phone Number
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Enter Phone Number">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="city">City
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="city" name="city" class="form-control" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="state">State
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="state" name="state" class="form-control" placeholder="Enter state">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="country">Country
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="countrys" name="countrys" class="form-control" placeholder="Enter Country">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="client_type">Client Type
                                        </label>
                                        <div class="form-control-group">
                                            <select id="client_type" name="client_type" class="form-control">
                                                <option value="B2B">B2B</option>
                                                <option value="B2C">B2C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="company_name">Company Name
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="company_website">Company Website
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="company_website" name="company_website" class="form-control" placeholder="Enter Company Website">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="industry">Industry
                                        </label>
                                        <div class="form-control-group">
                                            <select id="industry" name="industry" class="select2 form-control">
                                                <option value="Agriculture, Forestry, Fishing, and Hunting">Agriculture, Forestry, Fishing, and Hunting</option>
                                                <option value="Mining, Quarrying, and Oil & Gas Extraction">Mining, Quarrying, and Oil & Gas Extraction</option>
                                                <option value="Construction">Construction</option>
                                                <option value="Manufacturing">Manufacturing</option>
                                                <option value="Wholesale Trade">Wholesale Trade</option>
                                                <option value="Retail Trade">Retail Trade</option>
                                                <option value="Transportation and Warehousing">Transportation and Warehousing</option>
                                                <option value="Information Technology and Communications">Information Technology and Communications</option>
                                                <option value="Finance and Insurance">Finance and Insurance</option>
                                                <option value="Professional, Scientific, and Technical Services">Professional, Scientific, and Technical Services</option>
                                                <option value="Healthcare and Social Assistance">Healthcare and Social Assistance</option>
                                                <option value="Education Services">Education Services</option>
                                                <option value="Arts, Entertainment, and Recreation">Arts, Entertainment, and Recreation</option>
                                                <option value="Accommodation and Food Services">Accommodation and Food Services</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="niche">Niche
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="niche" name="niche" class="form-control" placeholder="Enter Niche">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="social_profiles">Social Profiles
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="social_profiles" name="social_profiles" class="form-control" placeholder="Enter Social Profiles">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="associate_manager">Associate Manager
                                        </label>
                                        <div class="form-control-group">
                                            <input type="text" id="associate_manager" name="associate_manager" class="form-control" placeholder="Enter Associate Manager">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="lead_source">Lead Source
                                        </label>
                                        <div class="form-control-group">
                                            <select id="lead_source" name="lead_source" class="select2 form-control">
                                                <option value="Referral">Referral</option>
                                                <option value="Website">Website</option>
                                                <option value="Cold Call">Cold Call</option>
                                                <option value="Offline">Offline</option>
                                                <option value="Upwork">Upwork</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="lead_type">Lead Type
                                        </label>
                                        <div class="form-control-group">
                                            <select id="lead_type" name="lead_type" class="select2 form-control">
                                                <option value="Inbound">Inbound</option>
                                                <option value="Outbound">Outbound</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="client_status">Client Status
                                        </label>
                                        <div class="form-control-group">
                                            <select id="client_status" name="client_status" class="select2 form-control">
                                                <option value="Active">Active</option>
                                                <option value="Lost">Lost</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="contract_start_date">Contract Start Date
                                        </label>
                                        <div class="form-control-group">
                                            <input type="date" id="contract_start_date" name="contract_start_date" class="form-control datepicker" placeholder="Enter Contract Start Date">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="contract_end_date">Contract End Date
                                        </label>
                                        <div class="form-control-group">
                                            <input type="date" id="contract_end_date" name="contract_end_date" class="form-control datepicker" placeholder="Enter Contract End Date">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label class="col-form-label" for="documents_upload">Documents Upload:
                                        </label>
                                        <div class="form-control-group">
                                            <input type="file" id="documents_upload" name="documents_upload" class="form-control datepicker" placeholder="Enter File Upload: Contract, NDA, etc" multiple="multiple">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label class="col-form-label" for="notes">Notes
                                        </label>
                                        <div class="form-control-group">
                                            <textarea id="notes" name="notes" class="form-control" placeholder="Enter notes">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label class="col-form-label" for="client_feedback">Client Feedback
                                        </label>
                                        <div class="form-control-group">
                                            <textarea id="client_feedback" name="client_feedback" class="form-control" placeholder="Enter Client Feedback">
                                            </textarea>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <button type="submit" name="insert-client"
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