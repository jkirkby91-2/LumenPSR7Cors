Lumen-CORS
==========

[Cross-origin resource sharing](https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS) (CORS) Middleware for [Lumen micro-framework](http://lumen.laravel.com/).

[![Latest Stable Version](https://poser.pugx.org/palanik/lumen-cors/v/stable.svg)](https://packagist.org/packages/palanik/lumen-cors)

## Installation ##

After you install lumen as per [lumen docs](http://lumen.laravel.com/docs/installation#install-lumen), install lumen-cors from `lumen` folder.

### Install with [Composer](https://packagist.org/packages/Jkirkby91-2/LumenPSR7Cors) ###

Run `composer require "Jkirkby91-2/LumenPSR7Cors"` to install lumen-cors.


## Usage ##

### Global CORS ###

If you want CORS enabled for every HTTP request to your application, simply list the middleware class `Jkirkby91\LumenPSR7Cors\Http\Middleware\Cors` in the $app->middleware() call of your `bootstrap/app.php` file. 

### CORS for Routes ###

If you would like to enable CORS to specific routes, you should first assign the `lumen-cors` middleware a short-hand key in your `bootstrap/app.php` file.

```php
$app->routeMiddleware([
    'cors' => 'Jkirkby91\LumenPSR7Cors\Http\Middleware\Cors',
]);
```

Then, you use the key in the route options array.
```php
$app->get('/data', ['middleware' => 'cors', function() {
    //
}]);
```

More info. - http://lumen.laravel.com/docs/middleware#registering-middleware

## License ##

[MIT](LICENSE)