<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TravelManCommand extends Command
{
    // The name of the command (the part after "bin/console")
    protected static $defaultName = 'zinio:travel-man';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Calculate the shortest path to our travel man.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command calculates the shortest path to our travel man from the cities list on cities.txt file at db folder.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ... put here the code to create the user

        


        // green text
        $output->writeln('<info>foo</info>');

        // yellow text
        $output->writeln('<comment>foo</comment>');

        // black text on a cyan background
        $output->writeln('<question>foo</question>');

        // white text on a red background
        $output->writeln('<error>foo</error>');



        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}