<?php

const TMP_DIR     = 'tmp/';
const LIBRARY_DIR = 'library/';

require_once 'functions.php';

header('Content-Type: application/json; charset=utf-8');

validateMethod();

$payload = getPayload();

try {
    $tmpPath   = TMP_DIR . generateRandomName();
    $imagePath = LIBRARY_DIR . generateRandomName() . '.jpeg';

    getFile($tmpPath, $payload['image_url']);
    processImage($tmpPath, $imagePath);
} catch (Exception $exception) {
    http_response_code(500);

    echo json_encode([
        'error' => 'Server error.'
    ]);

    exit();
}
