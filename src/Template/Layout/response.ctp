<?php

if (empty($response['result'])) {
    $response['result'] = [
        'error' => 'Unknown request!'
    ];
}

echo json_encode($response);
