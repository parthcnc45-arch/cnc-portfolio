<?php
include_once('db.php');
header('Content-Type: application/json');
    if(isset($_POST['website']) && $_REQUEST['action'] != 'updateFavouritePortfolio') {
        $website = $_POST['website'];
        $mainDomain = extractProjectName($website);
        $res_metas = get_metas_cnc($website);
        $metas = json_decode($res_metas);
        if(!empty($metas)){
            $company_image = $metas->image_src;
        }
        $array = array(
            'company_name'=>$mainDomain,
            'company_image'=>$company_image,
        );
        echo json_encode($array);
        die();
    }

    if(isset($_POST['action']) && $_POST['action'] == 'delet_portfolio') {
        $portfolio_id = $_REQUEST['portfolio_id'];
        $sql = "UPDATE `portfolio` SET`deleted`='1' WHERE `id`=$portfolio_id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
        }
        exit;
    }

    if(isset($_POST['action']) && $_POST['action'] == 'delet_client') {
        $client_id = $_REQUEST['client_id'];
        $sql = "UPDATE `clients` SET `deleted`='1' WHERE `id`=$client_id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
        }
        exit;
    }
    if (isset($_POST['action']) && $_POST['action'] == 'multi_delete_portfolio') {
        $selectedIds = isset($_POST['ids']) ? $_POST['ids'] : [];

        if (empty($selectedIds)) {
            echo json_encode(["success" => false, "message" => "No IDs provided."]);
            exit;
        }

        $safeIds = array_map('intval', $selectedIds);
        $idList = implode(',', $safeIds);

        $sql = "UPDATE `portfolio` SET `deleted`='1' WHERE `id` IN ($idList)";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
        }
        exit;
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_portfolios') {
        $status = $_REQUEST['value'];
        $result = mysqli_query($conn,"SELECT * FROM portfolio WHERE `deleted`='$status' ORDER BY timestamp DESC");
        include 'portfolios-table.php';
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'get_clients') {
        $status = isset($_REQUEST['value']) ? intval($_REQUEST['value']) : 0;
        $result = mysqli_query($conn, "SELECT * FROM clients WHERE `deleted`='$status' ORDER BY created_at DESC");
        $total_clients = mysqli_num_rows($result);
        include 'clients-table.php';
        exit;
    }

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateFavouritePortfolio') {
        $website = $_REQUEST['website'];
        $design = $_REQUEST['design'];
        $currentTimestamp = date('Y-m-d H:i:s');
        $sql = "UPDATE `favourite` SET`value`='$website',`timestamp`='$currentTimestamp' WHERE `name`='websites'";
        $result = mysqli_query($conn, $sql);
        $sql = "UPDATE `favourite` SET`value`='$design',`timestamp`='$currentTimestamp' WHERE `name`='designs'";
        $result = mysqli_query($conn, $sql);
    }


    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'changeFavouritePortfolio') {
        $item_id = $_REQUEST['item_id'];
        $is_favorite = $_REQUEST['is_favorite'];
        $sql = "UPDATE `portfolio` SET`favourite`='$is_favorite' WHERE `id`=$item_id";
        $result = mysqli_query($conn, $sql);
    }
    
    function fetch_all_rows($conn,$query,$tablename,$colname,$query_type_two){
       if( !empty( $query_type_two) ){
        $sql = $query 
        ? "SELECT id, $colname FROM $tablename WHERE $colname LIKE '%$query%' AND `framework_type` = '$query_type_two' LIMIT 10"
        : "SELECT id, $colname FROM $tablename WHERE `framework_type` = '$query_type_two'";
        
       }else{
        $sql = $query 
        ? "SELECT id, $colname FROM $tablename WHERE $colname LIKE '%$query%' LIMIT 10"
        : "SELECT id, $colname FROM $tablename";
       }      
        $result = $conn->query($sql);
        if($result){  
            if (!$result) {
                echo json_encode(["error" => "SQL Error: " . $conn->error]);
                exit;
            }
    
            $data = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }       
            echo json_encode($data);
            $conn->close();
        }
    }
    /*fetch all indsutries data in industy field select 2 dropdown code start*/
    $query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';   
    $query_type = isset($_GET['query_type']) ? $conn->real_escape_string($_GET['query_type']) : '';
    $query_type_two = isset($_GET['query_type_two']) ? $conn->real_escape_string($_GET['query_type_two']) : '';
    if($query_type == "industry_field"){
        fetch_all_rows($conn,$query,"industries","industrie_name","");
    } 
    if($query_type == "framework_field" && $query_type_two){
        fetch_all_rows($conn,$query,"framework","framework_name",$query_type_two);
    } 
    if($query_type == "programming_language_field"){
        fetch_all_rows($conn,$query,"programming_language","programming_language_name","");
    } 
    if($query_type == "technology_field"){
        fetch_all_rows($conn,$query,"technology","technology_name","");
    } 
    if($query_type == "portfolio_type_field"){
        fetch_all_rows($conn,$query,"application_type","application_type_name","");
    } 
    /*fetch all indsutries data in industy field select 2 dropdown code end*/    
    
    if (isset($_POST['industrie_name'])) {
        $industrie_name = trim($_POST['industrie_name']);
    
        $stmt = $conn->prepare("SELECT COUNT(*) FROM industries WHERE industrie_name = ?");
        $stmt->bind_param("s", $industrie_name);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        echo ($count > 0) ? "exists" : "not_exists";
    }
 
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'fetch_niche_tags') {        
        $sql = "SELECT niche FROM portfolio";
        $result = mysqli_query($conn, $sql);
    
        $niche_tags = [];
        $unique_texts = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['niche'])) {
                // Explode comma-separated values into an array
                $tags = explode(',', $row['niche']);
                // Trim spaces and add to an array of objects
                foreach ($tags as $tag) {
                    $trimmedTag = trim($tag);
                    if (!empty($trimmedTag) && !in_array($trimmedTag, $unique_texts)) {
                        $unique_texts[] = $trimmedTag; // Track unique texts
                        $niche_tags[] = [
                            "value" => count($niche_tags) + 1, // Unique ID
                            "text"  => $trimmedTag
                        ];
                    }
                }
            }
        }
    
        // Remove duplicate values based on the 'text' field
        $niche_tags = array_unique($niche_tags, SORT_REGULAR);
    
        // Output as JSON
        header('Content-Type: application/json');
        echo json_encode(array_values($niche_tags));
        exit;
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'fetch_tags') {        
        $sql = "SELECT tags FROM portfolio";
        $result = mysqli_query($conn, $sql);
    
        $tagss = [];
        $unique_texts = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['tags'])) {
                // Explode comma-separated values into an array
                $tags = explode(',', $row['tags']);
                // Trim spaces and add to an array of objects
                foreach ($tags as $tag) {
                    $trimmedTag = trim($tag);
                    if (!empty($trimmedTag) && !in_array($trimmedTag, $unique_texts)) {
                        $unique_texts[] = $trimmedTag; // Track unique texts
                        $tagss[] = [
                            "value" => count($tagss) + 1, // Unique ID
                            "text"  => $trimmedTag
                        ];
                    }
                }
            }
        }
    
        // Remove duplicate values based on the 'text' field
        $tagss = array_unique($tagss, SORT_REGULAR);
    
        // Output as JSON
        header('Content-Type: application/json');
        echo json_encode(array_values($tagss));
        exit;
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'team_associates') {        
        $sql = "SELECT team_associates FROM portfolio";
        $result = mysqli_query($conn, $sql);
    
        $team_associates_tags = [];
        $unique_texts = [];
    
        while ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['team_associates'])) {
                // Explode comma-separated values into an array
                $tags = explode(',', $row['team_associates']);
                // Trim spaces and add to an array of objects
                foreach ($tags as $tag) {
                    $trimmedTag = trim($tag);
                    if (!empty($trimmedTag) && !in_array($trimmedTag, $unique_texts)) {
                        $unique_texts[] = $trimmedTag; // Track unique texts
                        $team_associates_tags[] = [
                            "value" => count($team_associates_tags) + 1, // Unique ID
                            "text"  => $trimmedTag
                        ];
                    }
                }
            }
        }
    
        // Remove duplicate values based on the 'text' field
        $team_associates_tags = array_unique($team_associates_tags, SORT_REGULAR);
    
        // Output as JSON
        header('Content-Type: application/json');
        echo json_encode(array_values($team_associates_tags));
        exit;
    }

    // Get Search History
    if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'get_search_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "SELECT id, search_term, created_at FROM portfolio_search_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $history = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $history[] = [
                    'id' => (int)$row['id'],
                    'search_term' => $row['search_term'],
                    'created_at' => $row['created_at']
                ];
            }
            mysqli_stmt_close($stmt);
            echo json_encode(["success" => true, "history" => $history]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
        exit;
    }

    // Save Search History
    if (isset($_POST['action']) && $_POST['action'] === 'save_search_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $searchTerm = isset($_POST['search_term']) ? trim($_POST['search_term']) : '';
        if ($searchTerm === '') {
            echo json_encode(["success" => false, "message" => "Empty search term"]);
            exit;
        }

        // Case-insensitive check if term already exists for this user
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM portfolio_search_history WHERE user_id = ? AND LOWER(search_term) = LOWER(?) LIMIT 1");
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "is", $userId, $searchTerm);
            mysqli_stmt_execute($checkStmt);
            $checkRes = mysqli_stmt_get_result($checkStmt);
            if ($existing = mysqli_fetch_assoc($checkRes)) {
                mysqli_stmt_close($checkStmt);
                // Update existing record timestamp and term casing
                $updateStmt = mysqli_prepare($conn, "UPDATE portfolio_search_history SET search_term = ?, created_at = NOW() WHERE id = ?");
                if ($updateStmt) {
                    $existingId = (int)$existing['id'];
                    mysqli_stmt_bind_param($updateStmt, "si", $searchTerm, $existingId);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                }
            } else {
                mysqli_stmt_close($checkStmt);
                // Insert new record
                $insertStmt = mysqli_prepare($conn, "INSERT INTO portfolio_search_history (user_id, search_term, created_at) VALUES (?, ?, NOW())");
                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "is", $userId, $searchTerm);
                    mysqli_stmt_execute($insertStmt);
                    mysqli_stmt_close($insertStmt);
                }
            }
        }

        // Clean up entries beyond the latest 10 for this user
        $cleanStmt = mysqli_prepare($conn, "DELETE FROM portfolio_search_history WHERE user_id = ? AND id NOT IN (SELECT id FROM (SELECT id FROM portfolio_search_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 10) as keep_ids)");
        if ($cleanStmt) {
            mysqli_stmt_bind_param($cleanStmt, "ii", $userId, $userId);
            mysqli_stmt_execute($cleanStmt);
            mysqli_stmt_close($cleanStmt);
        }

        // Return updated latest 10 records
        $fetchStmt = mysqli_prepare($conn, "SELECT id, search_term, created_at FROM portfolio_search_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        $history = [];
        if ($fetchStmt) {
            mysqli_stmt_bind_param($fetchStmt, "i", $userId);
            mysqli_stmt_execute($fetchStmt);
            $result = mysqli_stmt_get_result($fetchStmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $history[] = [
                    'id' => (int)$row['id'],
                    'search_term' => $row['search_term'],
                    'created_at' => $row['created_at']
                ];
            }
            mysqli_stmt_close($fetchStmt);
        }

        echo json_encode(["success" => true, "history" => $history]);
        exit;
    }

    // Clear Search History
    if (isset($_POST['action']) && $_POST['action'] === 'clear_search_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM portfolio_search_history WHERE user_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
        exit;
    }

    // Get Recently Copied Links History
    if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'get_copy_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $history = fetchUserRecentCopiedLinks($conn, $userId);
        echo json_encode(["success" => true, "history" => $history]);
        exit;
    }

    // Record / Update Copy History
    if (isset($_POST['action']) && $_POST['action'] === 'record_copy_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $portfolioId = isset($_POST['portfolio_id']) ? (int)$_POST['portfolio_id'] : 0;
        if ($portfolioId <= 0) {
            echo json_encode(["success" => false, "message" => "Invalid portfolio ID"]);
            exit;
        }

        // Verify portfolio exists and is not deleted
        $checkPortStmt = mysqli_prepare($conn, "SELECT id, company_name, website_url FROM portfolio WHERE id = ? AND deleted = '0' LIMIT 1");
        if ($checkPortStmt) {
            mysqli_stmt_bind_param($checkPortStmt, "i", $portfolioId);
            mysqli_stmt_execute($checkPortStmt);
            $portRes = mysqli_stmt_get_result($checkPortStmt);
            $portfolio = mysqli_fetch_assoc($portRes);
            mysqli_stmt_close($checkPortStmt);

            if (!$portfolio) {
                echo json_encode(["success" => false, "message" => "Portfolio not found or inactive"]);
                exit;
            }
        }

        // Check if copy history entry already exists for this user and portfolio
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM portfolio_copy_history WHERE user_id = ? AND portfolio_id = ? LIMIT 1");
        if ($checkStmt) {
            mysqli_stmt_bind_param($checkStmt, "ii", $userId, $portfolioId);
            mysqli_stmt_execute($checkStmt);
            $checkRes = mysqli_stmt_get_result($checkStmt);
            if ($existing = mysqli_fetch_assoc($checkRes)) {
                mysqli_stmt_close($checkStmt);
                // Update timestamp to bring it to top
                $updateStmt = mysqli_prepare($conn, "UPDATE portfolio_copy_history SET copied_at = NOW() WHERE id = ?");
                if ($updateStmt) {
                    $existingId = (int)$existing['id'];
                    mysqli_stmt_bind_param($updateStmt, "i", $existingId);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                }
            } else {
                mysqli_stmt_close($checkStmt);
                // Insert new record
                $insertStmt = mysqli_prepare($conn, "INSERT INTO portfolio_copy_history (user_id, portfolio_id, copied_at) VALUES (?, ?, NOW())");
                if ($insertStmt) {
                    mysqli_stmt_bind_param($insertStmt, "ii", $userId, $portfolioId);
                    mysqli_stmt_execute($insertStmt);
                    mysqli_stmt_close($insertStmt);
                }
            }
        }

        // Clean up entries beyond latest 10 for this user
        $cleanStmt = mysqli_prepare($conn, "DELETE FROM portfolio_copy_history WHERE user_id = ? AND id NOT IN (SELECT id FROM (SELECT id FROM portfolio_copy_history WHERE user_id = ? ORDER BY copied_at DESC LIMIT 10) as keep_copy_ids)");
        if ($cleanStmt) {
            mysqli_stmt_bind_param($cleanStmt, "ii", $userId, $userId);
            mysqli_stmt_execute($cleanStmt);
            mysqli_stmt_close($cleanStmt);
        }

        $history = fetchUserRecentCopiedLinks($conn, $userId);
        echo json_encode(["success" => true, "history" => $history]);
        exit;
    }

    // Clear Copy History
    if (isset($_POST['action']) && $_POST['action'] === 'clear_copy_history') {
        $userId = getCurrentAuthUserId($conn);
        if (!$userId) {
            http_response_code(401);
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM portfolio_copy_history WHERE user_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Database error"]);
        }
        exit;
    }
?>
