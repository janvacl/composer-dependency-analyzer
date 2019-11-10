<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

class Package
{
    /** @var string */
    private $vendor;

    /** @var string */
    private $repository;

    public function __construct(string $vendor, string $repository)
    {
        $this->vendor = $vendor;
        $this->repository = $repository;
    }

    public static function fromPackageName(string $packageName): Package
    {
        $slicedPackageNames = explode('/', $packageName, 2);
        return new Package($slicedPackageNames[0], $slicedPackageNames[1]);
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function getName(): string
    {
        return $this->getVendor() . '/' . $this->getRepository();
    }
}
