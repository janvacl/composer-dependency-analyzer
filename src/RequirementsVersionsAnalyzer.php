<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

use Throwable;

class RequirementsVersionsAnalyzer
{
    /** @var ComposerJsonProvider */
    private $composerJsonProvider;

    /** @var PackageVersionsProvider */
    private $packagistVersionsProvider;

    public function __construct(
        ComposerJsonProvider $composerJsonProvider,
        PackageVersionsProvider $packagistVersionsProvider
    ) {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packagistVersionsProvider = $packagistVersionsProvider;
    }

    /**
     * @param string|null $packageName
     * @param string|null $branch
     *
     * @return RequiredPackageWithAvailableVersions[]
     */
    public function getRequirementsWithAvailableVersions(?string $packageName, ?string $branch): array
    {
        if ($packageName) {
            $branch = $branch ?? 'master';
            $this->composerJsonProvider->setGithubPackage($packageName, $branch);
        }
        return $this->resolveRequirementsAvailableVersions(
            RequiredPackages::fromComposerJson($this->composerJsonProvider->getFile())
        );
    }

    private function resolveRequirementsAvailableVersions(RequiredPackages $requirements): array
    {
        $dependencies = [];
        foreach ($requirements->getRequirements() as $requirement) {
            $packageName = $requirement->getPackage()->getName();
            $versions = $this->resolveRequirementAvailableVersions($packageName);
            $dependencies[$packageName] = new RequiredPackageWithAvailableVersions($requirement, $versions);
        }
        return $dependencies;
    }

    /**
     * @param string $packageName
     *
     * @return Version[]
     */
    private function resolveRequirementAvailableVersions(string $packageName): array
    {
        try {
            return $this->packagistVersionsProvider->getVersionListByRepository($packageName);
        } catch (Throwable $exception) {
            return [];
        }
    }
}
