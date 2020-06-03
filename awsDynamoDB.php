<?php

require_once 'vendor/autoload.php';
require_once './configuration.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Marshaler;

function saveDogImageToDynamoDb($image, $lables) {
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
        'image' => $image,
        'lables' => $lables
    ]);

    $params = [
        'TableName' => $tableName,
        'Item' => $marshaler->marshalJson($json)
    ];

    $result = $dynamodb->putItem($params);

    return $result;
}