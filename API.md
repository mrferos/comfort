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
