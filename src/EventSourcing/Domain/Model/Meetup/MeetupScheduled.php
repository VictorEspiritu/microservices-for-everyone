<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class MeetupScheduled
{
    private $meetupId;
    private $provisionalDate;

    public function __construct(MeetupId $meetupId, ScheduledDate $provisionalDate)
    {
        $this->meetupId = $meetupId;
        $this->provisionalDate = $provisionalDate;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function provisionalDate(): ScheduledDate
    {
        return $this->provisionalDate;
    }
}
