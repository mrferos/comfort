# Comfort
![Travis-CI](https://api.travis-ci.org/mrferos/comfort.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mrferos/comfort/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mrferos/comfort/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/mrferos/comfort/badge.svg?branch=master)](https://coveralls.io/github/mrferos/comfort?branch=master)<br />

## Easy & Flexible validation for your data.

Comfort is an easy to use validation lib styled after [Joi](https://github.com/hapijs/joi), an excellent (and so far, better featured) object validation lib.

## Example
```php
$json = <<<JSON
{
    "first_name": "Andres",
    "last_name": "Galindo",
    "email": "test@test.com",
    "address": {
        "street_addr1": "123 Joi lane",
        "city": "Miami",
        "state": "FL"
    }
}
JSON;

$stateArray = array(
    'AL'=>'ALABAMA',
    'AK'=>'ALASKA',
    'AS'=>'AMERICAN SAMOA',
    'AZ'=>'ARIZONA',
    'AR'=>'ARKANSAS',
    'CA'=>'CALIFORNIA',
    'CO'=>'COLORADO',
    'CT'=>'CONNECTICUT',
    'DE'=>'DELAWARE',
    'DC'=>'DISTRICT OF COLUMBIA',
    'FM'=>'FEDERATED STATES OF MICRONESIA',
    'FL'=>'FLORIDA',
    /** taken out for brevity */
);

$registrationSchema = cmf()->json()->keys([
    "first_name" => cmf()->string()->required()->alpha()->min(1),
    "last_name"  => cmf()->string()->required()->alpha()->min(1),
    "email" => cmf()->string()->email(),
    "address"    => cmf()->array()->keys([
        "street_addr1" => cmf()->string()->required()->min(2),
        "street_addr2" => cmf()->string()->optional()->min(2),
        "city"         => cmf()->string()->required()->min(2),
        "state"        => cmf()->string()->alternatives([
            [
                'is' => cmf()->string()->length(2),
                'then' => cmf()->string()->anyOf(array_keys($stateArray)),
                'else' => cmf()->string()->anyOf(array_values($stateArray))
            ],
        ])
    ])->required()
]);
```

The schema defined above validates the following:

- first_name, last_name:
    - must be a string
    - is required
    - contain only alpha (a-z) characters
    - a minimum of 1 character
- email:
    - must be a string
    - must be an email
    - is _optional_
- address:
    - must be an array
    - must have the following keys (also with their own validation)
        - street_addr1, city:
            - must be a string
            - is required
            - contains at least 2 characters
        - street_addr2:
            - must be a string
            - contains at least 2 characters
            - is _optional_ (everything is optional unless made explicitly `required()`)
        - state:
            - _if_ the string is exactly 2 characters, then:
                - the string may be any of the state short codes (e.g. FL)
            - _else_ the string must be any of the state long forms (e.g. Florida)

## Usage

Using Comfort entails defining your schema like we do above which returns a callable to be used like so:
```php
$data = $registrationSchema($jsonData);
```
This will do two things:

- Run the validation stack
- Return the data as an array (see more on this below) or a `ValidationError` instance

Only the `json()` validator currently takes the liberty of automatically transforming your data, the other validators
will currently return data in the type it was passed to.

See more at our API reference: [API.md](API.md)

## Things to do:

- [ ] Add tests
- [ ] Refactor some code to not be nasty
- [ ] Be original enough to not ripoff all of Joi's documentation format.
