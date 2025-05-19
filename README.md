# DevThoughts

A collection of poignant quotes and funny sayings related to programming.

## Installation

`composer require theodorejb/dev-thoughts`

Or you can simply copy the JSON file into your project and use it with the language of your choice.

Note: thoughts may contain `<em>` tags indicating italicized words.

## Usage

Create a new `theodorejb\DevThoughts\DevThoughts` instance. Then use one of the following methods:

### `getAllThoughts()`

Parses `dev_thoughts.json` and returns the quotes as a list of `Thought` objects.

### `getThought(int $index)`

Returns the `Thought` object at the specified index.
If `$index` is greater than the length of the array, it will wrap around to the beginning rather than causing an error.

### `getDailyThought()`

Returns a different `Thought` object each day of the year.

The `Thought` object has the following public properties:

| Type                 | Property       |
|----------------------|----------------|
| `string`             | `text`         |
| `string`             | `author`       |
| `string`             | `reference`    |

## Author

Theodore Brown  
<https://theodorejb.me>
