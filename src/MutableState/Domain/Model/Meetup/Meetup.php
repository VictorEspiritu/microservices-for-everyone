<?php
declare(strict_types = 1);

namespace MutableState\Domain\Model\Meetup;

final class Meetup
{
    private $id;
    private $scheduledDate;

    private function __construct(string $provisionalDate)
    {
        $this->scheduledDate = $provisionalDate;
    }

    public static function schedule(string $provisionalDate): Meetup
    {
        return new self($provisionalDate);
    }

    public function reschedule(string $newDate): void
    {
        $this->scheduledDate = $newDate;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function scheduledDate(): string
    {
        return $this->scheduledDate;
    }
}
