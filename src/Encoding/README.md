# Encoding

Object-oriented character encoding detection/conversion

## Usage

Eeach encoding is represented by enum class instance. `Encoding` class extends
`MyCLabs\Enum\Enum` - please find more info in
[https://github.com/myclabs/php-enum](https://github.com/myclabs/php-enum)
package documentation.

```php
use KickAssSubtitles\Encoding\Encoding;

// create instance by passing encoding string
$utf8 = new Encoding('UTF-8');

// encoding is case insensitive
$utf8 = new Encoding('utf-8');

// you can also pass alias
$utf8 = new Encoding('utf8');

$utf8->getKey(); // UTF_8
$utf8->getValue(); // utf-8
$utf8->getName(); // UTF-8
$utf8->getAliases(); // ['utf8']
$utf8->isWindows(); // false
$utf8->hasAlias('UTF8'); // true
$utf8->asArray(); // ['key' => 'UTF_8', 'value' => 'utf-8', 'name' => 'UTF-8', 'aliases' => ['utf8'], 'is_windows' => false]

// to generate select input with all formats
$select = [];
foreach (Encoding::values() as $encoding) {
    $select[$encoding->getValue()] = $encoding->getName();
}
```

## Building

```
php buildenum.php
php builddata.php build php
php builddata.php build json
```

## Credits

- [https://github.com/ddeboer/transcoder](https://github.com/ddeboer/transcoder)
