<?php
function getMedia($tag,$token)
{
    require './config/conf.php';
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $xiboserver .'/api/library?tags=' . urlencode($tag),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;

}// Function ends