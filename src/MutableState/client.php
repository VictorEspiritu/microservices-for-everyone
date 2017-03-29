<?php

use DbalSchema\DbalSchemaCommand;
use Doctrine\DBAL\DriverManager;
use MutableState\Domain\Model\Meetup\Meetup;
use MutableState\Infrastructure\Persistence\DataMapper;
use MutableState\Infrastructure\Persistence\MeetupsSchemaDefinition;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__ . '/../../vendor/autoload.php';

$output = new ConsoleOutput();
$output->getFormatter()->setStyle('section', new OutputFormatterStyle('magenta'));

function printTable(array $data, OutputInterface $output)
{
    if (empty($data)) {
        return;
    }

    $table = new Table($output);
    $table->setHeaders(array_keys(reset($data)));
    foreach ($data as $row) {
        $table->addRow($row);
    }
    $table->render();
}

$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'user' => 'user',
    'password' => 'password',
    'host' => 'mysql',
    'dbname' => 'app'
]);

$updateSchemaCommand = new DbalSchemaCommand($connection, new MeetupsSchemaDefinition());
$updateSchemaCommand->purge(true, $output);

$dataMapper = new DataMapper($connection);

$meetup = Meetup::schedule('2017-03-23 19:00');

$dataMapper->persist($meetup);
$dataMapper->flush();

$allData = $connection->fetchAll('SELECT * FROM Meetup');
$output->writeln('<section>After insert:</section>');
printTable($allData, $output);

$meetup->reschedule('2017-04-04 19:00');

$dataMapper->flush();

$allData = $connection->fetchAll('SELECT * FROM Meetup');
$output->writeln('<section>After update:</section>');
printTable($allData, $output);
