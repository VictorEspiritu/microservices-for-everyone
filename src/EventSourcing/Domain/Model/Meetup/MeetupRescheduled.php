<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class MeetupRescheduled
{
    private $meetupId;
    private $newDate;

    public function __construct(MeetupId $meetupId, ScheduledDate $newDate)
    {
        $this->meetupId = $meetupId;
        $this->newDate = $newDate;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function newDate(): ScheduledDate
    {
        return $this->newDate;
    }
}
