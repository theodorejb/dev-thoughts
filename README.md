# DevThoughts

A collection of poignant quotes and funny sayings related to programming,
which can be easily inserted and featured from a database.

## Installation

`composer require theodorejb/dev-thoughts`

Then add a table to your database to contain the dev thoughts:

**Mysql:**

```sql
CREATE TABLE dev_thoughts (
    thought_id int unsigned primary key auto_increment,
    thought varchar(500) not null unique,
    author varchar(50) not null,
    reference varchar(100) not null,
    last_featured datetime
);
```

**SQL Server:**

```sql
CREATE TABLE dev_thoughts (
    thought_id int primary key identity,
    thought varchar(500) not null unique,
    author varchar(50) not null,
    reference varchar(100) not null,
    last_featured datetime2(0)
);
```

## Usage

Create a `DevThoughts` instance, passing it a [PeachySQL](https://github.com/theodorejb/peachy-sql/)
object for your database:

```php
use theodorejb\DevThoughts\DevThoughts;

$db = new PeachySQL\Mysql($mysqlConn);
$devThoughts = new DevThoughts($db);

// if you haven't yet populated the table, call this method to do so:
$devThoughts->insertDefaultThoughts();

$featured = $devThoughts->getFeaturedThought();

echo $featured->text . "\n";

if ($featured->author) {
    echo " - {$featured->author}";
    
    if ($featured->reference) {
        echo ", {$featured->reference}";
    }
    
    echo "\n";
}
```

### Notes
The `DevThoughts` constructor takes an optional second parameter for the table name.
This allows you to name the table something other than `dev_thoughts` in your database if needed.

The `insertDefaultThoughts()` method should only be called once after installing or updating the library.

The `getFeaturedThought()` method takes an optional integer parameter to choose how long a thought
is featured (in seconds). It defaults to `86400` (24 hours).

## Author

Theodore Brown  
<https://theodorejb.me>
