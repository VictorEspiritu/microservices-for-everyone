<?php
declare(strict_types = 1);

namespace MutableState\Domain\Model\Anemic;

final class Meetup
{
    private $id;
    private $scheduledDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function setScheduledDate(string $newDate): void
    {
        $this->scheduledDate = $newDate;
    }

    public function getScheduledDate(): string
    {
        return $this->scheduledDate;
    }
}
