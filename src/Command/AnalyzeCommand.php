<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer\Command;

use S3tezsky\DependencyAnalyzer\RequirementsVersionsAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'composer:dependencies:analyze';

    /** @var RequirementsVersionsAnalyzer */
    private $requirementsVersionsAnalyzer;

    public function __construct(RequirementsVersionsAnalyzer $composerDependencyProvider)
    {
        $this->requirementsVersionsAnalyzer = $composerDependencyProvider;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Check repository dependencies according to actual versions of packages');
        $this->setHelp('usage: composer:dependencies:analyze symfony/console');
        $this->addArgument('repository', InputArgument::OPTIONAL, 'Github repository name');
        $this->addArgument('branch', InputArgument::OPTIONAL, 'Github branch name');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string|null $repository */
        $repository = $input->getArgument('repository');
        /** @var string|null $branch */
        $branch = $input->getArgument('branch');

        $dependencies = $this->requirementsVersionsAnalyzer->getRequirementsWithAvailableVersions($repository, $branch);
        ksort($dependencies);

        $table = new Table($output);
        $table->setHeaders(['package', 'actual', '', 'upgradable to']);
        foreach ($dependencies as $dep) {
            $latestVersion = $dep->getLatestVersion();
            $table->addRow([
                $dep->getRequiredPackage()->getPackage()->getName(),
                $dep->getRequiredPackage()->getVersion(),
                '->',
                $latestVersion ? $latestVersion->getVersionTag() : 'Unresolved',
            ]);
        }
        $table->render();
    }
}
