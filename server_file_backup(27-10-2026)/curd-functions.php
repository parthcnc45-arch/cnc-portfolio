<?php
error_reporting(E_ALL);
function comma_string_data_caller($conn,$id_of_framework,$table_name,$col_name){  
    $framework_str      = implode(',', $id_of_framework);
    $sql_framework      = "SELECT $col_name FROM $table_name WHERE id IN ($framework_str)";
    $result             = mysqli_query($conn, $sql_framework);        
    $framework_names    = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $framework_names[] = $row[$col_name];
    }        
    $framework_data = implode(', ', $framework_names);
    return $framework_data;
}

//INSERT PORTFOLIO
if(isset($_REQUEST['insert_portfolio'])) {
       //     error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    // ini_set('log_errors', 1);
    // ini_set('error_log', 'error_log.txt');
    $type           = $_REQUEST['type'];
    $website_url    = $_REQUEST['website_url'];
    $country        = isset($_REQUEST['country']) ? implode(',', $_REQUEST['country']) : '';
    $company_name   = mysqli_real_escape_string($conn, $_REQUEST['company_name']);
    $company_image  = $_REQUEST['company_image'];  
    $industries_ids = isset($_REQUEST['industries']) ? array_map('intval', $_REQUEST['industries']) : [];
   
    $framework_ids      = isset($_REQUEST['framework']) ? array_map('intval', $_REQUEST['framework']) : [];
    $cms_plateform_ids  = isset($_REQUEST['cms_plateform']) ? array_map('intval', $_REQUEST['cms_plateform']) : [];
    $backend_tech_ids   = isset($_REQUEST['backend_tech']) ? array_map('intval', $_REQUEST['backend_tech']) : [];
    $frontend_tech_ids  = isset($_REQUEST['frontend_tech']) ? array_map('intval', $_REQUEST['frontend_tech']) : [];
    $hosting_tech_ids   = isset($_REQUEST['hosting_tech']) ? array_map('intval', $_REQUEST['hosting_tech']) : [];
    $other_tech_ids     = isset($_REQUEST['other_tech']) ? array_map('intval', $_REQUEST['other_tech']) : [];
   
    $programming_language_ids   = isset($_REQUEST['programming_language']) ? array_map('intval', $_REQUEST['programming_language']) : [];
    $technology_ids             = isset($_REQUEST['technology']) ? array_map('intval', $_REQUEST['technology']) : [];
    $portfolio_type_ids         = isset($_REQUEST['portfolio_type']) ? array_map('intval', $_REQUEST['portfolio_type']) : [];

    if (isset($_REQUEST['deliverables_outlined']) && is_array($_REQUEST['deliverables_outlined'])) {
        $deliverables_outlined = $_REQUEST['deliverables_outlined'];
        $deliverables_outlined_json = json_encode($deliverables_outlined);
    }  
    
    if (!empty($industries_ids)) {
        $table_name = "industries";
        $col_name = "industrie_name";
        $industries = comma_string_data_caller($conn,$industries_ids,$table_name,$col_name);       
    } else {
        $industries = ''; 
    }     
    
    if ( !empty($cms_plateform_ids) ) {
        $table_name = "framework";
        $col_name = "framework_name";
        $cms_plateform = comma_string_data_caller($conn,$cms_plateform_ids,$table_name,$col_name);
    } else {
        $cms_plateform = '';
    } 

    if ( !empty($backend_tech_ids) ) {
        $table_name = "framework";
        $col_name = "framework_name";
        $backend_tech = comma_string_data_caller($conn,$backend_tech_ids,$table_name,$col_name);
    } else {
        $backend_tech = '';
    } 

    if ( !empty($frontend_tech_ids) ) {
        $table_name = "framework";
        $col_name = "framework_name";
        $frontend_tech = comma_string_data_caller($conn,$frontend_tech_ids,$table_name,$col_name);
    } else {
        $frontend_tech = ''; 
    } 

    if ( !empty($hosting_tech_ids) ) {
        $table_name = "framework";
        $col_name = "framework_name";
        $hosting_tech = comma_string_data_caller($conn,$hosting_tech_ids,$table_name,$col_name);
    } else {
        $hosting_tech = ''; 
    } 

    if ( !empty($other_tech_ids) ) {
        $table_name = "framework";
        $col_name = "framework_name";
        $other_tech = comma_string_data_caller($conn,$other_tech_ids,$table_name,$col_name);
    } else {
        $other_tech = ''; 
    } 

    if (!empty($programming_language_ids)) {
        $table_name = "programming_language";
        $col_name = "programming_language_name";
        $programming_language = comma_string_data_caller($conn,$programming_language_ids,$table_name,$col_name);        
    } else {
        $programming_language = '';
    }  

    if (!empty($technology_ids)) {
        $table_name = "technology";
        $col_name = "technology_name";
        $technology = comma_string_data_caller($conn,$technology_ids,$table_name,$col_name);
    } else {
        $technology = ''; 
    }  

    if (!empty($portfolio_type_ids)) {
        $table_name = "application_type";
        $col_name = "application_type_name";
        $portfolio_type = comma_string_data_caller($conn,$portfolio_type_ids,$table_name,$col_name);        
    } else {
        $portfolio_type = ''; 
    }  

    $all_frameworks = array_merge(
        explode(',', $cms_plateform),
        explode(',', $backend_tech),
        explode(',', $frontend_tech),
        explode(',', $hosting_tech),
        explode(',', $other_tech)
    );
    
    $unique_frameworks          = array_filter(array_unique($all_frameworks));   
    $framework                  = implode(',', $unique_frameworks);    
    $tags                       = mysqli_real_escape_string($conn, $_REQUEST['tags']);   
    $about_business             = mysqli_real_escape_string($conn, $_REQUEST['about_business']);
    $niche                      = mysqli_real_escape_string($conn, $_REQUEST['niche']);    
    $features                   = mysqli_real_escape_string($conn, $_REQUEST['features']);
    $team_associates            = mysqli_real_escape_string($conn, $_REQUEST['team_associates']);
    $github_repository          = mysqli_real_escape_string($conn, $_REQUEST['github_repository']);
    $demo_video                 = mysqli_real_escape_string($conn, $_REQUEST['demo_video']);
    $role                       = mysqli_real_escape_string($conn, $_REQUEST['role']);
    $comments                   = mysqli_real_escape_string($conn, $_REQUEST['comments']);
    $initial_goal               = mysqli_real_escape_string($conn, $_REQUEST['initial_goal']);
    $pain_area                  = mysqli_real_escape_string($conn, $_REQUEST['pain_area']);
    $development_approach       = mysqli_real_escape_string($conn, $_REQUEST['development_approach']);
    //$technologies_used          = mysqli_real_escape_string($conn, $_REQUEST['technologies_used']);   

    $unique_elements_image      = $_REQUEST['unique_elements_image'] ?? [];
    $unique_elements_title      = $_REQUEST['unique_elements_title'] ?? [];
    $unique_elements_content    = $_REQUEST['unique_elements_content'] ?? [];

    $unique_or_interactive_elements = [
        'unique_elements_image' => $unique_elements_image,
        'unique_elements_title' => $unique_elements_title,
        'unique_elements_content' => $unique_elements_content
    ];
    $unique_or_interactive_elements_json = json_encode($unique_or_interactive_elements);
    $success_image          = $_REQUEST['success_image'] ?? [];
    $success_number         = $_REQUEST['success_number'] ?? [];
    $success_subtitle       = $_REQUEST['success_subtitle'] ?? [];

    $success_story    = mysqli_real_escape_string($conn, $_REQUEST['success_story']);//content in this field
    $success_story_elem = [
        'success_image'     => $success_image,
        'success_number'    => $success_number,
        'success_subtitle'  => $success_subtitle
    ];
    $success_story_elem_json                = json_encode($success_story_elem);
    $challenges_faced_during_development    = mysqli_real_escape_string($conn, $_REQUEST['challenges_faced_during_development']);
    $solution                               = mysqli_real_escape_string($conn, $_REQUEST['solution']);
    $current_status                         = $_REQUEST['current_status'];
    $design_url                             = $_REQUEST['design_url'];
    $featured_image                         = mysqli_real_escape_string($conn, $_REQUEST['featured_image']);
    $logo                                   = mysqli_real_escape_string($conn, $_REQUEST['logo']);
    $title                                  = mysqli_real_escape_string($conn, $_REQUEST['title']);
    $sector                                 = mysqli_real_escape_string($conn, $_REQUEST['sector']);
    $featured_video                         = mysqli_real_escape_string($conn, $_REQUEST['featured_video']);
    $tag_logo                               = mysqli_real_escape_string($conn, $_REQUEST['tag_logo']);
    $pain_area_image                        = mysqli_real_escape_string($conn, $_REQUEST['pain_area_image']);

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO `portfolio` 
    (`type`,`website_url`, `site_preview_image`, `country`, `company_name`, `industries`, `framework`, `cms_plateform`, `backend_tech`, `frontend_tech`, `hosting_tech`, `other_tech`,`programming_language`, `tags`, `current_status`, `design_url`, `portfolio_type`, `about_business`, `niche`, `technology`, `role`, `features`, `team_associates`, `comments`, `github_repository`, `demo_video`,`initial_goal`,`pain_area`,`development_approach`,`deliverables_outlined`,`challenges_faced_during_development`,`solution`,`unique_or_interactive_elements`,`success_story`,`success_story_list`,`featured_image`,`logo`,`title`,`sector`,`featured_video`,`tag_logo`,`pain_area_image`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");

    // Bind parameters
    $stmt->bind_param("ssssssssssssssssssssssssssssssssssssssssss", $type, $website_url, $company_image, $country, $company_name, $industries, $framework, $cms_plateform, $backend_tech, $frontend_tech, $hosting_tech, $other_tech, $programming_language, $tags, $current_status, $design_url, $portfolio_type, $about_business, $niche, $technology, $role, $features, $team_associates, $comments, $github_repository, $demo_video,$initial_goal,$pain_area,$development_approach,$deliverables_outlined_json,$challenges_faced_during_development,$solution,$unique_or_interactive_elements_json,$success_story,$success_story_elem_json,$featured_image,$logo,$title,$sector,$featured_video,$tag_logo,$pain_area_image);

    // Execute the statement
    $result = $stmt->execute();
    if ($result) {
        header('location: index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

//UPDATE PORTFOLIO
if(isset($_REQUEST['update_portfolio'])){
    //     error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    // ini_set('log_errors', 1);
    // ini_set('error_log', 'error_log.txt');
    $id                     = $_REQUEST['id'];
    $type                   = $_REQUEST['type'];
    $website_url            = $_REQUEST['website_url'];
    $country                = isset($_REQUEST['country']) ? implode(',', $_REQUEST['country']) : '';
    $company_name           = mysqli_real_escape_string($conn,$_REQUEST['company_name']);
    $company_image          = $_REQUEST['company_image'];   
    $industries_ids         = isset($_REQUEST['industries']) ? array_map('intval', $_REQUEST['industries']) : [];

    $cms_plateform_ids      = isset($_REQUEST['cms_plateform']) ? array_map('intval', $_REQUEST['cms_plateform']) : [];
    $backend_tech_ids       = isset($_REQUEST['backend_tech']) ? array_map('intval', $_REQUEST['backend_tech']) : [];
    $frontend_tech_ids      = isset($_REQUEST['frontend_tech']) ? array_map('intval', $_REQUEST['frontend_tech']) : [];
    $hosting_tech_ids       = isset($_REQUEST['hosting_tech']) ? array_map('intval', $_REQUEST['hosting_tech']) : [];
    $other_tech_ids         = isset($_REQUEST['other_tech']) ? array_map('intval', $_REQUEST['other_tech']) : [];

    $framework_ids              = isset($_REQUEST['framework']) ? array_map('intval', $_REQUEST['framework']) : [];
    $programming_language_ids   = isset($_REQUEST['programming_language']) ? array_map('intval', $_REQUEST['programming_language']) : [];
    $technology_ids             = isset($_REQUEST['technology']) ? array_map('intval', $_REQUEST['technology']) : [];
    $portfolio_type_ids         = isset($_REQUEST['portfolio_type']) ? array_map('intval', $_REQUEST['portfolio_type']) : [];

    if (!empty($industries_ids)) {
        $table_name     = "industries";
        $col_name       = "industrie_name";
        $industries     = comma_string_data_caller($conn,$industries_ids,$table_name,$col_name);       
    } else {
        $industries = ''; // Default value if no industries are selected
    } 
        
    if ( !empty($cms_plateform_ids) ) {
        $table_name     = "framework";
        $col_name       = "framework_name";
        $cms_plateform  = comma_string_data_caller($conn,$cms_plateform_ids,$table_name,$col_name);
    } else {
        $cms_plateform = ''; // Default value if no cms_plateform are selected
    } 

    if ( !empty($backend_tech_ids) ) {
        $table_name     = "framework";
        $col_name       = "framework_name";
        $backend_tech   = comma_string_data_caller($conn,$backend_tech_ids,$table_name,$col_name);
    } else {
        $backend_tech   = ''; // Default value if no backend_tech are selected
    } 

    if ( !empty($frontend_tech_ids) ) {
        $table_name     = "framework";
        $col_name       = "framework_name";
        $frontend_tech  = comma_string_data_caller($conn,$frontend_tech_ids,$table_name,$col_name);
    } else {
        $frontend_tech  = ''; // Default value if no frontend_tech are selected
    } 

    if ( !empty($hosting_tech_ids) ) {
        $table_name         = "framework";
        $col_name           = "framework_name";
        $hosting_tech       = comma_string_data_caller($conn,$hosting_tech_ids,$table_name,$col_name);
    } else {
        $hosting_tech = ''; // Default value if no hosting_tech are selected
    } 

    if ( !empty($other_tech_ids) ) {
        $table_name     = "framework";
        $col_name       = "framework_name";
        $other_tech     = comma_string_data_caller($conn,$other_tech_ids,$table_name,$col_name);
    } else {
        $other_tech     = ''; // Default value if no other_tech are selected
    } 

    if (!empty($programming_language_ids)) {
        $table_name             = "programming_language";
        $col_name               = "programming_language_name";
        $programming_language   = comma_string_data_caller($conn,$programming_language_ids,$table_name,$col_name);        
    } else {
        $programming_language = ''; // Default value if no programming_language are selected
    }  

    if (!empty($technology_ids)) {
        $table_name     = "technology";
        $col_name       = "technology_name";
        $technology     = comma_string_data_caller($conn,$technology_ids,$table_name,$col_name);
    } else {
        $technology     = ''; // Default value if no technology are selected
    }  

    if (!empty($portfolio_type_ids)) {
        $table_name         = "application_type";
        $col_name           = "application_type_name";
        $portfolio_type     = comma_string_data_caller($conn,$portfolio_type_ids,$table_name,$col_name);        
    } else {
        $portfolio_type     = ''; // Default value if no portfolio_type are selected
    }  
    $all_frameworks = array_merge(
        explode(',', $cms_plateform),
        explode(',', $backend_tech),
        explode(',', $frontend_tech),
        explode(',', $hosting_tech),
        explode(',', $other_tech)
    );
        
    $unique_frameworks          = array_filter(array_unique($all_frameworks)); 
    $framework                  = implode(',', $unique_frameworks);
    $tags                       = mysqli_real_escape_string($conn,$_REQUEST['tags']);    
    $about_business             = mysqli_real_escape_string($conn,$_REQUEST['about_business']);
    $niche                      = mysqli_real_escape_string($conn,$_REQUEST['niche']);    
    $role                       = mysqli_real_escape_string($conn,$_REQUEST['role']);
    $features                   = mysqli_real_escape_string($conn,$_REQUEST['features']);
    $comments                   = mysqli_real_escape_string($conn,$_REQUEST['comments']);
    $initial_goal               = mysqli_real_escape_string($conn, $_REQUEST['initial_goal']);
    $pain_area                  = mysqli_real_escape_string($conn, $_REQUEST['pain_area']);
    $development_approach       = mysqli_real_escape_string($conn, $_REQUEST['development_approach']);
    //$technologies_used          = mysqli_real_escape_string($conn, $_REQUEST['technologies_used']);
    if (isset($_REQUEST['deliverables_outlined']) && is_array($_REQUEST['deliverables_outlined'])) {
        $deliverables_outlined_array = array_map(function ($item) use ($conn) {
            return mysqli_real_escape_string($conn, $item);
        }, $_REQUEST['deliverables_outlined']);    
       
        $deliverables_outlined = json_encode($deliverables_outlined_array);
    } else {
        $deliverables_outlined = json_encode([]);
    }
    
    $challenges_faced_during_development    = mysqli_real_escape_string($conn, $_REQUEST['challenges_faced_during_development']);
    $solution                               = mysqli_real_escape_string($conn, $_REQUEST['solution']);
    $unique_elements_image      = $_REQUEST['unique_elements_image'] ?? [];
    $unique_elements_title      = $_REQUEST['unique_elements_title'] ?? [];
    $unique_elements_content    = $_REQUEST['unique_elements_content'] ?? [];

    $unique_or_interactive_elements = [
        'unique_elements_image'     => $unique_elements_image,
        'unique_elements_title'     => $unique_elements_title,
        'unique_elements_content'   => $unique_elements_content
    ];

    $unique_or_interactive_elements_json = json_encode($unique_or_interactive_elements);

    $success_image      = $_REQUEST['success_image'] ?? [];
    $success_number     = $_REQUEST['success_number'] ?? [];
    $success_subtitle   = $_REQUEST['success_subtitle'] ?? [];

    $success_story      = mysqli_real_escape_string($conn, $_REQUEST['success_story']);//content in this field
    $success_story_elem = [
        'success_image'     => $success_image,
        'success_number'    => $success_number,
        'success_subtitle'  => $success_subtitle
    ];
    $success_story_elem_json = json_encode($success_story_elem);

    $team_associates            = mysqli_real_escape_string($conn, $_REQUEST['team_associates']);
    $github_repository          = mysqli_real_escape_string($conn, $_REQUEST['github_repository']);
    $demo_video                 = mysqli_real_escape_string($conn, $_REQUEST['demo_video']);
    $current_status             = mysqli_real_escape_string($conn, $_REQUEST['current_status']);
    $design_url                 = mysqli_real_escape_string($conn, $_REQUEST['design_url']);
    $featured_image             = mysqli_real_escape_string($conn, $_REQUEST['featured_image']);
    $logo                       = mysqli_real_escape_string($conn, $_REQUEST['logo']);
    $title                      = mysqli_real_escape_string($conn, $_REQUEST['title']);
    $sector                     = mysqli_real_escape_string($conn, $_REQUEST['sector']);
    $featured_video             = mysqli_real_escape_string($conn, $_REQUEST['featured_video']);
    $tag_logo                   = mysqli_real_escape_string($conn, $_REQUEST['tag_logo']);
    $pain_area_image            = mysqli_real_escape_string($conn, $_REQUEST['pain_area_image']);
    $currentTimestamp = date('Y-m-d H:i:s');

    $sql = "UPDATE `portfolio` SET `type`='$type', `website_url`='$website_url',`site_preview_image`='$company_image',`country`='$country',`company_name`='$company_name',`industries`='$industries',`framework`='$framework',`cms_plateform`='$cms_plateform',`backend_tech`='$backend_tech',`frontend_tech`='$frontend_tech',`hosting_tech`='$hosting_tech',`other_tech`='$other_tech',`programming_language`='$programming_language',`current_status`='$current_status',`tags`='$tags',`timestamp`='$currentTimestamp',`design_url`='$design_url',`portfolio_type`='$portfolio_type',`about_business`='$about_business',`niche`='$niche',`technology`='$technology',`role`='$role',`features`='$features',`team_associates`='$team_associates',`comments`='$comments',`github_repository`='$github_repository',`demo_video`='$demo_video',`initial_goal`='$initial_goal',`pain_area`='$pain_area',`development_approach`='$development_approach',`deliverables_outlined`='$deliverables_outlined',`challenges_faced_during_development`='$challenges_faced_during_development',`solution`='$solution',`success_story`='$success_story',`success_story_list`='$success_story_elem_json',`featured_image`='$featured_image',`logo`='$logo',`title`='$title',`sector`='$sector',`featured_video`='$featured_video',`tag_logo`='$tag_logo',`pain_area_image`='$pain_area_image',`unique_or_interactive_elements`='$unique_or_interactive_elements_json' WHERE `id`=$id";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('location: edit-portfolio.php?id='.$id);
        exit;
    } else {
        echo "Query failed: " . mysqli_error($conn) . "<br />";
        echo '<pre>' . htmlspecialchars($sql, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>';
        exit;
    }
    $conn->close();
}

if(isset($_REQUEST['login_submit'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = md5($password);
    // Query to check if the username and password exist in the database
    $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if ($row) {
        $_SESSION['username'] = $username;
        $user_ip = getUserIP();
        $user_country = getCountryByIP($user_ip);
        $login_time = date('Y-m-d H:i:s');
        $log_sql = "INSERT INTO login_logs (username, ip_address, country, login_time) VALUES ('$username', '$user_ip', '$user_country', '$login_time')";
        mysqli_query($conn, $log_sql);
        header("location: portfolios.php");
    } else {
        // Login failed, show error message
        echo "<script>alert('Invalid username or password'); window.location.href='login.php';</script>";
    }
}

if(isset($_REQUEST['logout'])){
    session_destroy();
    header("Location: index.php");
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function getCountryByIP($ip) {
    $token = 'd4666657ea0b8c'; // Replace with your actual token
    $url = "http://ipinfo.io/{$ip}?token={$token}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    return $data['country'] ?? 'Unknown';
}