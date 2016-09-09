<?php declare(strict_types = 1);

namespace Mikulas\TranspilerBuild;

use Mikulas\Transpiler\Transpiler;
use Symfony\Component\Console;
use Symfony\Component\Console\Input\InputInterface;


/**
 * @see http://symfony.com/doc/current/components/console/single_command_tool.html
 */
class Application extends Console\Application
{

	public function getVersion()
	{
		return Transpiler::VERSION;
	}


	public function getName()
	{
		return 'Transpiler';
	}


	protected function getCommandName(InputInterface $input)
	{
		return TranspileCommand::NAME;
	}


	protected function getDefaultCommands()
	{
		$commands = parent::getDefaultCommands();
		$commands[] = new TranspileCommand();
		return $commands;
	}


	public function getDefinition()
	{
		$inputDefinition = parent::getDefinition();
		// clear out the normal first argument, which is the command name
		$inputDefinition->setArguments();

		return $inputDefinition;
	}

}
