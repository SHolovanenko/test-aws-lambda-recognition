<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

return function ($data) {
    $data = isset($data['Input']['Payload']) ? $data['Input']['Payload'] : $data;
    $result = boolval($data['dogFound']);
    return $result;
};
