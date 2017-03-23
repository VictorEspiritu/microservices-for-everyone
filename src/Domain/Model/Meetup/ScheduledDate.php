<?php
declare(strict_types = 1);

namespace Domain\Model\Meetup;

final class ScheduledDate
{
    private $dateTime;

    public function __construct(string $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function __toString()
    {
        return $this->dateTime;
    }
}
