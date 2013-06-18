MongoJson
=========

A small library that JSON encode an Mongo document that contains MongoDB-specific types (e.g. MongoDate).

If you just use json_encode(), you will get some PHP specific results that makes it very difficult to interface with other part of your program. This library attempts to implement the 'strict mode' and also the 'javascript (JSONP) mode' defined in http://docs.mongodb.org/manual/reference/mongodb-extended-json/

Installation using Composer
---------------------------

    php composer.phar install mongo-json

P.S. If you haven't already been using this great dependency manager for PHP, you can install it via:

    curl -sS https://getcomposer.org/installer | php


Usage
-----

### Strict Mode

```php
$doc = array("dt" => new MongoDate);
echo MongoJson::strict($doc);
```

will print:

    {"dt":{"$date":1371525158000}}

### Extended Mode

```php
$doc = array("dt" => new MongoDate);
echo MongoJson::extended($doc);
```

will print:

    {"dt":new Date(1371525158000)}

### Options
You can pass extra option that you can normally use with json_encode()

```php
$doc = array("_id" => new MongoId, "regex" => new MongoRegex('/^acme.*corp/i'));
echo MongoJson::extended($doc, JSON_PRETTY_PRINT); // will prettify the JSON string
```

will print:

    {
        "_id": {
            "$oid": "51bfcdd71ede01d61a000000"
        },
        "regex": /^acme.*corp/i
    }

License
-------
MIT


Contribute
----------
Feel free to fork and submit pull requests!
