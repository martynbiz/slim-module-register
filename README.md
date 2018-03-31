# Register Module #

## Introduction ##

Adds registration functionality to auth module.

## Installation ##

Use composer to fetch the files and copy files to your project tree:

```
$ composer require martynbiz/slim-module-register
```

Install other dependant modules if not already done so:

```
$ composer require martynbiz/slim-module-core
$ composer require martynbiz/slim-module-auth
```

Enable the module in src/settings.php:

```
return [
    'settings' => [
        ...
        'module_initializer' => [
            'modules' => [
                ...
                'martynbiz-code' => 'MartynBiz\\Slim\\Module\\Core\\Module',
                'martynbiz-auth' => 'MartynBiz\\Slim\\Module\\Auth\\Module',
                'martynbiz-register' => 'MartynBiz\\Slim\\Module\\Register\\Module',
            ],
        ],
    ],
];
```
