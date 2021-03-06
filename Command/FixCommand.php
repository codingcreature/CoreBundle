<?php

namespace c33s\CoreBundle\Command;


//
//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class FixCommand extends ContainerAwareCommand
{
    protected $commandSets = array   
    (
	array('description' => 'Code Standard Fixer', 'command' => 'phpfix fix --verbose ./'),
	//array('description' => 'Php Code Sniffer', 'command' => 'phpcs ./src --ignore=Model/*/Base --standard=PSR1'),
	//array('description' => 'Sensio Security Checker', 'command' => 'security-checker security:check'),
	//array('description' => 'Copy&Paste Dedector', 'command' => 'copypaste --progress ./src'),
	//array('description' => 'Pdepend', 'command' => 'pdepend --jdepend-chart=tmp/chart.png --jdepend-xml=tmp/depend.xml --overview-pyramid=tmp/pyramid.png --summary-xml=tmp/summary.xml ./src'),
	//array('description' => 'phploc', 'command' => 'phploc --progress --exclude Model --count-tests ./src'),
	//array('description' => '', 'command' => ''),
	//array('description' => '', 'command' => ''),
	
	
    );
    protected function configure()
    {
        $this
            ->setName('c33s:fix')
            ->setDescription('c33s:fix calls multiple Tests to check the project')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	//$output->writeln('starting dumping config files');
	$output->writeln('<info>c33s:fix</info>');
	$this->dumpConfigFiles($output);

	foreach ($this->commandSets as $commandSet) 
	{
	    $output->writeln(sprintf('Running <comment>%s</comment> fix.', $commandSet['description']));
	    $process = new Process($commandSet['command']);
	    $process->run(function ($type, $buffer)
	    {
		if (Process::ERR === $type)
		{
		    echo $buffer;
		}
		else
		{
		    echo $buffer;
		}
	    });
	}





//	$command	 = $this->getApplication()->find('assetic:dump');
//	$arguments = array(
//	     'command' => 'assetic:dump',
//	    '--process-isolation' => true,
//	    '--shell' => true,
//	    '--force' => true,
//	    // 'no-debug'  => true,
//	    // 'env'    => 'prod',
//	 );
//	$input		 = new ArrayInput($arguments);
//	$returnCode	 = $command->run($input, $output);
//	foreach ($this->commandSets as $commandSet)
//	{
//
//	    if ($returnCode == 0)
//	    {
//
//	    }
//	}
//	$command = $this->getApplication()->find('cache:clear');
//
//	    $arguments = array(
//		'command' => 'cache:clear',
//		//'name'    => 'Fabien',
//		'--env'  => 'prod',
//	    );
//
//	    $input = new ArrayInput($arguments);
//	    $returnCode = $command->run($input, $output);


    }
    
    protected function dumpConfigFiles(OutputInterface $output)
    {
	$templateing = $this->getContainer()->get('templating');
	$fs = new Filesystem();
	if (!$fs->exists('.php_cs'))
	{
	    $output->writeln('<comment>starting dumping config files</comment>');
	    $body = $templateing->render
	    (
		'c33sCoreBundle:Command:code_standard_fixer.php.twig',
		array()
	    );
	    try
	    {
		$fs->dumpFile('.php_cs', $body);
	    }
	    catch (IOException $e)
	    {
		$output->writeln('<error>An error occurred while dumping the config files</error>');
	    }

	    if (!$fs->exists('.php_cs'))
	    {
		$output->writeln('<error>Dumping config file failed, file does not exist after dumping</error>');
	    }
	    else
	    {
		$output->writeln('<comment>done dumping config files</comment>');
	    }
	}
    }
}