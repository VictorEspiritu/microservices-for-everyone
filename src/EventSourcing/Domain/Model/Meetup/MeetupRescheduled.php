<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class MeetupRescheduled
{
    private $meetupId;
    private $newDate;

    public function __construct(int $meetupId, string $newDate)
    {
        $this->meetupId = $meetupId;
        $this->newDate = $newDate;
    }

    public function meetupId(): int
    {
        return $this->meetupId;
    }

    public function newDate(): string
    {
        return $this->newDate;
    }
}
