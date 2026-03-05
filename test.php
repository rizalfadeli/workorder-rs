<?php

$token = "eWnjy138hracic8unQyZ";

$data = [
    "target" => "6285608981265",
    "message" => "Test WA dari Laravel"
];

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.fonnte.com/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        "Authorization: $token"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;