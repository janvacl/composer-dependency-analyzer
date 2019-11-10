<?php

declare(strict_types=1);

namespace S3tezsky\DependencyAnalyzer;

class Version
{
    /** @var bool */
    private $hasLeadingV;

    /** @var int */
    private $major;

    /** @var int */
    private $minor;

    /** @var int */
    private $patch;

    public function __construct(bool $hasLeadingV, int $major, int $minor, int $patch)
    {
        $this->hasLeadingV = $hasLeadingV;
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    public function getVersionTag(): string
    {
        return sprintf(
            '%s%d.%d.%d',
            $this->hasLeadingV ? 'v' : '',
            $this->major,
            $this->minor,
            $this->patch
        );
    }
}
