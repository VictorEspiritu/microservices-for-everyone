<?php

use function Common\CommandLine\line;
use function Common\CommandLine\make_green;
use function Common\CommandLine\stdout;
use EventSourcing\Domain\Model\Meetup\Meetup;
use EventSourcing\Domain\Model\Meetup\MeetupId;
use EventSourcing\Domain\Model\Meetup\ScheduledDate;

require __DIR__ . '/../../vendor/autoload.php';

$events = [];

stdout(line(make_green('Schedule a Meetup')));
$meetup = Meetup::schedule(
    MeetupId::fromString('7acb3dc4-8d3a-4418-9c7d-8216787736cd'),
    ScheduledDate::fromString('2017-03-23 19:00')
);

$events = array_merge($events, $meetup->recordedEvents());

stdout(line(make_green('Stored events:')));
dump($events);

// a while later

stdout(line(make_green('Reconstitute a Meetup')));
$meetup = Meetup::reconstitute($events);

stdout(line(make_green('Reschedule a Meetup')));
$meetup->reschedule(ScheduledDate::fromString('2017-04-04 19:00'));

$events = array_merge($events, $meetup->recordedEvents());

stdout(line(make_green('Stored events:')));
dump($events);
