<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/configuration.php';

use Aws\Rekognition\RekognitionClient;

return function ($keyname) {
    $config = [
        'version' => 'latest',
        'region'  => AWS_REGION,
        'credentials' => [
            'key' => AWS_KEY,
            'secret' => AWS_SECRET
        ]
    ];

    $rClient = new RekognitionClient($config);

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
                return true;
            }
        }

        return false;

    } catch (Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }
};
