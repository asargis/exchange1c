# PHP exchange1c - обмен 1С предприятие с сайтом на php

[//]: # ([![Packagist]&#40;https://img.shields.io/packagist/l/Ar3s/exchange1c.svg?style=flat-square&#41;]&#40;LICENSE&#41;)

[//]: # ([![Packagist]&#40;https://img.shields.io/packagist/dt/Ar3s/exchange1c.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/Ar3s/exchange1c&#41;)

[//]: # ([![Packagist]&#40;https://img.shields.io/packagist/v/Ar3s/exchange1c.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/Ar3s/exchange1c&#41;)

[//]: # ([![Travis &#40;.org&#41;]&#40;https://img.shields.io/travis/Ar3s/exchange1c.svg?style=flat-square&#41;]&#40;https://travis-ci.org/Ar3s/exchange1c&#41;)

[//]: # ([![Codecov]&#40;https://img.shields.io/codecov/c/github/Ar3s/exchange1c.svg?style=flat-square&#41;]&#40;https://codecov.io/gh/Ar3s/exchange1c&#41;)

[//]: # ([![StyleCI]&#40;https://github.styleci.io/repos/153751681/shield?branch=master&#41;]&#40;https://github.styleci.io/repos/153751681&#41;)




Установка этой библиотеки, должна упрощать интеграцию 1С в ваш сайт.

Библиотека содержит набор интерфейсов, которые необходимо реализовать, чтобы получить возможность обмениваться товарами и документами с 1С. Предполагается, что у Вас есть 1С:Предприятие 8, Управление торговлей", редакция 11.3, версия 11.3.2 на платформе 8.3.9.2033. 

Если у вас версия конфигурации ниже, то скорее всего библиотека все равно будет работать, т.к. по большей части, обмен с сайтами сильно не меняется в 1С от версии к версии.

Данная библиотека была написана на основе модуля https://github.com/carono/yii2-1c-exchange - все основные интерфейсы взяты именно из этого модуля.

# Зависимости
* php ^7.1
* carono/commerceml
* symfony/http-foundation ^4.1

# Установка
`composer require Ar3s/exchange1c`

# Использование
Для использования библиотеки вам неободимо определить массив конфигов и реализовать интерфейсы в ваших моделях.

```php
require_once './../vendor/autoload.php'; //Подулючаем автолоад

// Определяем конфиг
$configValues = [
    'import_dir'    => '1c_exchange',
    'login'    => 'admin',
    'password' => 'admin',
    'use_zip'       => false,
    'file_part' => 0,
    'models'    => [
        \Ar3s\Exchange1C\Interfaces\GroupInterface::class => \Tests\Models\GroupTestModel::class,
        \Ar3s\Exchange1C\Interfaces\ProductInterface::class => \Tests\Models\ProductTestModel::class,
        \Ar3s\Exchange1C\Interfaces\OfferInterface::class => \Tests\Models\OfferTestModel::class,
    ],
];
$config = new \Ar3s\Exchange1C\Config($configValues);
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$symfonyDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
$dispatcher = new \Ar3s\Exchange1C\SymfonyEventDispatcher($symfonyDispatcher);
$modelBuilder = new \Ar3s\Exchange1C\ModelBuilder();
// Создаем необходимые сервисы
$loaderService = new \Ar3s\Exchange1C\Services\FileLoaderService($request, $config);
$authService = new \Ar3s\Exchange1C\Services\AuthService($request, $config);
$categoryService = new \Ar3s\Exchange1C\Services\CategoryService($request, $config, $dispatcher, $modelBuilder);
$offerService = new \Ar3s\Exchange1C\Services\OfferService($request, $config, $dispatcher, $modelBuilder);
$catalogService = new \Ar3s\Exchange1C\Services\CatalogService($request, $config, $authService, $loaderService, $categoryService, $offerService);


$mode = $request->get('mode');
$type = $request->get('type');

try {
    if ($type == 'catalog') {
        if (!method_exists($catalogService, $mode)) {
            throw new Exception('not correct request, mode=' . $mode);
        }
        //Запускаем сервис импорта каталога
        $body = $catalogService->$mode();
        $response = new \Symfony\Component\HttpFoundation\Response($body, 200, ['Content-Type', 'text/plain']);
        $response->send();
    } else {
        throw new \LogicException(sprintf('Logic for method %s not released', $type));
    }
} catch (\Exception $e) {
    $body = "failure\n";
    $body .= $e->getMessage() . "\n";
    $body .= $e->getFile() . "\n";
    $body .= $e->getLine() . "\n";

    $response = new \Symfony\Component\HttpFoundation\Response($body, 500, ['Content-Type', 'text/plain']);
    $response->send();
}
```

Более подробную информацию по интерфейсам и их реализациям можно почитаь в документации https://github.com/carono/yii2-1c-exchange
Документация будет добалена позже.

# Лицензия
Данный пакет является открытым кодом под лицензией [MIT license](LICENSE).




