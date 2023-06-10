<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">

<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/dt/sashagm/social" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/v/sashagm/social" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/l/sashagm/social" alt="License"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/github/languages/code-size/sashagm/social" alt="Code size"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/stars/sashagm/social" alt="Code size"></a>
</p>


## Авторизация через социальные сети с помощью пакета для Laravel
Наш пакет предоставляет удобный способ работы с авторизацией для вашего сайта с использованием Laravel Socialite. 


### Оглавление:

- [Установка](#установка)
- [Использование](#использование)
- [Дополнительные возможности](#дополнительные-возможности)
- [Тестирование](#тестирование)
- [Лицензия](#лицензия)

#### Установка

Для установки пакета необходимо выполнить команды:

- composer require sashagm/social
- php artisan vendor:publish --tag=social-auth --provider=Sashagm\Social\Providers\SocialServiceProvider


#### Использование

1. Для начала давайте определим нашу вспомогательную конфигурацию в `/config/socials.php`:

```php

    'admin_prefix'              => '',  // Укажите префикс для роутов
    'user_table'                => 'users', // Укажите таблицу для пользователей
    'user_avatar'               => "img",   // Укажите поле для аватаркм
    'user_table_after'          => "password", // Укажите после какого поля необходимо добавить новые поля
    'isActive'                  => true,   // Разрешить авторизацию (true/false)

```

2. Для модели `User` необходимо еще добавить наши новые поля:

```php

    protected $fillable = [
        'img',
        'provider',
        'provider_id',
    ];

```


3. Выполните команду: `php artisan migrate` чтобы добавить вспомогательные поля в таблицу пользователей.

4. Для использования дополнительных провайдеров авторизации в Laravel Socialite, вы можете посетить сайт [socialiteproviders.com](https://socialiteproviders.com/). Здесь вы найдете список доступных провайдеров, а также инструкции по их установке и настройке.

5. Пример с подключённым провайдером ВК. Как только вы подключите провайдер добавьте маршрут в ваш blade шаблон.

```php

@guest
<a href="{{route('social-auth', 'vkontakte')}}"> Вход через ВК</a>
@endguest

```



#### Дополнительные возможности



#### Тестирование

Для проверки работоспособности можно выполнить специальную команду:

- ./vendor/bin/phpunit --configuration phpunit.xml

#### Лицензия

Social - это программное обеспечение с открытым исходным кодом, лицензированное по [MIT license](LICENSE.md ).
