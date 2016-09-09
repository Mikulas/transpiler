<?php declare(strict_types = 1);

namespace Mikulas\TranspilerBuild;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TranspileCommand extends Command
{

	const NAME = 'transpile';
	const ARG_PATHS = 'paths';


	protected function configure()
	{
		$this->setName(self::NAME);
		$this->setDescription('Convert source from PHP7.1 to PHP7');
		$this->addArgument(self::ARG_PATHS, InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'path to transpile');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$paths = $input->getArgument(self::ARG_PATHS);

		$service = new PathTranspiler();
		foreach ($paths as $path) {
			foreach ($service->transpile($path) as $file) {
				$output->writeln($file, OutputInterface::VERBOSITY_VERBOSE);
			}
		}
	}

}
