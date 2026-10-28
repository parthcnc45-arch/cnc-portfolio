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

// Auto-login from Database-backed Remember Token (valid for 2 months / 60 days)
function checkRememberLogin($conn) {
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        return true;
    }
    
    if (!empty($_COOKIE['remember_user'])) {
        $parts = explode(':', $_COOKIE['remember_user'], 2);
        if (count($parts) === 2) {
            $user_id = intval($parts[0]);
            $cookie_token = $parts[1];
            
            if ($user_id > 0 && !empty($cookie_token)) {
                $stmt = mysqli_prepare($conn, "SELECT id, username, remember_token, remember_expires_at FROM users WHERE id = ? LIMIT 1");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt);
                    if ($user = mysqli_fetch_assoc($res)) {
                        $db_token = $user['remember_token'];
                        $expires_at = $user['remember_expires_at'];
                        
                        // Check if token exists in DB, matches cookie, and has not expired
                        if (!empty($db_token) && hash_equals($db_token, $cookie_token) && !empty($expires_at) && strtotime($expires_at) > time()) {
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['user_id'] = (int)$user['id'];
                            mysqli_stmt_close($stmt);

                            // Record Auto-Login / Remember Me session in login_logs
                            $user_ip = getUserIP();
                            $user_location = getLocationByIP($user_ip);
                            $user_country = $user_location['country'];
                            $user_city = $user_location['city'];
                            $login_time = date('Y-m-d H:i:s');
                            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                            $ua_info = parseUserAgent($user_agent);
                            $device_type = $ua_info['device_type'];
                            $browser = $ua_info['browser'];
                            $os = $ua_info['os'];
                            $login_method = 'Remember Me';
                            $session_status = 'Active';

                            $insert_stmt = mysqli_prepare($conn, "INSERT INTO login_logs (username, ip_address, country, city, login_time, device_type, browser, os, login_method, session_status, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            if ($insert_stmt) {
                                $un = $user['username'];
                                mysqli_stmt_bind_param($insert_stmt, "sssssssssss", $un, $user_ip, $user_country, $user_city, $login_time, $device_type, $browser, $os, $login_method, $session_status, $user_agent);
                                mysqli_stmt_execute($insert_stmt);
                                $_SESSION['login_log_id'] = mysqli_insert_id($conn);
                                mysqli_stmt_close($insert_stmt);
                            }
                            return true;
                        }
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
        // Invalid or expired remember cookie - clear cookie
        setcookie('remember_user', '', time() - 3600, "/");
    }
    return false;
}

// Check auto-login on every request
if (isset($conn) && $conn) {
    checkRememberLogin($conn);
}

if(isset($_REQUEST['login_submit'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = md5($password);
    // Query to check if the username and password exist in the database
    $sql = "SELECT id FROM users WHERE username = '$username' AND (password = '$hashed_password' OR password = '$password')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if ($row) {
        $user_id = (int)$row['id'];
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user_id;

        // Remember Me for 2 months (60 days) - Stored in Database
        $two_months = 60 * 24 * 60 * 60; // 5,184,000 seconds
        if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', time() + $two_months);

            // Save token and expiry date into users table
            $update_stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = ?, remember_expires_at = ? WHERE id = ?");
            if ($update_stmt) {
                mysqli_stmt_bind_param($update_stmt, "ssi", $token, $expires_at, $user_id);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }

            // Set secure HttpOnly cookie with user_id:token
            setcookie('remember_user', $user_id . ':' . $token, time() + $two_months, "/", "", false, true);
            setcookie('remember_username', $username, time() + $two_months, "/", "", false, false);
            if (session_id()) {
                setcookie(session_name(), session_id(), time() + $two_months, "/");
            }
        } else {
            // If remember is not checked, clear DB token and cookies
            $clear_stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = NULL, remember_expires_at = NULL WHERE id = ?");
            if ($clear_stmt) {
                mysqli_stmt_bind_param($clear_stmt, "i", $user_id);
                mysqli_stmt_execute($clear_stmt);
                mysqli_stmt_close($clear_stmt);
            }
            setcookie('remember_user', '', time() - 3600, "/");
            setcookie('remember_username', '', time() - 3600, "/");
        }

        $user_ip = getUserIP();
        $user_location = getLocationByIP($user_ip);
        $user_country = $user_location['country'];
        $user_city = $user_location['city'];
        $login_time = date('Y-m-d H:i:s');
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ua_info = parseUserAgent($user_agent);
        $device_type = $ua_info['device_type'];
        $browser = $ua_info['browser'];
        $os = $ua_info['os'];
        $login_method = 'Password';
        $session_status = 'Active';

        $insert_stmt = mysqli_prepare($conn, "INSERT INTO login_logs (username, ip_address, country, city, login_time, device_type, browser, os, login_method, session_status, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($insert_stmt) {
            mysqli_stmt_bind_param($insert_stmt, "sssssssssss", $username, $user_ip, $user_country, $user_city, $login_time, $device_type, $browser, $os, $login_method, $session_status, $user_agent);
            mysqli_stmt_execute($insert_stmt);
            $_SESSION['login_log_id'] = mysqli_insert_id($conn);
            mysqli_stmt_close($insert_stmt);
        }

        header("location: portfolios.php");
        exit();
    } else {
        // Login failed, set error message in session and redirect to login.php
        $_SESSION['login_error'] = 'Invalid username or password. Please try again.';
        header("Location: login.php");
        exit();
    }
}

if(isset($_REQUEST['logout'])){
    // Clear remember token in DB if user is logging out
    $logout_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($logout_user_id === 0 && !empty($_COOKIE['remember_user'])) {
        $parts = explode(':', $_COOKIE['remember_user'], 2);
        $logout_user_id = intval($parts[0] ?? 0);
    }
    if ($logout_user_id > 0 && isset($conn) && $conn) {
        $clear_stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = NULL, remember_expires_at = NULL WHERE id = ?");
        if ($clear_stmt) {
            mysqli_stmt_bind_param($clear_stmt, "i", $logout_user_id);
            mysqli_stmt_execute($clear_stmt);
            mysqli_stmt_close($clear_stmt);
        }
    }

    // Record logout time and mark session as Logged Out in login_logs
    if (isset($conn) && $conn) {
        $logout_time = date('Y-m-d H:i:s');
        if (!empty($_SESSION['login_log_id'])) {
            $log_id = (int)$_SESSION['login_log_id'];
            $up_stmt = mysqli_prepare($conn, "UPDATE login_logs SET logout_time = ?, session_status = 'Logged Out' WHERE id = ?");
            if ($up_stmt) {
                mysqli_stmt_bind_param($up_stmt, "si", $logout_time, $log_id);
                mysqli_stmt_execute($up_stmt);
                mysqli_stmt_close($up_stmt);
            }
        } elseif (!empty($_SESSION['username'])) {
            $un = $_SESSION['username'];
            $up_stmt = mysqli_prepare($conn, "UPDATE login_logs SET logout_time = ?, session_status = 'Logged Out' WHERE username = ? AND logout_time IS NULL AND session_status = 'Active' ORDER BY id DESC LIMIT 1");
            if ($up_stmt) {
                mysqli_stmt_bind_param($up_stmt, "ss", $logout_time, $un);
                mysqli_stmt_execute($up_stmt);
                mysqli_stmt_close($up_stmt);
            }
        }
    }

    session_destroy();
    setcookie('remember_user', '', time() - 3600, "/");
    header("Location: login.php");
    exit();
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
/**
 * Return approximate city/country for a public IP address.
 * Private and loopback addresses are not sent to a geolocation provider.
 */
function getLocationByIP($ip) {
    $unknown = ['city' => 'Unknown', 'country' => 'Unknown'];
    $ip = trim((string)$ip);
    if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) return $unknown;
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        return ['city' => 'Local network', 'country' => 'Unknown'];
    }
    if (!function_exists('curl_init')) return $unknown;
    $url = 'https://ipwho.is/' . rawurlencode($ip) . '?fields=success,city,country_code';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_TIMEOUT => 5,
        CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_HTTPHEADER => ['Accept: application/json'],
    ]);
    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (!is_string($response) || $response === '' || $httpCode < 200 || $httpCode >= 300) return $unknown;
    $data = json_decode($response, true);
    if (!is_array($data) || empty($data['success'])) return $unknown;
    return [
        'city' => !empty($data['city']) ? (string)$data['city'] : 'Unknown',
        'country' => !empty($data['country_code']) ? (string)$data['country_code'] : 'Unknown',
    ];
}
function getCountryByIP($ip) {
    $location = getLocationByIP($ip);
    return $location['country'];
}

