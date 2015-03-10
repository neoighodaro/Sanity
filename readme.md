# PHP Array Sanitizer

[![Build Status](https://travis-ci.org/CreativityKills/Sanity.svg?branch=master)](https://travis-ci.org/CreativityKills/Sanity)

This package is a Sanitizer for PHP arrays. It's best used to sanitize request inputs like input from `$_POST` and/or `$_GET`.

## Usage 

### Step 1: Install via Composer

```
composer require 'creativitykills/sanity'
```

### Step 2: Add the Service Provider

Open `config/app.php` and, to your "providers" array at the bottom, add:

```
"CreativityKills\Sanity\SanityServiceProvider"
```

### Sanitize Requests

Out of the box you can start running requests through the sanitizer.

```php
<?php

// Possibly input from $_POST or $_GET global array
$someArray = ['name' => ' JOHN DOE ', 'email' => ' JOHN@DOE.COM '];

// Rules to validate against
$sanitizerRules = ['name' => 'ucwords|trim', 'email' => 'strtolower|trim'];

$sanitizer = new \CreativityKills\Sanity\Sanitizer;

$someArray = $sanitizer->sanitize($someArray, $sanitizerRules);

// array(
//    'name'  => 'John Doe',
//    'email' => 'john@doe.com'
// )
var_dump($someArray);
```

### Custom Sanitizers

The sweet aspect of Sanity is extending the Sanity class. You can create a custom extension of the class.

```php
<?php

use CreativityKills\Sanity\Sanitizer;

class UserSanitizer extends Sanitizer {
    
    protected $rules = [
        'name'  => 'ucwords|trim|remove_excess_white_spaces',
        'email' => 'strtolower|trim'
    ];
    
    public function sanitizeRemoveExcessWhiteSpaces($value)
    {
        return preg_replace('/\s+/', ' ', $value)
    }
}
```

> Notice the custom `remove_excess_white_spaces` sanitizer is called from the method `sanitizeRemoveExcessWhiteSpaces`. All snake cased sanitizer rules are converted to camel cases.
    
Now we can call our custom sanitizer from our application like so:

```php
<?php

// Possibly input from $_POST or $_GET global array
$someArray = ['name' => ' JOHN   DOE ', 'email' => ' JOHN@DOE.COM '];
                
$someArray = (new UserSanitizer)->sanitize($someArray);

// array(
//    'name'  => 'John Doe',
//    'email' => 'john@doe.com'
// )
var_dump($someArray);
```    


    