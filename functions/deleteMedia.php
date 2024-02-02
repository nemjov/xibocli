<?php
function deleteMedia($id,$accessToken)
{
    require './config/conf.php';
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $xiboserver . '/api/library/' . urlencode($id),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_POSTFIELDS => 'forceDelete=1',
        CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer ' . $accessToken
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

}