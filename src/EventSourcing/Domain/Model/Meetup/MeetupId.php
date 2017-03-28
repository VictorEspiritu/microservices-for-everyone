<?php
declare(strict_types = 1);

namespace EventSourcing\Domain\Model\Meetup;

final class MeetupId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): MeetupId
    {
        return new self($id);
    }
}
