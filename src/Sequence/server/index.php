<?php

use Sequence\MeetupIdSequence;

require __DIR__ . '/../../../vendor/autoload.php';

$redisClient = new Predis\Client(['host' => 'redis']);

$sequence = new MeetupIdSequence($redisClient);

http_response_code(200);
header('Content-Type: application/json');
echo json_encode([
    'id' => $sequence->nextId()
]);
