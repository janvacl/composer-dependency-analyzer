<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ComposerJsonProvider
{
    private const GITHUB_RAW_URL = 'https://raw.githubusercontent.com/';

    /** @var Client */
    private $client;

    /** @var string */
    private $composerFileUri;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setGithubPackage(string $packageName, string $branch): void
    {
        $this->composerFileUri = self::GITHUB_RAW_URL . $packageName . '/' . $branch . '/composer.json';
    }

    public function getFile(): string
    {
        if ($this->composerFileUri) {
            return $this->getFileFromRemote();
        }
        return $this->getFileFromFileSystem();
    }

    private function getFileFromFileSystem(): string
    {
        $filePath = __DIR__ . '/../input/composer.json';
        if (!file_exists($filePath)) {
            throw new Exception();
        }
        $fh = fopen($filePath, 'r');
        if (!$fh) {
            throw new Exception();
        }
        $size = filesize($filePath);
        if (!$size) {
            throw new Exception();
        }
        $json = fread($fh, $size);
        if (!$json) {
            throw new Exception();
        }
        return $json;
    }

    private function getFileFromRemote(): string
    {
        $response = $this->client->get($this->composerFileUri);

        // @TODO - replace 200 by constant
        if ($response->getStatusCode() !== 200) {
            throw new BadRequestHttpException();
        }
        return $response->getBody()->getContents();
    }
}
