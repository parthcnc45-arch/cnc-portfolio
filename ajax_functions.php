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
?> 
