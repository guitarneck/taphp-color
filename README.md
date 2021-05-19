# taphp-color

TAP consumer in color for TAPHP. 

This library is not psr-0/4 compliant. Indeed, its contains functions to capture the running 
tests of TAPHP that make it unfriendly with composer's autoload way. 

> Tested on PHP versions : 5.6.9, 7.4.13 ,8.0.0

# example

with composer :

``` php
require dirname(__DIR__).'/vendor/guitarneck/taphp-color/taphp-html.php';
require dirname(__DIR__).'/vendor/guitarneck/taphp/taphp.lib.php';
```

without composer :

``` php
require_once 'taphp-html.php';
require_once 'taphp.lib.php';
```

# License

[MIT © guitarneck](./LICENSE)