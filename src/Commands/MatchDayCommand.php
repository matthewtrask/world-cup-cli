<?php

declare(strict_types=1);

namespace Trask\WorldCup\Commands;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchDayCommand extends Command
{
    /** @var Client */
    private $client;

    /** @var string */
    private $uri = 'http://worldcup.sfg.io/matches/today';

    public function __construct(Client $client)
    {
        parent::__construct(null);
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setName('worldcup:matchday')
            ->setDescription('Get the days matches')
            ->addArgument('events', InputArgument::OPTIONAL, 'List of cards during the matches today');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $input->getArgument('events');

        //make request
        $res = $this->client->request('GET', $this->uri);

        //cast data to string
        $data = (string) $res->getBody();

        //make it an array so its nice to play with
        $matches = json_decode($data, true);

        $table = new Table($output);

        if(!$events) {
            $table->setHeaders(['Home Team', 'Visiting Team', 'Venue', 'Completed', 'Time', 'Winner', 'Goals']);


            foreach((array) $matches as $match) {
                $table->addRows([
                    [$match['home_team']['country'],
                        $match['away_team']['country'],
                        $match['venue'],
                        $match['status'],
                        $match['time'],
                        $match['winner']],
                    new TableSeparator(),
                ]);
            }

            $table->setStyle('box');
            $table->render();
        }

        if ($events) {
            $table->setHeaders(['Player', 'Time', 'Event']);

            foreach($matches as $match) {
                foreach($match['home_team_events'] as $event) {
                    $table->addRows([
                        [$event['player'], $event['time'], $event['type_of_event']],
                    ]);
                }

            }

            $table->setStyle('box');
            $table->render();
        }

    }
}