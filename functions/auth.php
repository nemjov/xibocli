<?php

require './vendor/autoload.php'; // Include the Composer autoloader
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Exception\RequestException;

function getAuth()
{
    require './config/conf.php';

    // Xibo API configuration
    $apiUrl = $xiboserver . '/api';

    // Create a provider
    $provider = new GenericProvider([
        'clientId'                => $clientId,
        'clientSecret'            => $clientSecret,
        'urlAuthorize'            => '', // not used in the client credentials flow
        'urlAccessToken'          => $apiUrl . '/authorize/access_token', // adjust the path accordingly
        'urlResourceOwnerDetails' => '', // not used in the client credentials flow
    ]);

    // Get an access token
    try {
        $accessToken = $provider->getAccessToken('client_credentials');

        // Now you can use $accessToken to make requests to the Xibo API
        // For example, fetching a list of layouts
        $response = $provider->getAuthenticatedRequest(
            'GET',
            $apiUrl . '/api/authorize',
            $accessToken
        )->getBody();

        // Output the response
        $json_data_pretty = json_encode($accessToken, JSON_PRETTY_PRINT);
        return $accessToken;
    } catch (RequestException $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        echo 'HTTP response code: ', $e->getResponse()->getStatusCode(), "\n";
        echo 'Raw response: ', $e->getResponse()->getBody(), "\n";
    }
}
