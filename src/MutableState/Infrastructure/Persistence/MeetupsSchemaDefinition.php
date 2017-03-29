<?php
declare(strict_types = 1);

namespace MutableState\Infrastructure\Persistence;

use DbalSchema\SchemaDefinition;
use Doctrine\DBAL\Schema\Schema;

final class MeetupsSchemaDefinition implements SchemaDefinition
{
    public function define(Schema $schema)
    {
        $table = $schema->createTable('Meetup');
        $table->addColumn('id', 'string');
        $table->addColumn('scheduledDate', 'string');
    }
}
