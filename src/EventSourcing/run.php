<?php

use EventSourcing\Domain\Model\Meetup\Meetup;
use EventSourcing\Domain\Model\Meetup\MeetupId;
use EventSourcing\Domain\Model\Meetup\ScheduledDate;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/../../vendor/autoload.php';

$output = new ConsoleOutput();
$output->getFormatter()->setStyle('section', new OutputFormatterStyle('magenta'));

$events = [];

$output->writeln('<section>Schedule a Meetup</section>');
$meetup = Meetup::schedule(
    MeetupId::fromString('7acb3dc4-8d3a-4418-9c7d-8216787736cd'),
    ScheduledDate::fromString('2017-03-23 19:00')
);

$events = array_merge($events, $meetup->recordedEvents());

$output->writeln('<section>Stored events:</section>');
dump($events);

// a while later

$output->writeln('<section>Reconstitute a Meetup</section>');
$meetup = Meetup::reconstitute($events);

$output->writeln('<section>Reschedule a Meetup</section>');
$meetup->reschedule(ScheduledDate::fromString('2017-04-04 20:00'));

$events = array_merge($events, $meetup->recordedEvents());

$output->writeln('<section>Stored events:</section>');
dump($events);
