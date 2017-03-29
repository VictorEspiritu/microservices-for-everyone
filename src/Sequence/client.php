<?php

use GuzzleHttp\Client;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/../../vendor/autoload.php';

$output = new ConsoleOutput();

function nextMeetupId(): int
{
    $httpClient = new Client();
    $response = $httpClient->get('http://sequence_server/');
    $data = json_decode($response->getBody());

    return $data->id;
}

foreach (range(1, 10) as $round) {
    $output->writeln(sprintf(
        'Next meetup ID: <info>%d</info>',
        nextMeetupId()
    ));
}
