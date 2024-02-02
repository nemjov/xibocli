<?php
function setMediaLibrary($accessToken)
{
    require './config/conf.php';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $xiboserver . '/api/folders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer ' . $accessToken
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    // JSON DECODE
    $data = json_decode($response, true);

// Check if decoding was successful
    if ($data !== null) {
        // Now $data is a PHP array containing the decoded JSON data
        $dirs = $data[0]['children'];

        foreach ($dirs as $dir) {

            if($dir['text'] == $folderInCMS){
                return $dir['folderId'];
            }
        }
    } else {
        // Handle decoding error
        echo 'Error decoding JSON response';
    }
}
