<?php

namespace Browscap\Command;

use Browscap\Generator\BuildGenerator;
use Browscap\Helper\LoggerHelper;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author James Titcumb <james@asgrim.com>
 * @package Browscap\Command
 */
class BuildCommand extends Command
{
    /**
     * @var string
     */
    const DEFAULT_BUILD_FOLDER = '/../../../build';

    /**
     * @var string
     */
    const DEFAULT_RESOURCES_FOLDER = '/../../../resources';

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $defaultBuildFolder = __DIR__ . self::DEFAULT_BUILD_FOLDER;
        $defaultResourceFolder = __DIR__ . self::DEFAULT_RESOURCES_FOLDER;

        $this
            ->setName('build')
            ->setDescription('The JSON source files and builds the INI files')
            ->addArgument('version', InputArgument::REQUIRED, 'Version number to apply')
            ->addOption('output', null, InputOption::VALUE_REQUIRED, 'Where to output the build files to', $defaultBuildFolder)
            ->addOption('resources', null, InputOption::VALUE_REQUIRED, 'Where the resource files are located', $defaultResourceFolder);
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resourceFolder = $input->getOption('resources');
        $buildFolder = $input->getOption('output');
        $version = $input->getArgument('version');

        $loggerHelper = new LoggerHelper();
        $logger = $loggerHelper->create();

        $buildGenerator = new BuildGenerator($resourceFolder, $buildFolder);
        $buildGenerator
            ->setLogger($logger)
            ->generateBuilds($version)
        ;

        $logger->log(Logger::INFO, 'Build done.');
    }
}
