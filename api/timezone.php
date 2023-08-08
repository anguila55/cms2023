<?php


function apiTimeZone($pais, $method,$typeget)
{
    $curl = curl_init();
    $headers = [
        "Content-Type: application/json",
    ];
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://timeapi.io/api/TimeZone/".$typeget.$pais,
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
    
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
}

function getTimeZone($pais)
{
    $response = apiTimeZone($pais, "GET","zone?timeZone=");
    return $response->currentUtcOffset->seconds;
}

function getTimeZoneIp($ip)
{
    $response = apiTimeZone($ip, "GET","ip?ipAddress=");
    return $response->timeZone;
}



?>