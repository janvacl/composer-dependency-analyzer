<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer\Tests\Unit;

use S3tezsky\DependencyAnalyzer\RequiredPackage;
use S3tezsky\DependencyAnalyzer\RequiredPackageWithAvailableVersions;
use PHPUnit\Framework\TestCase;
use S3tezsky\DependencyAnalyzer\Version;

class RequiredPackageWithAvailableVersionsTest extends TestCase
{
    public function testGetLatestVersionSuccessful(): void
    {
        $requiredPackage = new RequiredPackage('foo/bar', '^10.5', false);
        $versions = [
            '8.2.1' => new Version(true, 8, 2, 1),
            '9.1.3' => new Version(true, 9, 1, 3),
            '9.2.1' => new Version(true, 9, 2, 1),
            '10.0.0' => new Version(true, 10, 0, 0),
            '10.1-dev' => new Version(true, 10, 0, 0),
        ];

        $requiredPackageWithVersions = new RequiredPackageWithAvailableVersions(
            $requiredPackage,
            $versions
        );
        $this->assertInstanceOf(RequiredPackage::class, $requiredPackageWithVersions->getRequiredPackage());

        /** @var Version $latestVersion */
        $latestVersion = $requiredPackageWithVersions->getLatestVersion();
        $this->assertInstanceOf(Version::class, $latestVersion);
        $this->assertSame('v10.0.0', $latestVersion->getVersionTag());
    }

    public function testGetLatestVersionWithoutVersionsReturnsNull(): void
    {
        $requiredPackageWithoutVersions = new RequiredPackageWithAvailableVersions(
            new RequiredPackage('foo/bar', '^10.5', false),
            []
        );

        $this->assertNull($requiredPackageWithoutVersions->getLatestVersion());
    }
}
