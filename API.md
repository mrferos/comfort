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
    - [`date.format()`](#dateformatformat)
    - [`date.max()`](#datemaxmax)
    - [`date.min()`](#dateminmin)
    - [`date.timestamp()`](#datetimestamp)
- [`array`](#array)
    - [`array.keys($definition)`](#arraydefinition)
    - [`array.min($min)`](#arrayminmin)
    - [`array.max($max)`](#arraymaxmax)
    - [`array.length($length)`](#arraylengthlenght)
    - [`array.unique()`](#arrayunique)

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
