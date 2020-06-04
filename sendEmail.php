<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/configuration.php';

use Aws\Ses\SesClient;

return function ($data) {
    $data = isset($data['Input']['Payload']) ? $data['Input']['Payload'] : $data;

    $recipient_email = $data['email'];
    $image = $data['image_url'];

    $SesClient = new SesClient([
        'version' => 'latest',
        'region'  => AWS_REGION,
        'credentials' => [
            'key' => AWS_KEY,
            'secret' => AWS_SECRET
        ]
    ]);
    
    $sender_email = 'serhii.holovanenko@gmail.com';
    
    $subject = 'Image not a dog (via Amazon SES)';
    $plaintext_body = 'Image not a dog.' ;
    $html_body =  '<h1>Image not a dog.</h1>'.
                  '<p><img src="'. $image .'"></p>';
    $char_set = 'UTF-8';

    $result = $SesClient->sendEmail([
        'Destination' => [
            'ToAddresses' => [$recipient_email],
        ],
        'ReplyToAddresses' => [$sender_email],
        'Source' => $sender_email,
        'Message' => [
            'Body' => [
                'Html' => [
                    'Charset' => $char_set,
                    'Data' => $html_body,
                ],
                'Text' => [
                    'Charset' => $char_set,
                    'Data' => $plaintext_body,
                ],
            ],
            'Subject' => [
                'Charset' => $char_set,
                'Data' => $subject,
            ],
        ]
    ]);

    $data['messageId'] = $result['MessageId'];
    
    return $data;
};