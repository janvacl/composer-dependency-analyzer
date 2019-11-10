<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

class RequiredPackageWithAvailableVersions
{
    /** @var RequiredPackage */
    private $requiredPackage;

    /** @var Version[] */
    private $availableVersions;

    /**
     * @param RequiredPackage $requiredPackage
     * @param Version[] $availableVersions
     */
    public function __construct(
        RequiredPackage $requiredPackage,
        array $availableVersions
    ) {
        $this->requiredPackage = $requiredPackage;
        uksort($availableVersions, 'version_compare');
        $this->availableVersions = $availableVersions;
    }

    public function getRequiredPackage(): RequiredPackage
    {
        return $this->requiredPackage;
    }

    public function getLatestVersion(): ?Version
    {
        return $this->availableVersions[array_key_last($this->availableVersions)];
    }
}
