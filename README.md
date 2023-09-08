# polyglot/plural-detector-callable

> A simple [polyglot](https://packagist.org/packages/polyglot/) callable plural detector.

# Install

```shell
composer require polyglot/plural-detector-callable:^1.0
```

# Using

```php
<?php

$callable = function (Number $number): string {
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
$allowedCategories = [];

$registry = new \Polyglot\CallablePluralDetector\CallablePluralDetector($callable);
$en = $registry->get('en_US'); // or $registry->get('en')
$en->getAllowedCategories(); // returns ["one", "other"]
$en->detect(1); // returns "one"
$en->detect(2); // returns "other"

$ar = $registry->get('ar');
$en->getAllowedCategories(); // returns ["zero", "one", "two", "few", "many", "other"]
$en->detect(0); // returns "zero"
$en->detect(1); // returns "one"
$en->detect(2); // returns "two"
$en->detect(rand(3, 10) + 100 * rand(0, 100)); // returns "few"
$en->detect(rand(11, 99) + 100 * rand(0, 100)); // returns "many"
$en->detect(rand(10, 99) / 10); // returns "other"

$registry->get('unknown'); // throws \Polyglot\Contract\PluralDetectorRegistry\Exception\LocaleNotSupported 
```

