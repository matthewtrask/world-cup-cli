<?php

declare(strict_types=1);

namespace Trask\WorldCup\Commands;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TeamCommand extends Command
{
    /** @var Client */
    private $client;

    private $uri = 'http://worldcup.sfg.io/teams';

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setName('worldcup:team')
            ->setDescription('Look up one of the 32 teams playing at the FIFA World Cup 2018')
            ->addArgument('team', InputArgument::OPTIONAL, 'Pass a team playing in the World Cup');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputTeam = $input->getArgument('team');

        $res = $this->client->request('GET', $this->uri);

        $data = (string) $res->getBody();

        $teams = json_decode($data, true);

        if (!$inputTeam){
            foreach ($teams as $team) {
                $output->writeln(
                    sprintf('Team: <info>%s</info> - Group <info>%s</info>',
                        $team['country'],
                        $team['group_id']
                    )
                );
            }
        }

        if($inputTeam) {
            array_filter($teams, function ($team) use ($inputTeam, $output) {
                if($team['country'] === $inputTeam) {
                    $output->writeln(
                        sprintf('Team: <info>%s</info> - Group <info>%s</info>',
                            $team['country'],
                            $team['group_id']
                        )
                    );
                }
            });
        }
    }
}