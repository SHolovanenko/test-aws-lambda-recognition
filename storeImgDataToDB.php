<?php

require_once 'vendor/autoload.php';
require_once './configuration.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Marshaler;

return function ($data) {
    $data = isset($data['Input']['Payload']) ? $data['Input']['Payload'] : $data;

    $sdk = new Aws\Sdk([
        'version' => 'latest',
        'region'  => AWS_REGION,
        'credentials' => [
            'key' => AWS_KEY,
            'secret' => AWS_SECRET
        ]
    ]);
    
    $dynamodb = $sdk->createDynamoDb();
    $marshaler = new Marshaler();
    
    $tableName = 'recognition_results';

    $json = json_encode([
        'image' => $data['image'],
        'lables' => $data['lables']
    ]);

    $params = [
        'TableName' => $tableName,
        'Item' => $marshaler->marshalJson($json)
    ];

    $dynamodb->putItem($params);

    return $data;
};