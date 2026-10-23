<?php
$zip = new ZipArchive();
$zipFile = 'backup.zip'; // Change to your desired ZIP name

if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
    $folder = 'plugins'; // Change this to the folder you want to zip
    
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::LEAVES_ONLY);

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(__DIR__) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    $zip->close();
    echo "ZIP file created successfully: <a href='$zipFile'>$zipFile</a>";
} else {
    echo "Failed to create ZIP file.";
}
?>
