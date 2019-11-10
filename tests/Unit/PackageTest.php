<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer\Tests\Unit;

use PHPUnit\Framework\TestCase;
use S3tezsky\DependencyAnalyzer\Package;

class PackageTest extends TestCase
{
    public function testCretePackageFromPackageName(): void
    {
        $packageName = 'foo/bar';
        $package = Package::fromPackageName($packageName);
        $this->assertInstanceOf(Package::class, $package);
        $this->assertSame($packageName, $package->getName());
        $this->assertSame('foo', $package->getVendor());
        $this->assertSame('bar', $package->getRepository());
    }
}
