<?php
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $clientId = '1265331302475894858';
    $clientSecret = 'TsKS2G-rg3CxCWJTSKjeOXZC0Kb9-DWC';
    $redirectUri = 'https://synos-ms.site/callback';

    $tokenUrl = 'https://discord.com/api/oauth2/token';
    $data = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'scope' => 'identify email'
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($tokenUrl, false, $context);
    $tokenData = json_decode($response, true);

    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];
        $userUrl = 'https://discord.com/api/v10/users/@me';
        $userOptions = [
            'http' => [
                'header'  => "Authorization: Bearer $accessToken\r\n",
                'method'  => 'GET',
            ],
        ];
        $userContext = stream_context_create($userOptions);
        $userResponse = file_get_contents($userUrl, false, $userContext);
        $userData = json_decode($userResponse, true);

        echo json_encode($userData);
    } else {
        echo "Error retrieving access token";
    }
} else {
    echo "Error: No code provided";
}
?>
