doctrine-timestamp
==================

Implementation of a timestamp type for the Doctrine DBAL

This library maps the [DateTime](http://www.php.net/manual/fr/class.datetime.php)
PHP class to a Unix timestamp (integer) in the database.

Installation
============

Just add this to your composer.json

```json
{
  "require": {
    "mmerian/doctrine-timestamp": "dev-master"
  }
}
```

Then, when bootstraping your doctrine connection :

```php
Type::addType('money', 'DoctrineTimestamp\DBAL\Types\Timestamp');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('Timestamp', 'timestamp');
```
