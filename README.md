doctrine-timestamp
==================

Implementation of a timestamp type for the Doctrine DBAL

This library maps the [DateTime](http://www.php.net/manual/en/class.datetime.php)
PHP class to a Unix timestamp (integer) in the database.

Installation
============

Just add this to your composer.json

```json
{
  "require": {
    "chesscom/doctrine-timestamp": "^1"
  }
}
```

Then, when bootstraping your doctrine connection :

```php
Type::addType('timestamp', 'DoctrineTimestamp\DBAL\Types\Timestamp');
$conn->getDatabasePlatform()->registerDoctrineTypeMapping('Timestamp', 'timestamp');
```
