<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class Meetup
{
    private $recordedEvents = [];

    private $id;
    private $scheduledDate;

    private function __construct()
    {
    }

    public static function schedule(int $id, string $provisionalDate): Meetup
    {
        $meetup = new self();
        $meetup->recordThat(new MeetupScheduled($id, $provisionalDate));

        return $meetup;
    }

    public static function reconstitute(array $events)
    {
        $meetup = new self();

        foreach ($events as $event) {
            $meetup->apply($event);
        }

        return $meetup;
    }

    public function reschedule(string $newDate): void
    {
        $this->recordThat(new MeetupRescheduled($this->id, $newDate));
    }

    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    private function recordThat($event): void
    {
        $this->recordedEvents[] = $event;

        $this->apply($event);
    }

    private function apply($event)
    {
        if ($event instanceof MeetupScheduled) {
            $this->applyMeetupScheduledEvent($event);
        } elseif ($event instanceof MeetupRescheduled) {
            $this->applyMeetupRescheduledEvent($event);
        }
    }

    private function applyMeetupScheduledEvent(MeetupScheduled $event): void
    {
        $this->id = $event->meetupId();
        $this->scheduledDate = $event->provisionalDate();
    }

    private function applyMeetupRescheduledEvent(MeetupRescheduled $event): void
    {
        $this->scheduledDate = $event->newDate();
    }
}
