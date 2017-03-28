<?php

use Cassandra\SimpleStatement;

require __DIR__ . '/../../vendor/autoload.php';

//ini_set('cassandra.log', 'cassandra.log');
//ini_set('cassandra.log_level', 'TRACE');

$cluster = Cassandra::cluster()
    ->withContactPoints('cassandra')
    ->build();
$session = $cluster->connect();

$schema = <<<EOD
DROP KEYSPACE IF EXISTS meetup_management;

CREATE KEYSPACE meetup_management WITH REPLICATION = { 'class' : 'SimpleStrategy', 'replication_factor' : 3 };

USE meetup_management;

CREATE TABLE IF NOT EXISTS meetups (
  id int PRIMARY KEY,
  scheduledDate varchar,
);

INSERT INTO meetups (id, scheduledDate) VALUES (1, '2017-03-28');
UPDATE meetups SET scheduledDate = '2017-04-03' WHERE id = 1;
EOD;

foreach (explode(";\n", $schema) as $cql) {
    $cql = trim($cql);

    if (empty($cql)) {
        continue;
    }

    echo $cql . "\n";
    $session->execute(new SimpleStatement($cql));
}

echo <<<EOD
Now run:

docker-compose run cqlsh cqlsh cassandra -e "SELECT * FROM meetup_management.meetups;"
EOD;
