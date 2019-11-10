<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer\CompilerPass;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CommandsCollector implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $applicationDefinition = $containerBuilder->getDefinition(Application::class);

        foreach ($containerBuilder->getDefinitions() as $name => $definition) {
            /** @var string $class */
            $class = $definition->getClass();
            if (is_a($class, Command::class, true)) {
                $applicationDefinition->addMethodCall('add', [new Reference($name)]);
            }
        }
    }
}
