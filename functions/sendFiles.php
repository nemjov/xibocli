<?php

require './vendor/autoload.php'; // Include the Composer autoloader
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function sendFiles($accessToken, $fileFullPath, $tag,$mediaLibrary)
{
    require './config/conf.php';
// CLEAR THE UPLOAD LOGFILE
    $client = new Client();
    $fileContent = file_get_contents($fileFullPath);

    if ($fileContent !== false) {
        // Sanitize the filename by removing null bytes
        $sanitizedFilename = str_replace("\0", '', $fileFullPath);
        $randomNumber = substr(uniqid('', true), 0, 2) . mt_rand(1000, 9999);
        $sanitizedFilenameDir = $tag . '_' . $randomNumber . '_' . basename($sanitizedFilename);

        $postData = [
            'multipart' => [
                [
                    'name' => 'tags[]',
                    'contents' => $tag,
                ],
                [
                    'name' => 'files[]',
                    'contents' => $fileContent,
                    'filename' => $sanitizedFilenameDir,
                ],
                [
                    'name' => 'folderId',  // Include parentId in multipart
                    'contents' => $mediaLibrary,    // Set the folderId you want
                ],
            ],
        ];

        // Add the Authorization header with the access token
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $options = [
            'headers' => $headers,
            'multipart' => $postData['multipart'],
        ];

        try {
            $response = $client->request('POST', $xiboserver . '/api/library', $options);
            $jsonString = $response->getBody();

            // Decode the JSON string into a PHP array
            $data = json_decode($jsonString, true);

            // Check if decoding was successful
            if ($data !== null) {
                // Encode the array back to a formatted JSON string
                $prettyJson = json_encode($data, JSON_PRETTY_PRINT);
                // Echo the formatted JSON in a <pre> tag for better readability

            } else {
                // Handle decoding error
                echo 'Error decoding JSON response';
            }
        } catch (RequestException $e) {
            echo 'Guzzle error: ' . $e->getMessage();
        }
    } else {
        // Handle the case where file reading failed for $fileFullPath
        echo "Failed to read file: $fileFullPath\n";
    }
}