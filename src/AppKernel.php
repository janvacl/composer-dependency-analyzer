<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

use S3tezsky\DependencyAnalyzer\CompilerPass\CommandsCollector;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class AppKernel extends Kernel
{
    public const ENVIRONMENT_DEV   = 0;
    public const ENVIRONMENT_TEST  = 1;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CommandsCollector());
    }

    public function registerBundles(): array
    {
        return [];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/../config/services.yml');
    }
}
