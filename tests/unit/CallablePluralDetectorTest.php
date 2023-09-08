<?php

declare(strict_types=1);

namespace TestUnits\Polyglot\CallablePluralDetector;

use PHPUnit\Framework\TestCase;
use Polyglot\CallablePluralDetector\CallablePluralDetector;
use Polyglot\Contract\PluralDetector\PluralCategory;
use Polyglot\Number\Number;

final class CallablePluralDetectorTest extends TestCase
{
    /**
     * @param mixed $number
     * @param string $expected
     * @return void
     * @dataProvider provideDetect
     */
    public function testDetect($number, string $expected): void
    {
        $fn = function (Number $number): string {
            $n = $number->number();
            if ($n == 0) {
                return PluralCategory::ZERO;
            }
            if ($n == 1) {
                return PluralCategory::ONE;
            }
            if ($n == 2) {
                return PluralCategory::TWO;
            }
            if ($n == 3) {
                return PluralCategory::FEW;
            }
            if ($n == 4) {
                return PluralCategory::MANY;
            }
            return PluralCategory::OTHER;
        };

        $allowedCategories = [
            PluralCategory::ZERO,
            PluralCategory::ONE,
            PluralCategory::TWO,
            PluralCategory::FEW,
            PluralCategory::MANY,
            PluralCategory::OTHER,
        ];
        $detector = new CallablePluralDetector($fn, $allowedCategories);

        $this->assertSame($expected, $detector->detect($number));
    }

    /**
     * @return void
     */
    public function testGetAllowedCategories(): void
    {
        $fn = function (): string {
            return PluralCategory::OTHER;
        };
        $expectedAllowedCategories = [
            PluralCategory::OTHER,
        ];

        $detector = new CallablePluralDetector($fn, $expectedAllowedCategories);

        $actualAllowedCategories = $detector->getAllowedCategories();
        $quantity = 0;
        foreach ($actualAllowedCategories as $allowedCategory) {
            $this->assertTrue(in_array($allowedCategory, $expectedAllowedCategories));
            $quantity++;
        }
        $this->assertSame(count($expectedAllowedCategories), $quantity);
    }

    public function provideDetect(): iterable
    {
        yield ['0', PluralCategory::ZERO];
        yield ['.0', PluralCategory::ZERO];
        yield ['0.0', PluralCategory::ZERO];
        yield [0, PluralCategory::ZERO];
        yield [0.0, PluralCategory::ZERO];
        yield ['1', PluralCategory::ONE];
        yield ['1.0', PluralCategory::ONE];
        yield [1, PluralCategory::ONE];
        yield [1.0, PluralCategory::ONE];
        yield ['2', PluralCategory::TWO];
        yield ['2.0', PluralCategory::TWO];
        yield [2, PluralCategory::TWO];
        yield [2.0, PluralCategory::TWO];
        yield ['3', PluralCategory::FEW];
        yield ['3.0', PluralCategory::FEW];
        yield [3, PluralCategory::FEW];
        yield [3.0, PluralCategory::FEW];
        yield ['04', PluralCategory::MANY];
        yield ['4.00', PluralCategory::MANY];
        yield [4, PluralCategory::MANY];
        yield [4.0, PluralCategory::MANY];
        yield ['5', PluralCategory::OTHER];
        yield ['0.1', PluralCategory::OTHER];
        yield [.1, PluralCategory::OTHER];
        yield [6.0, PluralCategory::OTHER];
        yield ['no number', PluralCategory::ZERO];
    }
}
