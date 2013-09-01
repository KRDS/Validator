Validator
=========

Validator is a nifty form validation library with a small footprint.

Brought to you by the fine folks at [KRDS](http://www.krds.com/).


##### Table of contents

  * [Validating a form](#validating-a-form)
  
    * [Setting validation rules](#setting-validation-rules)
    
    * [For a single field](#for-a-single-field)
    * [For multiple fields](#for-multiple-fields)
    
      * ['and' rule](#and-rule)
      * ['or' rule](#or-rule)
      * [Break](#break)
    
    * [Running the validation](#running-the-validation)
    * [Pushing errors manually](#pushing-errors-manually)
    * [Full example](#full-example)
    
  * [Field related functions](#field-related-functions)
  
    * [Getting the error](#getting-the-error)
    * [Pushing an error manually](#pushing-an-error-manually)
    * [Clearing the error](#clearing-the-error)
    * [Knowing whether the validation passed](#knowing-whether-the-validation-passed)
    
  * [Validation rules](#validation-rules)
  
    * [Built-in rules](#built-in-rules)
  
      * [Validation_Alphanumeric](#validation_alphanumeric)
      * [Validation_Boolean](#validation_boolean)
      * [Validation_Date](#validation_date)
      * [Validation_DateHour](#validation_datehour)
      * [Validation_DependsOn](#validation_dependson)
      * [Validation_Digits](#validation_digits)
      * [Validation_Email](#validation_email)
      * [Validation_Empty](#validation_empty)
      * [Validation_Float](#validation_float)
      * [Validation_GreaterThan](#validation_greaterthan)
      * [Validation_InArray](#validation_inarray)
      * [Validation_LengthGreaterThan](#validation_lenghtgreaterthan)
      * [Validation_LengthLowerThan](#validation_lengthlowerthan)
      * [Validation_LowerThan](#validation_lowerthan)
      * [Validation_NotEmpty](#validation_notempty)
      * [Validation_Required](#validation_required)
      * [Validation_Unchanged](#validation_unchanged)
      
    * [Custom rules](#custom-rules)
    
      * [Closure](#closure)
      * [Custom function](#custom-function)
      * [PHP function](#php-function)

========================================================================


## Validating a form

To validate a form, you’ll have define its validation rules first, then run the validation.

First, instantiate a `Validator` object:

```php
$validator = new Validator;
```

The methods below can be chained to `$validator`.

### Setting validation rules

A validation rule checks a field against an expected type, format, value, …<br>
The list of rules available is given in [Rules](#rules) section below.

#### For a single field

First, choose the field you want to apply the rules on:

```php
$validator->field('field_name');
```

  * Then, add one or more rules:

  ```php
  $validator->rule(new Validation_Required);
              ->rule(new Validation_Email);
  ```

  * Or prepend them (will be pushed at the top of the validation stack):

    ```php
    $validator->ruleBefore(new Validation_Required);
    ```


#### For multiple fields

A single rule can be applied to multiple fields.<br>
It is useful to avoid repeating the same rule, for example in the case of several fields are required.

These “global rules” are applied to each field until calling `breakRule` or `breakRules` functions.<br>

There are two type of rules: 'and' and 'or'.

##### 'and' rule

An 'and' rule is applied to each field added after declaring it.

```
$validator->ruleUntilBreak($rule);
```

##### 'or' rule

An 'or' rule will pass if at least one of the fields added after declaring it passes it.<br>
It is typically used with `Validation_Required`, when at least one of X fields must be filled (for example, at least the user landline or mobile phone number).

```
$validator->ruleUntilBreak($rule, Validator::OPERATOR_OR, $message);
```

`$message` is a required property. It is the user-facing message that will be returned if none of the fields pass the rule.


##### Break

To break the latest global rule declared:

```
$validator->breakRule();
```

To break all global rules declared till now:

```
$validator->breakRules();
```


### Running the validation

To run the validation:

```php
$validator->run($fields);
// returns `true` if the validation passed, `false` otherwise.
```

`$fields` is an a list of `field name` => `value`, such as `$_POST`.<br>
This method will return `true` if the validation passed, `false` otherwise.


To get the errors messages:

```php
$validator->getErrors();

/* Returns an array:

[
  "field_name" => "Error message",
  "field_name" => "Error message",
  "field_name" => "Error message",
  "_common" => [
    "Common error message",
    "Common error message"
  ]
]

_common errors are errors that apply to the whole form.
*/
```


### Full example

The following examples validates a simple user information form.

```php
$validator = new Validator;

$validator->ruleuntilbreak(new Validation_Required)

          ->ruleuntilbreak(new Validation_LengthGreaterThan(3))
          ->field('firstname')
          ->field('lastname')
          ->breakRule()

          ->field('email')
          ->rule(new Validation_Email)

          ->field('dob')
          ->rule(new Validation_Dob)

          ->breakRules();

if( ! $validator->run($_POST))
  print_r($validator->getErrors());
```

## Field related functions


### Getting the error

Returns the error for a field as a `FieldError` object.<br>
Use `$error->getMessage()` to get the error message.

If no error, returns `null`.

```php
$error	=	$validator->field('field_name')->getError();
echo $error->getMessage();
```

### Pushing an error manually

In some case, you might need to push some error messages manually.

  * Push an error for a field:
  
    ```php
    $validator->field('field_name')->error('MyField error message');
    ```

  * Push an error related to the whole form:
    ```php
    $validator->globalError('Global error message');
    ```

### Clearing the error

Clear the error of a field and set it as if the validation passed.

```php
$validator->field('field_name')->clearError();
```

### Knowing whether the validation passed

To know whether a field has been given and passed the validation.

```php
$validator->field('field_name')->ok();

// return `true` if the field is NOT missing and the validation passed, `false` otherwise.
```


## Validation rules

Validation rules are executed only if the field is present (that means, given on the array, it might be empty).


  * If a field equals `null`, it is considered **missing**;
  * if a field equals "null" (as a string), it is considered **empty**.<br>
    This is typically useful if you are using any kind of API console which removes the empty fields before making the call.


### Built-in rules

#### Validation_Alphanumeric

Validates an alphanumeric string.

#### Validation_Boolean

Validates a boolean value.

Can be: `true` / `false` / 1 / 0 / "1" / "0" / ""

#### Validation_Date

Validates a date in YYYY-MM-DD HH:MM format.

#### Validation_DateHour

Validates a date + hour in YYYY-MM-DD HH:MM format.

#### Validation_DependsOn

This validation rule has to be placed at the top of the rules declaration for a field.
It will block the other validation rules in the stack if another field is missing of invalid.

This validation rule won’t generate an error message by default in case of failure.
It can be shown anyway if `$displayable` param of `Validator::run is set to `true`.

```php
new Validation_DependsOn($field)
```

  * `$field` is the name of the field it depends on

#### Validation_Digits

Validates a field made of digits only.

Can be either of type `string` or `int`.

#### Validation_Email

Validates an email address.

#### Validation_Empty

Validates a required but empty field.

#### Validation_Float

Validates a float value.

Can be either of type `string` or `int`.<br>
The decimal separator can be either a point (.) or a comma (,).

#### Validation_GreaterThan

Validates a value greater than another value.
Can validate numbers or dates.

```php
new Validation_GreaterThan($number[, $type])
```

   * `$number` is the number that should be the lowest
   * `$type` can be set to `Validation_GreaterThan::TYPE_DATE` to compare dates

#### Validation_InArray

Validates a value part of a pre-defined list.

```php
new Validation_InArray($list[, $ignore_case = false])
```

  * `$list`: List of values the validated value should belong to
  * `$ignore_case`: If true, the case will be ignored for searching through the array


#### Validation_LengthGreaterThan

Validates a length greater than a given length.

```php
new Validation_LengthGreaterThan($length)
```

  * `$length`: Length for which the field must be greater than.
  

#### Validation_LengthLowerThan

Validates a length lower than a given length.

```php
new Validation_LengthLowerThan($length)
```

  * `$length`: Length for which the field must be lower than.


#### Validation_LowerThan

Validates a value lower than another value.
Can validate numbers or dates.

```php
new Validation_LowerThan($number[, $type = Validation_GreaterThan::TYPE_NUMBER])
```

  * `$number` is the number that should be the greatest
  * `$type` can be set to Validation_GreaterThan::TYPE_DATE` to compare dates

#### Validation_NotEmpty

Validates a required and non-empty field.


#### Validation_Required

Validates a required field.


#### Validation_Unchanged

Validates that the value has not been changed.

```php
new Validation_Unchanged($reference)
```

  * `$reference` is the reference value to be checked against
  
### Custom rules

You can give custom rules to validate a field.

#### Closure

```php
$validator->rule(function($value, $values, $validator) {

  /**
   * Remove the parameters you won’t use
   * (typically, only $value is interesting)
   **/
 
  if(isUsernameTaken($value))
    throw new Exception('This username is already taken. Please choose another one.');

});
```

  * `$value` is the value checked
  * `$values`is the list of all the values
  * `$validator` is the `Validator` object

#### Custom function

Public static function:

```php
$validator->rule('CustomValidation::ruleName');

// Will call: CustomValidation::ruleName($value)
```

#### PHP function

A PHP function that takes a single parameter can be given.<br>
The validation will fail if the function returns a “falsy” value (empty, 0, false, null, …).

```php
$validator->rule('is_scalar');

// Will call: is_scalar($value)
```