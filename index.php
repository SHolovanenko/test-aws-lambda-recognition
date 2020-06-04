<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/configuration.php';
require_once __DIR__ . '/awsSes.php';
require_once __DIR__ . '/awsDynamoDB.php';

use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;

return function ($keyname) {
    $keyname = is_array($keyname) ? $keyname['Input'] : $keyname;
    
    $config = [
        'version' => 'latest',
        'region'  => AWS_REGION,
        'credentials' => [
            'key' => AWS_KEY,
            'secret' => AWS_SECRET
        ]
    ];

    $rClient = new RekognitionClient($config);

    $s3Client = new S3Client($config);

    try {
        $result = $rClient->detectLabels([
            'Image' => [
                'S3Object' => [
                    'Bucket' => AWS_BUCKET,
                    'Name' => $keyname
                ],
            ],
            'MaxLabels' => 10,
            'MinConfidence' => 75
        ]);

        $result = $result->toArray();
        
        foreach ($result['Labels'] as $lable) {
            if ($lable['Name'] == 'Dog') {
                $dbResponse = saveDogImageToDynamoDb($keyname, $result['Labels']);
                return true;
            }
        }

        $image = $s3Client->getObject([
            'Bucket' => AWS_BUCKET,
            'Key'    => $keyname
        ]);

        $image = $image->toArray();
        $image = $image["@metadata"]["effectiveUri"];

        $msgId = sendNotADogEmail('serhii.holovanenko@gmail.com', $image);
        return false;

    } catch (Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }
};