function getCurrentAuthUserId($conn) {
    if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0) {
        return (int)$_SESSION['user_id'];
    }
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? LIMIT 1");
        if ($stmt) {
            $u = $_SESSION['username'];
            mysqli_stmt_bind_param($stmt, "s", $u);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($res)) {
                $_SESSION['user_id'] = (int)$row['id'];
                mysqli_stmt_close($stmt);
                return (int)$_SESSION['user_id'];
            }
            mysqli_stmt_close($stmt);
        }
    }
    return null;
}

function fetchUserRecentCopiedLinks($conn, $userId) {
    if (!$conn || !$userId) {
        return [];
    }
    $stmt = mysqli_prepare(
        $conn,
        "SELECT 
            h.id AS history_id,
            h.portfolio_id,
            h.copied_at,
            p.company_name,
            p.website_url
        FROM portfolio_copy_history h
        INNER JOIN portfolio p ON h.portfolio_id = p.id
        WHERE h.user_id = ? 
          AND p.deleted = '0' 
          AND p.website_url IS NOT NULL 
          AND TRIM(p.website_url) != ''
        ORDER BY h.copied_at DESC 
        LIMIT 5"
    );
    $links = [];
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $links[] = [
                'portfolio_id' => (int)$row['portfolio_id'],
                'company_name' => $row['company_name'] ?: 'Untitled Project',
                'website_url'  => $row['website_url'],
                'copied_at'    => $row['copied_at']
            ];
        }
        mysqli_stmt_close($stmt);
    }
    return $links;
}

