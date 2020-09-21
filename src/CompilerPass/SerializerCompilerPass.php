<?php

declare(strict_types=1);

namespace App\CompilerPass;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SerializerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.base_controller_interface');

        foreach ($taggedServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setSerializer', [new Reference(SerializerInterface::class)]);
        }
    }
}