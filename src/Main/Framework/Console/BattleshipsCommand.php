<?php

namespace Framework\Console;

use Controller\ConsoleController;
use Output\ConsoleOutputManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BattleshipsCommand extends Command
{

    /**
     * @var ConsoleController
     */
    private $controller;

    /**
     * @var ConsoleOutputManager
     */
    private $consoleOutput;

    public function __construct(ConsoleController $controller, ConsoleOutputManager $consoleOutput)
    {
        $this->controller = $controller;
        $this->consoleOutput = $consoleOutput;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('battleships');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->controller->setOutputManager($this->consoleOutput);

        $consoleOutput = $this->controller->newGameAction();

        $output->writeln($consoleOutput->getOutput());
        $output->writeln('');

        $this->gameLoop($input, $output);
    }

    private function gameLoop(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Enter coordinates (row, col), e.g. A5 ');

        do {
            $answer = $helper->ask($input, $output, $question);
            $output->writeln('');

            try {
                $consoleOutput = $this->controller->submitAction($answer);
                $output->writeln($consoleOutput->getOutput());

                $continue = !$consoleOutput->getStopStatus();
            } catch (\InvalidArgumentException $e) {
                return true;
            }
        } while ($continue);
    }
}