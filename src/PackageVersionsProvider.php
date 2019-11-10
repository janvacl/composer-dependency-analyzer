<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PackageVersionsProvider
{
    private const PACKAGIST_URI_PATTERN = 'https://repo.packagist.org/p/%s.json';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getVersionListByRepository(string $packageName): array
    {
        $availableVersions = array_keys($this->getPackageVersionsDataDecoded($packageName));
        return $this->getFilteredVersions($availableVersions);
    }

    private function getPackageDataDecoded(string $packageName): array
    {
        $response = $this->client->get(sprintf(self::PACKAGIST_URI_PATTERN, $packageName));
        if ($response->getStatusCode() !== 200) {
            throw new BadRequestHttpException();
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getPackageVersionsDataDecoded(string $packageName): array
    {
        return $this->getPackageDataDecoded($packageName)['packages'][$packageName];
    }

    /**
     * @param array $versions
     *
     * @return Version[]
     */
    private function getFilteredVersions(array $versions): array
    {
        $filteredVersions = [];

        foreach ($versions as $version) {
            if (!preg_match('~^(v)?(\d+)(\.(\d+))(\.(\d+))?$~', $version, $matches)) {
                continue;
            }

            $filteredVersions[ltrim($version, 'v')] = new Version(
                $matches[1] === 'v',
                (int) $matches[2],
                $matches[4] ? (int) $matches[4] : 0,
                $matches[6] ? (int) $matches[6] : 0
            );
        }

        return $filteredVersions;
    }
}