if (!date_default_timezone_get() || date_default_timezone_get() === 'UTC') {
    date_default_timezone_set('Asia/Kolkata');
}

/**
 * Lightweight, reliable User-Agent parser detecting Device Type, Browser, and OS
 */
function parseUserAgent($userAgent) {
    if (empty($userAgent)) {
        return [
            'device_type' => 'Not Available',
            'browser' => 'Not Available',
            'os' => 'Not Available'
        ];
    }

    // 1. Device Type
    $deviceType = 'Desktop';
    if (preg_match('/(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk)/i', $userAgent)) {
        $deviceType = 'Tablet';
    } elseif (preg_match('/(mobi|iphone|ipod|phone|blackberry|opera mini|fennec|minimo|symbian|psp|nintendo)/i', $userAgent)) {
        $deviceType = 'Mobile';
    }

    // 2. Operating System
    $os = 'Unknown OS';
    if (preg_match('/windows nt 10\.0/i', $userAgent)) {
        $os = 'Windows 10/11';
    } elseif (preg_match('/windows nt 6\.3/i', $userAgent)) {
        $os = 'Windows 8.1';
    } elseif (preg_match('/windows nt 6\.2/i', $userAgent)) {
        $os = 'Windows 8';
    } elseif (preg_match('/windows nt 6\.1/i', $userAgent)) {
        $os = 'Windows 7';
    } elseif (preg_match('/windows nt 6\.0/i', $userAgent)) {
        $os = 'Windows Vista';
    } elseif (preg_match('/windows nt 5\.1/i', $userAgent)) {
        $os = 'Windows XP';
    } elseif (preg_match('/windows/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/android\s*([0-9\.]+)?/i', $userAgent, $matches)) {
        $os = 'Android' . (!empty($matches[1]) ? ' ' . $matches[1] : '');
    } elseif (preg_match('/iphone os\s*([0-9\_]+)?/i', $userAgent, $matches)) {
        $os = 'iOS' . (!empty($matches[1]) ? ' ' . str_replace('_', '.', $matches[1]) : '');
    } elseif (preg_match('/ipad;\s*cpu\s*os\s*([0-9\_]+)?/i', $userAgent, $matches)) {
        $os = 'iPadOS' . (!empty($matches[1]) ? ' ' . str_replace('_', '.', $matches[1]) : '');
    } elseif (preg_match('/mac os x\s*([0-9\_\.]+)?/i', $userAgent, $matches)) {
        $os = 'macOS' . (!empty($matches[1]) ? ' ' . str_replace('_', '.', $matches[1]) : '');
    } elseif (preg_match('/cros/i', $userAgent)) {
        $os = 'Chrome OS';
    } elseif (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    }

    // 3. Browser & Version
    $browser = 'Unknown Browser';
    if (preg_match('/edg[ea]?\/([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'Edge ' . $matches[1];
    } elseif (preg_match('/opr\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/opera\/([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'Opera ' . $matches[1];
    } elseif (preg_match('/(chrome|crios)\/([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'Chrome ' . $matches[2];
    } elseif (preg_match('/(firefox|fxios)\/([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'Firefox ' . $matches[2];
    } elseif (preg_match('/version\/([0-9\.]+).*safari/i', $userAgent, $matches)) {
        $browser = 'Safari ' . $matches[1];
    } elseif (preg_match('/msie\s*([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'IE ' . $matches[1];
    } elseif (preg_match('/trident\/.*rv:([0-9\.]+)/i', $userAgent, $matches)) {
        $browser = 'IE ' . $matches[1];
    }

    // Simplify version to major.minor
    if (preg_match('/^([a-zA-Z\s]+)\s+([0-9]+(?:\.[0-9]+)?)/', $browser, $bMatches)) {
        $browser = $bMatches[1] . ' ' . $bMatches[2];
    }

    return [
        'device_type' => $deviceType,
        'browser' => $browser,
        'os' => $os
    ];
}

function formatSessionDuration($seconds) {
    if ($seconds === null || $seconds < 0) {
        return 'N/A';
    }
    if ($seconds < 60) {
        return $seconds . 's';
    }
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;

    $parts = [];
    if ($days > 0) $parts[] = $days . 'd';
    if ($hours > 0) $parts[] = $hours . 'h';
    if ($minutes > 0) $parts[] = $minutes . 'm';
    if (empty($parts) || ($days == 0 && $hours == 0 && $secs > 0 && count($parts) < 2)) {
        $parts[] = $secs . 's';
    }
    return implode(' ', $parts);
}

function resolveLogSessionStatus($row) {
    if (empty($row['session_status'])) {
        return 'Not Available';
    }
    if ($row['session_status'] === 'Logged Out') {
        return 'Logged Out';
    }
    if ($row['session_status'] === 'Active') {
        if (!empty($row['logout_time'])) {
            return 'Logged Out';
        }
        if (isset($_SESSION['login_log_id']) && (int)$_SESSION['login_log_id'] === (int)$row['id']) {
            return 'Active';
        }
        if (!empty($row['login_time']) && (time() - strtotime($row['login_time'])) > 86400) {
            return 'Expired';
        }
        return 'Active';
    }
    return $row['session_status'];
}

function getLogSessionDuration($loginTime, $logoutTime, $sessionStatus) {
    if (empty($loginTime)) {
        return 'N/A';
    }
    $loginTs = strtotime($loginTime);
    if ($sessionStatus === 'Active' && empty($logoutTime)) {
        $diff = time() - $loginTs;
        return formatSessionDuration($diff) . ' (Active)';
    }
    if (!empty($logoutTime)) {
        $logoutTs = strtotime($logoutTime);
        $diff = $logoutTs - $loginTs;
        if ($diff >= 0) {
            return formatSessionDuration($diff);
        }
    }
    return 'N/A';
}

function renderDeviceBadge($deviceType) {
    if (empty($deviceType) || $deviceType === 'Not Available' || $deviceType === 'N/A') {
        return '<span class="log-na">N/A</span>';
    }
    $icon = 'fa-desktop';
    $lower = strtolower($deviceType);
    if (strpos($lower, 'mobile') !== false || strpos($lower, 'phone') !== false) {
        $icon = 'fa-mobile-alt';
    } elseif (strpos($lower, 'tablet') !== false || strpos($lower, 'ipad') !== false) {
        $icon = 'fa-tablet-alt';
    }
    return '<span class="device-badge"><i class="fas ' . $icon . '"></i> ' . htmlspecialchars($deviceType) . '</span>';
}

function renderBrowserBadge($browser) {
    if (empty($browser) || $browser === 'Not Available' || $browser === 'N/A' || $browser === 'Unknown Browser') {
        return '<span class="log-na">N/A</span>';
    }
    $icon = 'fas fa-globe';
    $lower = strtolower($browser);
    if (strpos($lower, 'chrome') !== false) {
        $icon = 'fab fa-chrome icon-chrome';
    } elseif (strpos($lower, 'edge') !== false) {
        $icon = 'fab fa-edge icon-edge';
    } elseif (strpos($lower, 'firefox') !== false) {
        $icon = 'fab fa-firefox-browser icon-firefox';
    } elseif (strpos($lower, 'safari') !== false) {
        $icon = 'fab fa-safari icon-safari';
    } elseif (strpos($lower, 'opera') !== false) {
        $icon = 'fab fa-opera icon-opera';
    } elseif (strpos($lower, 'ie') !== false) {
        $icon = 'fab fa-internet-explorer icon-ie';
    }
    return '<span class="env-badge"><i class="' . $icon . '"></i> ' . htmlspecialchars($browser) . '</span>';
}

function renderOsBadge($os) {
    if (empty($os) || $os === 'Not Available' || $os === 'N/A' || $os === 'Unknown OS') {
        return '<span class="log-na">N/A</span>';
    }
    $icon = 'fas fa-desktop';
    $lower = strtolower($os);
    if (strpos($lower, 'windows') !== false) {
        $icon = 'fab fa-windows icon-windows';
    } elseif (strpos($lower, 'mac') !== false || strpos($lower, 'ios') !== false || strpos($lower, 'ipad') !== false) {
        $icon = 'fab fa-apple icon-apple';
    } elseif (strpos($lower, 'android') !== false) {
        $icon = 'fab fa-android icon-android';
    } elseif (strpos($lower, 'linux') !== false) {
        $icon = 'fab fa-linux icon-linux';
    } elseif (strpos($lower, 'cros') !== false || strpos($lower, 'chrome') !== false) {
        $icon = 'fab fa-chrome icon-cros';
    }
    return '<span class="env-badge"><i class="' . $icon . '"></i> ' . htmlspecialchars($os) . '</span>';
}

function renderLoginMethodBadge($method) {
    if (empty($method) || $method === 'Not Available' || $method === 'N/A') {
        return '<span class="log-na">N/A</span>';
    }
    $cls = 'badge-method-password';
    $icon = 'fas fa-key';
    if ($method === 'Remember Me') {
        $cls = 'badge-method-remember';
        $icon = 'fas fa-cookie-bite';
    } elseif ($method === 'Auto Login') {
        $cls = 'badge-method-autologin';
        $icon = 'fas fa-sync-alt';
    }
    return '<span class="badge-method ' . $cls . '"><i class="' . $icon . '"></i> ' . htmlspecialchars($method) . '</span>';
}

function renderSessionStatusBadge($status) {
    if (empty($status) || $status === 'Not Available' || $status === 'N/A') {
        return '<span class="log-na">N/A</span>';
    }
    if ($status === 'Active') {
        return '<span class="badge-status badge-status-active"><span class="status-dot"></span> Active</span>';
    } elseif ($status === 'Logged Out') {
        return '<span class="badge-status badge-status-loggedout"><i class="fas fa-sign-out-alt"></i> Logged Out</span>';
    } elseif ($status === 'Expired') {
        return '<span class="badge-status badge-status-expired"><i class="far fa-clock"></i> Expired</span>';
    }
    return '<span class="badge-status">' . htmlspecialchars($status) . '</span>';
}