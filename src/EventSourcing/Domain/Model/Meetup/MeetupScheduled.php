<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class MeetupScheduled
{
    private $meetupId;
    private $provisionalDate;

    public function __construct(int $meetupId, string $provisionalDate)
    {
        $this->meetupId = $meetupId;
        $this->provisionalDate = $provisionalDate;
    }

    public function meetupId(): int
    {
        return $this->meetupId;
    }

    public function provisionalDate(): string
    {
        return $this->provisionalDate;
    }
}
