<?php
$file = $_GET['file'];

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    flush();
    readfile($file);
    exit;
} else {
    http_response_code(404);
    echo "File not found!";
}
?>
