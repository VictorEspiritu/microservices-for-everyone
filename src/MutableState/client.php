<?php

use function Common\CommandLine\line;
use function Common\CommandLine\make_green;
use function Common\CommandLine\stdout;
use Doctrine\DBAL\DriverManager;
use MutableState\Domain\Model\Meetup\Meetup;
use MutableState\Infrastructure\Persistence\DataMapper;
use Webmozart\Console\IO\ConsoleIO;
use Webmozart\Console\UI\Component\Table;

require __DIR__ . '/../../vendor/autoload.php';

function printTable(array $data)
{
    if (empty($data)) {
        return;
    }

    $table = new Table();
    $table->setHeaderRow(array_keys(reset($data)));
    foreach ($data as $row) {
        $table->addRow($row);
    }
    $table->render(new ConsoleIO());
}

$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'user' => 'user',
    'password' => 'password',
    'host' => 'mysql',
    'dbname' => 'app'
]);
$connection->executeUpdate('DELETE FROM Meetup');
$dataMapper = new DataMapper($connection);

$meetup = Meetup::schedule('2017-03-23 19:00');

$dataMapper->persist($meetup);
$dataMapper->flush();

$allData = $connection->fetchAll('SELECT * FROM Meetup');
stdout(line(make_green('After insert:')));
printTable($allData);

$meetup->reschedule('2017-04-04 19:00');

$dataMapper->flush();

$allData = $connection->fetchAll('SELECT * FROM Meetup');
stdout(line(make_green('After update:')));
printTable($allData);
