<?php
header('Content-Type: application/json');
include_once('db.php');
require 'vendor/autoload.php'; // Ensure PhpSpreadsheet is autoloaded

use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $_FILES['file']['tmp_name'];

try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (count($rows) < 2) {
        http_response_code(400);
        echo json_encode(["message" => "The uploaded file is empty or lacks data rows."]);
        exit;
    }

    $header_aliases = [      
        "Type" => "type",
        "Company Name" => "company_name",
        "Website Url" => "website_url",
        "Country" => "country",
        "Industries" => "industries",
        "CMS / Website Builder" => "cms_plateform",
        "Backend Technologies" => "backend_tech",
        "Frontend Technologies" => "frontend_tech",
        "Hosting / Cloud Services" => "hosting_tech",
        "Other Technologies" => "other_tech",
        "Programming Language" => "programming_language",
        "Current Status" => "current_status",
        "Tags" => "tags",
        "Design URL" => "design_url",
        "Portfolio Type" => "portfolio_type",
        "About Business" => "about_business",
        "Niche" => "niche",
        "Role" => "role",
        "Features" => "features",
        "Team Associates" => "team_associates",
        "Comments" => "comments",
        "GitHub Repository" => "github_repository",
        "Demo Video" => "demo_video",
        "Timestamp" => "timestamp",
        "Featured Image" => "featured_image",
        "Logo" => "logo",
        "Title" => "title",
        "Sector" => "sector",
        "Featured Video" => "featured_video",
        "Tag Logo" => "tag_logo",

        "Initial Goal Title" => "",
        "Initial Goal Content" => "initial_goal",
        "Initial Goal Image" => "",

        "Pain Area Title" => "",
        "Pain Area Image" => "pain_area_image",
        "Pain Area Content" => "pain_area",

        "Development Approach Title" => "",
        "Development Approach Content" => "development_approach",
        "Development Approach Image" => "",

        "Deliverables Outlined Title" => "",
        "Deliverables Outlined Content List" => "deliverables_outlined",

        "Challenges Faced Title" => "",
        "Challenges Faced Image" => "",
        "Challenges Faced Content" => "challenges_faced_during_development",

        "Solution Title" => "",
        "Solution Content" => "solution",

        "Unique Elements Title" => "unique_or_interactive_elements",
        "Unique Element Img" => "",
        "Unique Element Cont" => "",

        "Success Story Title" => "success_story_list",
        "Success Story Image" => "",
        "Success Story Number" => "",
        "Success Story Sub Title" => "",
        "Success Story Content" => "success_story"
    ];

    $headers = array_map('trim', $rows[0]); // First row is headers
    $allowed_columns = array_values(array_filter($header_aliases));
    $deliverables_outlinedc = [];
    $unique_elements_title = [];
    $unique_elements_image = [];
    $unique_elements_content = [];
    $success_images = [];
    $success_numbers = [];
    $success_subtitles = [];

    foreach ($headers as $header) {
        $header_trimmed = trim($header);
        if (!array_key_exists($header_trimmed, $header_aliases)) {
            // Just skip unrecognized columns instead of throwing an error
            continue;
        }
    }

    $inserted = 0;

    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (count($row) === 0 || array_filter($row) === []) {
            continue; // skip empty row
        }

        $data = [];
        foreach ($row as $index => $value) {
            $original_header = $headers[$index] ?? null;
            $db_column = $header_aliases[$original_header] ?? null;
            if ($original_header === "Unique Elements Title") {
                $unique_elements_title = array_map('trim', explode("|", $value));
            } elseif ($original_header === "Unique Element Img") {
                $unique_elements_image = array_map('trim', explode("|", $value));
            } elseif ($original_header === "Unique Element Cont") {
                $unique_elements_content = array_map('trim', explode("|", $value));
            }  if ($original_header === "Success Story Image") {
                $success_images = array_map('trim', explode("|", $value));
            } elseif ($original_header === "Success Story Number") {
                $success_numbers = array_map('trim', explode("|", $value));
            } elseif ($original_header === "Success Story Sub Title") {
                $success_subtitles = array_map('trim', explode("|", $value));
            }elseif ($db_column === 'deliverables_outlined') {
                $html_items = array_map('trim', explode('|', $value));
                $data[$db_column] = json_encode($html_items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }elseif (!empty($db_column)) {
                $data[$db_column] = $value;
            }
        }

        $data['unique_or_interactive_elements'] = json_encode([
            "unique_elements_image" => $unique_elements_image,
            "unique_elements_title" => $unique_elements_title,
            "unique_elements_content" => $unique_elements_content
        ]);
        $data['success_story_list'] = json_encode([
            "success_image"    => $success_images,
            "success_number"   => $success_numbers,
            "success_subtitle" => $success_subtitles
        ]);

        if (empty($data)) continue;

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $types = str_repeat("s", count($data));
        $values = array_values($data);

        $stmt = $conn->prepare("INSERT INTO portfolio ($columns) VALUES ($placeholders)");
        if (!$stmt) {
            continue;
        }

        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            $inserted++;
        }

        $stmt->close();
    }

    $conn->close();

    echo json_encode(["message" => "Successfully imported $inserted records."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Import failed: " . $e->getMessage()]);
}
