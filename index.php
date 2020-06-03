<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;  
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

return function ($keyname) {
    $bucket = 'images2recognition';

    $credentials = [
        'key' => AWS_KEY,
        'secret' => AWS_SECRET
    ];

    $s3Client = new S3Client([
        'version' => 'latest',
        'region'  => AWS_REGION,
        'credentials' => $credentials
    ]);

    try {
        $result = $s3Client->getObject([
            'Bucket' => $bucket,
            'Key'    => $keyname
        ]);

        return $result['ContentType'];

    } catch (Exception $e) {
        return $e->getMessage() . PHP_EOL;
    }
};
