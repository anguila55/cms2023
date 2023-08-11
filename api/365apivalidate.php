<?php

function eventUsers($user, $password)
{

    $data = getEvent($user, $password);    
    return $user;

}
function apiCms2($url, $method, $payload = null, $token = null)
{
    $client = new http\Client;
    $request = new http\Client\Request;
    $request->setRequestUrl('https://cmseventos.com/api/' . $url);
    $request->setRequestMethod($method);
    $body = new http\Message\Body;
    if ($payload) {
        $body->append(json_encode($payload));
    }
    $request->setBody($body);
    $request->setOptions([]);
    $headers = ['X-Requested-With' => 'XMLHttpRequest', 'Content-Type' => 'application/json'];
    if ($token) {
        $headers['Authorization'] = 'Bearer ' . $token;
    }
    $request->setHeaders($headers);
    $client->enqueue($request)->send();
    $response = $client->getResponse();
    return $response->getBody();
}
function apiCms($url, $method, $payload = null, $token = null)
{
    $curl = curl_init();
    $headers = [
        "X-Requested-With: XMLHttpRequest",
        "Content-Type: application/json",
    ];
    if ($token) {
        array_push($headers, "Authorization: Bearer " . $token);
    }
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://cmseventos.com/api/" . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    if ($payload) {
        curl_setopt_array($curl, [CURLOPT_POSTFIELDS => json_encode($payload)]);
    }
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response);
}

function getToken()
{
    $payload = ["email" => "api@api.com", "password" => "api123456"];
    $response = apiCms("auth", "POST", $payload);
    return $response->access_token;
}

function getEvents()
{
    $response = apiCms("events", "GET", null, getToken());
    return $response->data;
}

function getEvent($user, $password)
{
    $token = getToken();
    if (!validateUser($user, $password, $token)) {
       
        header('Location: login');
		exit;
        
    }
    return $user;
}

function validateUser($user, $password, $token)
{
    $payload = ["email" => $user, "password" => $password];
    $response = apiCms('validate/ilf-2023', "POST", $payload, $token);
    //var_dump($response);die;
    return $response->message == "Correct user credentials" ? true : false;
}

?>