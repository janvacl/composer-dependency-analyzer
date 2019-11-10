<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

class RequiredPackage
{
    /** @var Package */
    private $package;

    /** @var string */
    private $version;

    /** @var bool */
    private $isDevOnly;

    public function __construct(string $packageName, string $version, bool $isDevOnly)
    {
        $this->package = Package::fromPackageName($packageName);
        $this->version = $version;
        $this->isDevOnly = $isDevOnly;
    }

    public function getPackage(): Package
    {
        return $this->package;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function isDevOnly(): bool
    {
        return $this->isDevOnly;
    }
}
