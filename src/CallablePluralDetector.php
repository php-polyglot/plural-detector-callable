<?php

declare(strict_types=1);

namespace Polyglot\CallablePluralDetector;

use Polyglot\Contract\PluralDetector\PluralDetector;
use Polyglot\Number\Number;

final class CallablePluralDetector implements PluralDetector
{
    /**
     * @var callable(mixed): string
     */
    private $detector;
    private iterable $allowedCategories;

    /**
     * @param callable(mixed): string $detector
     */
    public function __construct(callable $detector, iterable $allowedCategories)
    {
        $this->detector = $detector;
        $this->allowedCategories = $allowedCategories;
    }

    /**
     * @inheritDoc
     */
    public function detect($number): string
    {
        return ($this->detector)(Number::create($number));
    }

    /**
     * @inheritDoc
     */
    public function getAllowedCategories(): iterable
    {
        return $this->allowedCategories;
    }
}
