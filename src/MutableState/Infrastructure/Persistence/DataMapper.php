<?php
declare(strict_types = 1);

namespace MutableState\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Table;

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
        $this->connection->transactional(function (Connection $connection) {
            foreach ($this->managedEntities as $entity) {
                $table = $this->tableNameForEntity($entity);
                $data = $this->extractData($entity);

                $this->connection->update($table, $data, ['meetupId' => $data['meetupId']]);
            }

            foreach ($this->newEntities as $entity) {
                // set the auto-incremented ID
                $idProperty = new \ReflectionProperty($entity, 'meetupId');
                $idProperty->setAccessible(true);
                $idProperty->setValue($entity, count($this->managedEntities) + 1);

                $table = $this->tableNameForEntity($entity);
                $data = $this->extractData($entity);
                $this->createTableIfNotExists($table, array_keys($data));
                $connection->insert($table, $data);
                unset($this->newEntities[$this->objectHash($entity)]);
                $this->managedEntities[$this->objectHash($entity)] = $entity;
            }
        });
    }

    private function objectHash($entity): string
    {
        return spl_object_hash($entity);
    }

    private function createTableIfNotExists(string $tableName, array $columnNames)
    {
        if ($this->connection->getSchemaManager()->tablesExist([$tableName])) {
            return;
        }

        $table = new Table($tableName);
        foreach ($columnNames as $columnName) {
            $table->addColumn($columnName, 'string');
        }
        $this->connection->getSchemaManager()->createTable($table);
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
