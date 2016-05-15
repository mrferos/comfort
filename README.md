# Comfort
![Travis-CI](https://api.travis-ci.org/mrferos/comfort.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mrferos/comfort/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mrferos/comfort/?branch=master)
PHP style object schema validation, [Joi](https://github.com/hapijs/joi)-style.

# Example
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
    ])
]);

$data = $registrationSchema($json);
var_dump($data);
```
# API

See API reference: [API.md](API.md)

## Things to do:

- [ ] Add more to README
- [ ] Add comments
- [ ] Add tests
- [ ] Refactor some code to not be nasty
- [ ] Be original enough to not ripoff all of Joi's documentation format.