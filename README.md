# Comfort

Ensure your data conforms (a shameless ripoff of [Joi](https://github.com/hapijs/joi)).

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

$underwritingSchema = cmf()->json()->keys([
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

$data = $underwritingSchema($json);
var_dump($data);
```

## Things to do:

- [ ] Add more to README
- [ ] Add comments
- [ ] Add tests
- [ ] Refactor some code to not be nasty
