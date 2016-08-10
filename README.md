# Laravel Responder

TLDR ;)


## Installation

Install the package through Composer:

```bash
composer require purwandi/responder
```

### Laravel

#### Registering the Service Provider

After updating Composer, append the following service provider to the providers ```key``` in ```config/app.php```

```php
Purwandi\Responder\ResponderServiceProvider::class
```

#### Registering the Facade

If you like facades you may also append the ```Responder``` facade to the ```aliases``` key:

## Usage

#### Using Facade

Optionally, you may use the ```Responder``` facade to create responses:

```php
return Responder::success($users);
```

#### Including Data

```php
return Responder::with('blog')->success($users);
```

```php
return Responder::with('blog', 'blog.comment')->success($users);
```

## Credit

1. [Fractal](https://github.com/thephpleague/fractal)
2. [Laravel Responder](https://github.com/flugger/laravel-responder)
