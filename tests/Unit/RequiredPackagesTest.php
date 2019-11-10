<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;
use S3tezsky\DependencyAnalyzer\Package;
use S3tezsky\DependencyAnalyzer\RequiredPackage;
use S3tezsky\DependencyAnalyzer\RequiredPackages;

class RequiredPackagesTest extends TestCase
{
    public function testCreteRequiredPackagesFromComposerJson(): void
    {
        $composerJson = (string) json_encode(
            [
                'name' => 'package/name',
                'require' => [
                    'php' => '^7.2',
                    'foo/bar' => '^1.2',
                ],
                'require-dev' => [
                    'tool/dev' => '4.5',
                    'foo/test' => '^3.0',
                ],
            ],
            JSON_PRETTY_PRINT
        );

        $requiredPackages = RequiredPackages::fromComposerJson($composerJson);
        $this->assertInstanceOf(RequiredPackages::class, $requiredPackages);
        $this->assertCount(3, $requiredPackages->getRequirements());

        $requirement1 = $requiredPackages->getRequirements()[0];
        $this->assertInstanceOf(RequiredPackage::class, $requirement1);
        $this->assertInstanceOf(Package::class, $requirement1->getPackage());
        $this->assertSame('foo/bar', $requirement1->getPackage()->getName());
        $this->assertSame('^1.2', $requirement1->getVersion());
        $this->assertFalse($requirement1->isDevOnly());

        $requirement2 = $requiredPackages->getRequirements()[1];
        $this->assertInstanceOf(RequiredPackage::class, $requirement2);
        $this->assertInstanceOf(Package::class, $requirement2->getPackage());
        $this->assertSame('tool/dev', $requirement2->getPackage()->getName());
        $this->assertSame('4.5', $requirement2->getVersion());
        $this->assertTrue($requirement2->isDevOnly());

        $requirement3 = $requiredPackages->getRequirements()[2];
        $this->assertInstanceOf(RequiredPackage::class, $requirement3);
        $this->assertInstanceOf(Package::class, $requirement3->getPackage());
        $this->assertSame('foo/test', $requirement3->getPackage()->getName());
        $this->assertSame('^3.0', $requirement3->getVersion());
        $this->assertTrue($requirement3->isDevOnly());
    }
}
