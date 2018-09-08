# PHP Catch Exit Statements

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imliam/php-catch-exit.svg)](https://packagist.org/packages/imliam/php-catch-exit)
[![Total Downloads](https://img.shields.io/packagist/dt/imliam/php-catch-exit.svg)](https://packagist.org/packages/imliam/php-catch-exit)
[![License](https://img.shields.io/github/license/imliam/php-catch-exit.svg)](LICENSE.md)

Gracefully handle an unwanted exit statement.

<!-- TOC -->

- [PHP Catch Exit Statements](#php-catch-exit-statements)
    - [🤔 F.A.Q.](#🤔-faq)
        - [Why would you need this?](#why-would-you-need-this)
        - [So why do this?](#so-why-do-this)
    - [💾 Installation](#💾-installation)
    - [📝 Usage](#📝-usage)
        - [Print a string](#print-a-string)
        - [Execute a closure](#execute-a-closure)
    - [🔖 Changelog](#🔖-changelog)
    - [⬆️ Upgrading](#⬆️-upgrading)
    - [🎉 Contributing](#🎉-contributing)
        - [🔒 Security](#🔒-security)
    - [👷 Credits](#👷-credits)
    - [♻️ License](#♻️-license)

<!-- /TOC -->

## 🤔 F.A.Q.

### Why would you need this?

This shouldn't be needed at all, really. There is virtually no practical reason to have an `exit` statement over an exception in a modern PHP application, as all frameworks have their own decent, extendable exception handlers that should be taken full advantage of.

That said, a _lot_ of beginners PHP tutorials end up showing the same sort of code for your first database connection:

```php
<?php
$db = mysql_connect(...) or die('Could not connect.');
```

Luckily this is a thing of the past for the most part, but unfortunately still seems to be a practice some people use. Over the past few years, I still occasionally come across Composer packages that still use `exit` and `die` statements when an error occurs.

These packages should be avoided entirely - or better yet, fork or submit a pull-request when they pop up.

### So why do this?

This package/function is more of a proof-of-concept to show how these situations can be handled, rather than a serious suggestion of a way to keep working with them.

## 💾 Installation

You can install the package with [Composer](https://getcomposer.org/) using the following command:

```bash
composer require imliam/php-catch-exit:^1.0.0
```

## 📝 Usage

If you've ever written a basic exit statement before, you'll understand that, if the output buffer is empty at that point, you'll be greeted with a blank white screen in the browser with text given to the statement.

``` php
exit('Something went wrong.');
```

This package adds a single helper function that will accept a closure as the first argument. This closure is where you can add any code that you expect _could_ throw an exit statement - just like the `try` block of a try-catch statement.

Assuming no exit occurs, you can return a value from this closure and continue your application like normal.

```php
$var = catch_exit(function() {
    return "It's all okay.";
}, ...);

echo $var; // "It's all okay."
```

### Print a string

The second argument of the function _can_ accept a string which will be output to the buffer _if_ an early exit occurs in the closure from the first argument.

This lets you decide what gets shown instead of the previous content, which may be out of your control.

```php
catch_exit(function() {
    exit('Uh-oh!');
}, 'Something went wrong.');

// 'Something went wrong.' is displayed
```

You can also use this to render a view that can be displayed to the user in case this occurs.

### Execute a closure

However, the second argument can also be another closure which will only be executed if there is an early exit occurring in the first closure. It'll also give you access to the current output buffer

Here, you can gracefully handle the way the application shuts down. You may want to perform some actions such as:

- Log the error that occurred
- Flash a message to the session to inform the user what happened
- Render an error page to the user
- Redirect the user back to the previous page

```php
catch_exit(function() {
    exit('Uh-oh, something went wrong!');
}, function($message) {
    Log::error("The application exited with the following message: '{$message}'");

    return View::make('errors.500')
        ->with('error', 'An unexpected error occurred.');
});
```

This can also be used to handle fatal errors - such as when a class that doesn't exist is being instantiated or when the request exceeds the maximum execution time for PHP.

```php
catch_exit(function() {
    new ClassThatDoesNotExist();
}, function($message) {
    // ...
});
```

## 🔖 Changelog

Please see [the changelog file](CHANGELOG.md) for more information on what has changed recently.

## ⬆️ Upgrading

Please see the [upgrading file](UPGRADING.md) for details on upgrading from previous versions.

## 🎉 Contributing

Please see the [contributing file](CONTRIBUTING.md) and [code of conduct](CODE_OF_CONDUCT.md) for details on contributing to the project.

### 🔒 Security

If you discover any security related issues, please email liam@liamhammett.com instead of using the issue tracker.

## 👷 Credits

- [Liam Hammett](https://github.com/imliam)
- [All Contributors](../../contributors)

## ♻️ License

The MIT License (MIT). Please see the [license file](LICENSE.md) for more information.
