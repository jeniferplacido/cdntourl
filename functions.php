<?php

function validateMethod() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);

        echo json_encode([
            'error' => 'Method not allowed.'
        ]);

        exit();
    }
}

function validatePayload($payload) {
    if (!is_array($payload) || !isset($payload['image_url'])) {
        http_response_code(422);

        echo json_encode([
            'error' => 'Invalid payload.'
        ]);

        exit();
    }
}

function getPayload(): array {
    $input   = file_get_contents('php://input');
    $payload = json_decode($input, true);

    validatePayload($payload);

    return $payload;
}

function getCurrentUrl(): string {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $currentUrl = 'https://';
    } else {
        $currentUrl = 'http://';
    }

    $currentUrl .= $_SERVER['HTTP_HOST'];
    $currentUrl .= $_SERVER['REQUEST_URI'];

    return $currentUrl;
}

/**
 * @throws Exception
 */
function generateRandomName(): string {
    return bin2hex(random_bytes(32));
}

function getFile($target, $source) {
    $file = fopen($target, 'w');

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $source);
    curl_setopt($curl, CURLOPT_FILE, $file);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($curl, CURLOPT_TIMEOUT, 2000);

    curl_exec($curl);
    curl_close($curl);

    fclose($file);
}

/**
 * @throws ImagickException
 */
function processImage($tmpPath, $imagePath) {
    $image = new Imagick($tmpPath);
    $image->setImageFormat('jpeg');
    $image->writeImage($imagePath);

    unlink($tmpPath);

    echo json_encode([
        'image_url' => getCurrentUrl() . $imagePath,
    ]);

    exit();
}
