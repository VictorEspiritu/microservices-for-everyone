<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class ScheduledDate
{
    private $date;

    private function __construct(string $date)
    {
        $this->date = $date;
    }

    public static function fromString(string $date): ScheduledDate
    {
        return new self($date);
    }
}
