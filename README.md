# DevThoughts

A collection of poignant quotes and funny sayings related to programming,
which can be easily inserted and featured from a database.

## Installation

`composer require theodorejb/dev-thoughts`

## Usage without database

Call `DevThoughts::getDefaultThoughts()`.
This returns a list of `Thought` objects for of all the quotes in the default JSON file, to be used however you want.

Or you can simply copy the JSON file into your project and use it with the language of your choice.

Note: thoughts may contain `<em>` tags indicating italicized words.

## Usage with database

Create a `DevThoughts` instance, passing it a [PeachySQL](https://github.com/theodorejb/peachy-sql/)
object for your database:

```php
use theodorejb\DevThoughts\DevThoughts;

$db = new PeachySQL\Mysql($mysqlConn);
$devThoughts = new DevThoughts($db);
```

The `DevThoughts` constructor takes an optional second parameter for the table name.
This allows you to name the table something other than `dev_thoughts` in your database if needed.

Instance methods:

### `insertDefaultThoughts()`

Call this method once after installing or updating the library to create the `dev_thoughts`
table if it doesn't exist and insert any missing default thoughts.

### `getFeaturedThought()`

Returns a `Thought` object for the current featured thought.

An optional integer parameter can be passed to choose how long a thought is featured (in seconds).
It defaults to `86400` (24 hours).

The `Thought` object has the following public properties:

| Type                 | Property       |
|----------------------|----------------|
| `int`                | `id`           |
| `string`             | `text`         |
| `string`             | `author`       |
| `string`             | `reference`    |
| `?DateTimeImmutable` | `lastFeatured` |


## Author

Theodore Brown  
<https://theodorejb.me>
