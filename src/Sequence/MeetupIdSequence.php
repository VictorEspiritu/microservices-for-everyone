<?php
declare(strict_types = 1);

namespace Sequence;

use Predis\Client;

final class MeetupIdSequence
{
    /**
     * @var Client
     */
    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function nextId(): int
    {
        return $this->redisClient->incr('meetup_id');
    }
}
