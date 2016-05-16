# Comfort Validation

- [`string`](#string)
	- [`string.token()`](#stringtoken)
    - [`string.min($min)`](#stringminmin)
    - [`string.max($max)`](#stringmaxmax)
    - [`string.matches($regex)`](#stringmatchesregex)
    - [`string.length($length)`](#stringlengthlength)
    - [`string.alphanum()`](#stringalphanum)
    - [`string.alpha()`](#stringalpha)
    - [`string.email()`](#stringemail)
    - [`string.ip()`](#stringip)
    - [`string.uri($options)`](#stringurioptions)
    - [`string.replace($pattern, $replacement)`](#stringreplacepattern-replacement)
- [`date`](#date)
    - [`date.iso()`](#dateiso)
    - [`date.format($format)`](#dateformatformat)
    - [`date.max($max)`](#datemaxmax)
    - [`date.min($min)`](#dateminmin)
    - [`date.timestamp()`](#datetimestamp)
- [`array`](#array)
    - [`array.keys($definition)`](#arraydefinition)
    - [`array.min($min)`](#arrayminmin)
    - [`array.max($max)`](#arraymaxmax)
    - [`array.length($length)`](#arraylengthlenght)
    - [`array.unique()`](#arrayunique)
    - [`array.items()`](#arrayitems)
- [`number`](#number)
    - [`number.min($min)`](#numberminmin)
    - [`number.max($max)`](#numbermaxmax)
    - [`number.precision($precision)`](#numberprecisionprecision)
    - [`number.isInt()`](#numberisint)
    - [`number.isFloat()`](#numberisfloat)
    - [`number.isNumber()`](#numberisnumber)

_Note: the following methods are inherited bty all validators_
#### required()
Validates the data element is not null
```php
$schema = cmf()->string()->required();
```
#### alternatives($alternatives)
Creates conditional validation or values
```php
$schema = cmf()->string()->alternatives([
    [
        'is' => cmf()->string()->length(2),
        'then' => cmf()->string()->anyOf($stateAbbrCodes),
        'else' => cmf()->string()->anyOf($stateLongCodes)
    ],
]);
```
#### anyOf($values)
Validates given value is part of provided `$values` array
```php
$schema = cmf()->string()->anyOf($stateAbbrCodes);
```

#### string()
Validates data is a string
```php
$schema = cmf()->string();
```
#### string.token()
Validate string contains only a character set matching `[a-zA-Z0-9_]`
```php
$schema = cmf()->string()->token();
```
#### string.min($min)
Validate string has more than `$min` characters
```php
$schema = cmf()->string()->min(4);
```
#### string.max($max)
Validate string has at most `$max` characters
```php
$schema = cmf()->string()->max(10);
```
#### string.matches($regex)
Validate string matches provided regular expression
```php
$schema = cmf()->string()->matches('/^\d+\w/');
```
#### string.length($length)
Validate string is exactly the specified length
```php
$schema = cmf()->string()->length(20);
```
#### string.alphanum()
Validate string is alphanumeric
```php
$schema = cmf()->string()->alphanum();
```
#### string.alpha()
Validate string contains only alpha characters
```php
$schema = cmf()->string()->alpha();
```
#### string.ip()
Validate string is a valid IPv4 address
```php
$schema = cmf()->string()->ip();
```
#### string.uri($options)
Validate string is a valid URI
```php
$schema = cmf()->string()->uri($options);
```
#### string.replace($pattern, $replacement)
Replace matched portion of string
```php
$schema = cmf()->string()->replace('/\d+/', 'foo');
```
#### date()
Validates data is a date
```php
$schema = cmf()->date();
```
#### date.iso()
Validates data confirms to ISO 8601 standard
```php
$schema = cmf()->date()->iso();
```
#### date.format($format)
Validates data confirms to provided format see [php docs](http://php.net/manual/en/function.date.php#format)
```php
$schema = cmf()->date()->format('Y-m-d');
```
#### date.max($max)
Validates given date is not past provided `$max`
```php
$schema = cmf()->date()->max('2016-01-01');
```
#### date.min($min)
Validates given date is not before provided `$min`
```php
$schema = cmf()->date()->min('2016-01-01');
```
#### date.timestamp()
Validates date is a unix timestamp
```php
$schema = cmf()->date()->timestamp();
```
#### array
Validates data is an array
```php
$schema = cmf()->array();
```
#### array.keys($definition)
Validates data is an array
```php
$schema = cmf()->array()->keys([
	'first_name' => cmf()->string()->min(5)
]);
```
#### array.min($min)
Validates array has atleast `$min` elements
```php
$schema = cmf()->array()->min(5);
```
#### array.max($max)
Validates array has at most `$max` elements
```php
$schema = cmf()->array()->max(20);
```
#### array.length($length)
Validates array has a specific amount of elements, $length
```php
$schema = cmf()->array()->length(100);
```
#### array.unique()
Validates data in array is unique (see [array_unique](http://php.net/manual/en/function.array-unique.php) for description of how _not_ unique elements are removed - result is then compared to original)
```php
$schema = cmf()->array();
```
### array.items()
Validates elements in array comply with a given schema
```php
$schema = cmf()->array()->items(
    cmf()->array()->keys([
        'name' => cmf()->string()->required()
    ])
);

/**
 * Will match an array such as:
 * [
 *     [
 *         'name' => 'John'
 *     ]
 * ];
**/
```
#### number()
Validate data is a number
```php
$schema = cmf()->number();
```
#### number.max($max)
Validate data is no more than `$max`
```php
$schema = cmf()->number()->max($max)
```
#### number.min($min)
Validate data is no less than `$min`
```php
$schema = cmf()->number()->min($min)
```
#### number.precision($precision)
Validate number's precision `$precision`
```php
$schema = cmf()->number()->precision($precision)
```
_Note: the is<int|float|number> method style that runs counter to the other validator's naming conventions is due to php7's reserves words_
#### number.isInt()
Validate number is an integer
```php
$schema = cmf()->number()->isInt()
```
#### number.isFloat()
Validate number is a float
```php
$schema = cmf()->number()->isFloat()
```
#### number.isNumber()
Validate number is a number
```php
$schema = cmf()->number()->isNumber()
```