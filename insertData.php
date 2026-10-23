<?php

if (isset($_POST['website_url'])) :

    # Database Connection my_test_db is the Database name.
    $db_conn = mysqli_connect("localhost", "root", "", "cnc_portfolio");

    # Assigning user data to variables for easy access later.
    $website = $_POST['website_url'];
    $country = $_POST['country'];
    $company_name = $_POST['company_name'];
    $industries = $_POST['industries'];
    $current_status = $_POST['current_status'];

    # SQL query for Inserting the Form Data into the users table.
    $sql = "INSERT INTO `portfolio` (`website_url`, `country`,`company_name`,`industries`,`current_status`) VALUES ('$website', '$country','$company_name' , '$industries' ,'$current_status')";

    # Executing the Above SQL query.
    $query = mysqli_query($db_conn, $sql);

    # Checks that the query executed successfully
    if ($query) {
        echo 'New data inserted successfully. <a href="./index.html">Go Back</a>';
    } else {
        echo "Failed to insert new data.";
    }
    exit;
endif;


