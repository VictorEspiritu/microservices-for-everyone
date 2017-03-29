<?php
declare(strict_types = 1);

namespace MutableState\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;

final class DataMapper
{
    private $connection;
    private $newEntities = [];
    private $managedEntities = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function persist($entity)
    {
        if (isset($this->managedEntities[$this->objectHash($entity)])) {
            return;
        }

        $this->newEntities[$this->objectHash($entity)] = $entity;
    }

    public function flush()
    {
        $this->connection->transactional(function () {
            $this->performInserts();
            $this->performUpdates();

            foreach ($this->newEntities as $entity) {
                unset($this->newEntities[$this->objectHash($entity)]);
                $this->managedEntities[$this->objectHash($entity)] = $entity;
            }
        });
    }

    private function performUpdates()
    {
        foreach ($this->managedEntities as $entity) {
            $table = $this->tableNameForEntity($entity);
            $data = $this->extractData($entity);
            $id = $data['id'];
            unset($data['id']);

            $this->connection->update($table, $data, ['id' => $id]);
        }
    }

    private function performInserts()
    {
        foreach ($this->newEntities as $entity) {
            $table = $this->tableNameForEntity($entity);
            $data = $this->extractData($entity);
            unset($data['id']);
            $this->connection->insert($table, $data);

            // set the auto-incremented ID
            $idProperty = new \ReflectionProperty($entity, 'id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, (int)$this->connection->lastInsertId());
        }
    }

    private function objectHash($entity): string
    {
        return spl_object_hash($entity);
    }

    private function tableNameForEntity($entity): string
    {
        return basename(str_replace('\\', '/', get_class($entity)));
    }

    private function extractData($entity): array
    {
        $data = [];
        foreach ((new \ReflectionObject($entity))->getProperties() as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($entity);
        }

        return $data;
    }
}
