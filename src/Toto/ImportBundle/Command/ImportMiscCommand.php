<?php
namespace Toto\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportMiscCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('toto:import:misc')
            ->setDescription('Import miscelaneous stuff')
            ->addArgument(
                'tournament',
                InputArgument::REQUIRED,
                'Tournament name'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $name = $input->getArgument('tournament');

        $output->writeln('<question>Tournament ' . $name . '</question>');

        $service = 'toto_import.misc_importer.' . $name;
        if (!$container->has($service)) {
            $output->writeln('<error>Misc stuff importer for tournament not found</error>');
            return;
        }

        $output->writeln('<info>Importing misc stuff...</info>');

        $res = $container->get($service)->import();
        if ($res === false) {
            $output->writeln('<info>Error</info>');
            return;
        }

        $output->writeln('<info>Done!</info>');
    }
}