<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

class RequiredPackages
{
    /** @var RequiredPackage[] */
    private $requirements;

    public function __construct(array $requirements = [])
    {
        $this->requirements = $requirements;
    }

    public function addRequirement(RequiredPackage $requirement): void
    {
        $this->requirements[] = $requirement;
    }

    /**
     * @return RequiredPackage[]
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public static function fromComposerJson(string $composerJson): RequiredPackages
    {
        $requirements = new RequiredPackages();
        foreach (self::parseRequireNodes($composerJson) as $key => $requireNode) {
            $isDev = $key === 'require-dev';
            self::fulfillFromRequireNode($requirements, $requireNode, $isDev);
        }
        return $requirements;
    }

    private static function fulfillFromRequireNode(
        RequiredPackages $requirements,
        array $requireNode,
        bool $isDev
    ): void {
        foreach ($requireNode as $packageName => $version) {
            if (!strpos($packageName, '/')) {
                continue;
            }
            $requirements->addRequirement(new RequiredPackage($packageName, $version, $isDev));
        }
    }

    private static function parseRequireNodes(string $composerJson): array
    {
        $composerJsonDecoded = json_decode($composerJson, true);
        $defaults = [
            'require' => [],
            'require-dev' => [],
        ];

        return array_intersect_key(array_merge($defaults, $composerJsonDecoded), $defaults);
    }
}
