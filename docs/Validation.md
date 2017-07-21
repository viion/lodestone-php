# Validation

To ensure that every api call returns the requested data, the api 
validates every attribute. An example for a validation would be:

```php
$this->validator
  ->check($id, 'ID')
  ->isInitialized()
  ->isInteger()
  ->validate();
```

When a validation fails, a `\\Lodestone\\Validator\\ValidationException` 
will be thrown. The thrown exception contains an explanation which validation 
failed and the name of the object which failed.

## Working with exceptions

The normal behaviour for working with exception is to catch an exception and provide
a fallback solution (e.g.: A message for the user or some more complex stuff).

Below is a simple example for a try-catch statement:

```php

$api = new \Lodestone\Api();

try {
  $char = $api->getCharacter(<myCharId>);
  ... do something with $char ...
} catch(\Lodestone\Validator\ValidationException $vex) {
  ... provide some fallback solution ...
  echo $vex->getMessage();
}
```

## Extend the Validation

If the provided Validator does not contain a needed validation, it is possible to 
extend the `\\Lodestone\\Validator\\BaseValidator Class` or one of its child classes.

Validators have access after calling `check(x,y)` to:

- `$this->object` - The object being checked/tested.
- `$this->name` - A friendly name for the object.

an Example for this would look like:

```php
use \Lodestone\Validator\BaseValidator;
use \Lodestone\Validator\ValidationException

class MyValidator extends BaseValidator 
{
  public function needsToStartWithA() 
  {
    if (!preg_match('/^[A|a]/', $this->object)) {
      $errors[] = throw new ValidationException($this->name . ' needs to start with an A or an a'.);
    }

    return $this;
  }
}
```

You can the use your validator like any other validator:

```php

$validator = new MyValidator();
$validator
  ->check('A String', 'Name of A String')
  ->needsToStartWithA()
  ->validate();
```

If you need more distinguishable Exceptions you can also extend the 
`\\Lodestone\\Vlaidator\\ValidationException class` to provide more informations or 
to react in different ways for different validation exceptions.
