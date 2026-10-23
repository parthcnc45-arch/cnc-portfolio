<?php
include_once('db.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

header('Content-Type: application/json');

try {
    $selectedIds = isset($_POST['ids']) ? $_POST['ids'] : [];

    if (empty($selectedIds)) {
        throw new Exception("No records selected.");
    }

    $idList = implode(",", array_map('intval', $selectedIds));
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = [
        "Serial No", "Type", "Company Name", "Website Url", "Country", "Industries",
        "CMS / Website Builder", "Backend Technologies", "Frontend Technologies", "Hosting / Cloud Services",
        "Other Technologies", "Programming Language", "Current Status", "Tags", "Design URL", "Portfolio Type",
        "About Business", "Niche", "Role", "Features", "Team Associates", "Comments", "GitHub Repository",
        "Demo Video", "Timestamp", "Featured Image", "Logo", "Title", "Sector", "Featured Video", "Tag Logo",
        "Initial Goal Title", "Initial Goal Content", "Initial Goal Image", "Pain Area Title", "Pain Area Image",
        "Pain Area Content", "Development Approach Title", "Development Approach Content", "Development Approach Image",
        "Deliverables Outlined Title", "Deliverables Outlined Content List", "Challenges Faced Title",
        "Challenges Faced Image", "Challenges Faced Content", "Solution Title", "Solution Content",
        "Unique Elements Title", "Unique Element Img", "Unique Element Cont",
        "Success Story Title", "Success Story Image", "Success Story Number", "Success Story Sub Title",
        "Success Story Content"
    ];

    foreach ($headers as $i => $header) {
        $col = Coordinate::stringFromColumnIndex($i + 1);
        $sheet->setCellValue($col . '1', $header);
    }
    // Fetch distinct types from the portfolio table
    $typeQuery = "SELECT DISTINCT type FROM portfolio";
    $typeResult = $conn->query($typeQuery);

    if (!$typeResult) {
        throw new Exception("Failed to fetch portfolio types: " . $conn->error);
    }

    $types = [];
    while ($typeRow = $typeResult->fetch_assoc()) {
        $types[] = trim($typeRow['type']);
    }
    // Fetch all available programming languages
    $languagesQuery = "SELECT programming_language_name FROM programming_language";
    $languagesResult = $conn->query($languagesQuery);

    if (!$languagesResult) {
        throw new Exception("Failed to fetch programming languages: " . $conn->error);
    }

    $allLanguages = [];
    while ($langRow = $languagesResult->fetch_assoc()) {
        $allLanguages[] = trim($langRow['programming_language_name']);
    }


    // Fetch distinct industries from the industries table
        $industriesQuery = "SELECT industrie_name FROM industries";
        $industriesResult = $conn->query($industriesQuery);

        if (!$industriesResult) {
            throw new Exception("Failed to fetch industries: " . $conn->error);
        }

        $industries = [];
        while ($industryRow = $industriesResult->fetch_assoc()) {
            $industries[] = trim($industryRow['industrie_name']);
    }
    // Fetch backend frameworks
    $backendQuery = "SELECT framework_name FROM framework WHERE framework_type = 'backend'";
    $backendResult = $conn->query($backendQuery);

    if (!$backendResult) {
        throw new Exception("Failed to fetch backend frameworks: " . $conn->error);
    }

    $backendTechnologies = [];
    while ($backendRow = $backendResult->fetch_assoc()) {
        $backendTechnologies[] = trim($backendRow['framework_name']);
    }

    // Frontend  frameworks
    $frontendQuery = "SELECT framework_name FROM framework WHERE framework_type = 'frontend'";
    $frontendResult = $conn->query($frontendQuery);

    if (!$frontendResult) {
        throw new Exception("Failed to fetch backend frameworks: " . $conn->error);
    }

    $forntTechnologies = [];
    while ($frontendRow = $frontendResult->fetch_assoc()) {
        $forntTechnologies[] = trim($frontendRow['framework_name']);
    }
     // Fetch hosting frameworks
     $hostingQuery = "SELECT framework_name FROM framework WHERE framework_type = 'hosting'";
     $hostingResult = $conn->query($hostingQuery);
 
     if (!$hostingResult) {
         throw new Exception("Failed to fetch backend frameworks: " . $conn->error);
     }
 
     $hostingTechnologies = [];
     while ($hostingdRow = $hostingResult->fetch_assoc()) {
         $hostingTechnologies[] = trim($hostingdRow['framework_name']);
     }

    // Fetch CMS frameworks
    $cmsQuery = "SELECT framework_name FROM framework WHERE framework_type = 'cms'";
    $cmsResult = $conn->query($cmsQuery);

    if (!$cmsResult) {
        throw new Exception("Failed to fetch backend frameworks: " . $conn->error);
    }

    $cmsTechnologies = [];
    while ($cmsRow = $cmsResult->fetch_assoc()) {
        $cmsTechnologies[] = trim($cmsRow['framework_name']);
       }

    // Fetch other technologies
        $otherQuery = "SELECT framework_name FROM framework WHERE framework_type = 'other'";
        $otherResult = $conn->query($otherQuery);

        if (!$otherResult) {
            throw new Exception("Failed to fetch backend frameworks: " . $conn->error);
        }

        $otherTechnologiesList = [];
        while ($otherRow = $otherResult->fetch_assoc()) {
            $otherTechnologiesList[] = trim($otherRow['framework_name']);
        }
      
   // Fetch Portfolio Types
    $portfolioQuery = "SELECT application_type_name FROM application_type";
    $portfolioResult = $conn->query($portfolioQuery);

    if (!$portfolioResult) {
        throw new Exception("Failed to fetch portfolio types: " . $conn->error);
    }

    $portfolioTypes = [];
    while ($portfolioRow = $portfolioResult->fetch_assoc()) {
        $portfolioTypes[] = trim($portfolioRow['application_type_name']);
    }


    // 🔹 Fetch selected portfolio records
    $sql = "SELECT * FROM portfolio WHERE id IN ($idList)";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Database query error: " . $conn->error);
    }

    $rowIndex = 2;
    $serialNo = 1;

    while ($row = $result->fetch_assoc()) {
        $deliverables_outlined = json_decode($row['deliverables_outlined'], true);
        if (!is_array($deliverables_outlined)) {
            $deliverables_outlined = unserialize($row['deliverables_outlined']);
        }
        $deliverables_outlined_list = !empty($deliverables_outlined) ? implode(" | ", $deliverables_outlined) : '';

        $unique_elements = json_decode($row['unique_or_interactive_elements'], true);
        if (!is_array($unique_elements)) {
            $unique_elements = unserialize($row['unique_or_interactive_elements']);
        }
        $unique_titles = !empty($unique_elements['unique_elements_title']) ? implode(" | ", $unique_elements['unique_elements_title']) : '';
        $unique_imgs = !empty($unique_elements['unique_elements_image']) ? implode(" | ", $unique_elements['unique_elements_image']) : '';
        $unique_conts = !empty($unique_elements['unique_elements_content']) ? implode(" | ", $unique_elements['unique_elements_content']) : '';

        $success = json_decode($row['success_story_list'], true);
        if (!is_array($success)) {
            $success = unserialize($row['success_story_list']);
        }
        $success_imgs = !empty($success['success_image']) ? implode(" | ", $success['success_image']) : '';
        $success_nums = !empty($success['success_number']) ? implode(" | ", $success['success_number']) : '';
        $success_subtitles = !empty($success['success_subtitle']) ? implode(" | ", $success['success_subtitle']) : '';

        $data = [
            $serialNo,
            trim($row['type']),
            $row['company_name'],
            $row['website_url'],
            $row['country'],
            $row['industries'],
            trim($row['cms_plateform']),
            trim($row['backend_tech']),
            trim($row['frontend_tech']),
            trim($row['hosting_tech']),
            trim($row['other_tech']),
            trim($row['programming_language']),
            $row['current_status'],
            $row['tags'],
            $row['design_url'],
            trim($row['portfolio_type']),
            strip_tags($row['about_business']),
            $row['niche'],
            strip_tags($row['role']),
            strip_tags($row['features']),
            $row['team_associates'],
            strip_tags($row['comments']),
            $row['github_repository'],
            $row['demo_video'],
            $row['timestamp'],
            $row['featured_image'],
            $row['logo'],
            $row['title'],
            $row['sector'],
            $row['featured_video'],
            $row['tag_logo'],
            "Initial Goal",
            strip_tags($row['initial_goal']),
            "https://codeandcore.sirv.com/media/2024/10/freepik__adjust__79298-1-1.png",
            "Pain Area",
            $row['pain_area_image'],
            strip_tags($row['pain_area']),
            "Development Approach",
            strip_tags($row['development_approach']),
            "https://api.codeandcore.com/media/2024/10/69-3.png",
            "Deliverables Outlined",
            strip_tags($deliverables_outlined_list),
            "Challenges Faced",
            "https://codeandcore.sirv.com/media/2024/10/Group-1261155952.png",
            strip_tags($row['challenges_faced_during_development']),
            "Solution",
            strip_tags($row['solution']),
            $unique_titles,
            $unique_imgs,
            strip_tags($unique_conts),
            "Success Story",
            $success_imgs,
            $success_nums,
            $success_subtitles,
            strip_tags($row['success_story'])
        ];

        foreach ($data as $i => $value) {
            $col = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col . $rowIndex, $value);
        }
      

// Define a reusable dropdown setter function
$dropdowns = [
        ['column' => 2, 'options' => $types], // types
        ['column' => 6, 'options' => $industries], // Industries
        ['column' => 7, 'options' => $cmsTechnologies], // CMS
        ['column' => 8, 'options' => $backendTechnologies], // Backend
        ['column' => 9, 'options' => $forntTechnologies], // Frontend
        ['column' => 10, 'options' => $hostingTechnologies], // Hosting
        ['column' => 11, 'options' => $otherTechnologiesList], // Other Tech
        ['column' => 12, 'options' => $allLanguages], // Programming Lang
        ['column' => 16, 'options' => $portfolioTypes], // Portfolio Type
    ];

  // Create dropdown sheet if it doesn't exist
$dropdownSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Dropdowns');
$spreadsheet->addSheet($dropdownSheet);

// Add dropdown options to the "Dropdowns" sheet
$dropdownSources = [];
foreach ($dropdowns as $index => $dropdown) {
    $columnIndex = $dropdown['column'];
    $columnLetter = Coordinate::stringFromColumnIndex($columnIndex);

    $options = $dropdown['options'];

    // Define range start for this dropdown list in the Dropdowns sheet
    $startRow = 1;
    $col = Coordinate::stringFromColumnIndex($index + 1); // A, B, C, etc.

    foreach ($options as $row => $value) {
        $dropdownSheet->setCellValue("{$col}" . ($row + $startRow), $value);
    }

    $dropdownSources[$columnIndex] = [
        'range' => "'Dropdowns'!\${$col}\${$startRow}:\${$col}\$" . ($startRow + count($options) - 1),
    ];
}

// Apply dropdowns to main sheet
foreach ($dropdowns as $dropdown) {
    $colLetter = Coordinate::stringFromColumnIndex($dropdown['column']);
    $cellRef = $colLetter . $rowIndex;

    $validation = $sheet->getCell($cellRef)->getDataValidation();
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setErrorStyle(DataValidation::STYLE_STOP);
    $validation->setAllowBlank(true);
    $validation->setShowInputMessage(true);
    $validation->setShowErrorMessage(true);
    $validation->setShowDropDown(true);
    $validation->setErrorTitle('Invalid input');
    $validation->setError('Value is not in the list.');
    $validation->setPromptTitle('Pick from list');
    $validation->setPrompt('Please pick a value from the dropdown.');

    $columnIndex = $dropdown['column'];
    if ($columnIndex === 11) {
        // Apply external dropdown from hidden sheet for Other Technologies
        $validation->setFormula1($dropdownSources[$columnIndex]['range']);
    } else {
        // Inline dropdown for short lists
        $validation->setFormula1('"' . implode(',', array_map(function ($item) {
            return str_replace('"', '""', $item); // Escape double quotes
        }, $dropdown['options'])) . '"');
    }
}
        $serialNo++;
        $rowIndex++;
    }


    $exportDir = __DIR__ . '/exports/';
    if (!is_dir($exportDir)) {
        mkdir($exportDir, 0777, true);
    }

    $fileName = 'portfolio_export_' . time() . '.xlsx';
    $filePath = $exportDir . $fileName;

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    echo json_encode([
        'status' => 'success',
        'message' => 'Excel file generated successfully.',
        'file' => $fileName,
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}