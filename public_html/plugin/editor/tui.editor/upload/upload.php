<?php
include_once("config.php");

if ($_FILES['file']['name']) {
    $filename = $_FILES['file']['name'];
    $filetype = $_FILES['file']['type'];
    $filetmp = $_FILES['file']['tmp_name'];

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    if (!in_array($extension, $allowedExtensions) || !in_array($filetype, $allowedMimeTypes)) {
        echo json_encode(['success' => false, 'error' => '허용되지 않는 파일 형식입니다.']);
        exit;
    }

    $filename = tui_replace_filename($filename);
    $location = SAVE_DIR . '/' . $filename;
    $location_url = SAVE_URL . '/' . $filename;
    if (move_uploaded_file($filetmp, $location)) {
        echo json_encode(['success' => true, 'url' => $location_url]);
    } else {
        echo json_encode(['success' => false, 'error' => '파일 업로드 실패']);
    }
} else {
    echo json_encode(['success' => false, 'error' => '파일이 없음']);
}