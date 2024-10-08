<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">

<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/dt/sashagm/social" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/v/sashagm/social" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/l/sashagm/social" alt="License"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/github/languages/code-size/sashagm/social" alt="Code size"></a>
<a href="https://packagist.org/packages/sashagm/social"><img src="https://img.shields.io/packagist/stars/sashagm/social" alt="Code size"></a>

[![PHP Version](https://img.shields.io/badge/PHP-%2B8-blue)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-%2B10-red)](https://laravel.com/)

</p>

## Авторизация через социальные сети с помощью пакета для Laravel

Наш пакет предоставляет удобный способ работы с авторизацией для вашего сайта с использованием Laravel Socialite.
Так же есть и интегривованная альтернативная регистрация и авторизация через обычную форму.
Простая интеграция позволяет быстро подключить необходимые провайдеры и использовать их.
Расширенная кастомизация позволяет гибко настроить функционал под ваш проект без особых изменений.

### Оглавление:

- [Требования](#требования)
- [Установка](#установка)
- [Использование](#использование)
  - [Авторизация и регистрация](#авторизация-и-регистрация)
  - [Методы шифрования](#методы-шифрования)
  - [Фильтры генерации](#фильтры-генерации)
  - [Длина пароля](#длина-пароля)
  - [Генерация пароля](#генерация-пароля)
  - [Режим обслуживания](#режим-обслуживания)
  - [Директивы](#директивы)
  - [Кастомные поля](#кастомные-поля)
  - [Локализация](#локализация)
  - [Запуск кастомных функций](#запуск-кастомных-функций)
  - [Кастомные маршруты](#кастомные-маршруты)
  - [Кастомный логер](#кастомный-логер)
- [Дополнительные возможности](#дополнительные-возможности)
- [Тестирование](#тестирование)
- [Лицензия](#лицензия)

#### Требования

Основные требования для установки и корректной работы:

- `PHP` >= 8.0
- `Laravel` >= 10.x || 11.x
- `Composer` >= 2.4.x

#### Установка

Для установки пакета необходимо выполнить команды:

- composer require sashagm/social
- php artisan socials:install

#### Использование

1. Для начала давайте определим нашу вспомогательную конфигурацию в `/config/socials.php`:

```php

    'admin_prefix'              => '', // Префикс для маршрутов
    'isActive'                  => true,  // Доступ авторизации
    'isProvider'                => true,  // Проверка провайдера( запрет на использование одинаковых email)
    'isLoginForm'               => true,  // Разрешать авторизовываться через обычную форму

    'user'                      => [
        'table'                 => 'users', // Таблица пользователей
        'avatar'                => "img",  //  Поле для аватарок
        'pass_colum'            => "password",  // Поле пароля
        'table_after'           => "password",  // После какого поля будут добавлены новые поля
        'access_colum'          => 'isBanned',  // Поле для учета блокировки/группа или роль
        'access_value'          =>  1,  // Какое значение необходимо получить чтобы заблокировать доступ
        'name_colum'            => 'name', // Поле для логина
        'email_colum'           => 'email', // Поле для почты
        'auto_update'           =>  true,   // Разрешить пользователям обновлять данные от провайдеров
        'update_colum'          =>  'isUpdate', // Поле для обновлений данных
        'getAvatar'             => true, // Использовать аватарку пользователя из провайдера социальной сети
        'defaultAvatar'         => '/path/to/default/avatar.png', // Путь к дефолтной аватарке
        'check_field'           => 'id', // Поле для проверки активности социальных сетей.
    ],

    'genPass'                   => [
        'method'                => 'bcrypt', // Метод шифрования пароля
        'filter'                => 'number',   // Фильтр генерации пароля
        'length'                => 8,  // Количество знаков для генерации пароля
        'min'                   => 6,  // Минимальное количество знаков
        'max'                   => 10, // Максимальное количество знаков
        'stable_length'         => true,   // Стабильная генерация
        'secret'                => 'erb26vwu2', // Секретная фраза для метода md5
        'viewReg'               => true,     // Верхний регистр для метода md5
        'default_gen'           => true,    // Использовать default_pass как дефолтный пароль
        'default_pass'          => "123456", // Строка для дефолтного пароля
        'custom_string'         => "", // Свой набор символов и знаков
        'custom_hard'           => "", // Свой набор символов и знаков
        'custom_unique'         => "", // Свой набор символов и знаков
        'generation_stages'     => 10, // Сколько стадий генерации будет

    ],

    'redirect'                  => [
        'auth'                  => 'home', // редирект после авторизации укажите именной роут
        'logout'                => 'home', // редирект после выхода укажите именной роут
     ],

    'routes'                    => [
        'auth_login'            => [
                                    '/login/{provider}', // url на вызов провайдера
                                    'social-auth'        // route name
        ],
        'auth_login_callback'   => [
                                    '/login/{provider}/callback', // url на вызов коллбэк
                                    'social-callback'             // route name
        ],
        'social_logout'         => [
                                    '/logout/social',           // url на вызов выход с аккаунта
                                    'social-logout'             // route name
        ],
        'auth_login_form'       => [
                                        '/login',               // url на вызов формы входа
                                        'auth-login-form'      // route name
        ],
        'auth_login_form_callback'=> [
                                        '/login/auth',           // url на вызов коллбэк form
                                        'auth-login-form-callback'             // route name
        ],
    ],

    'custom_fields'             => [
        /*
        'phone'                 => '+1234567890',
        'address'               => '123 Main Street',
        */
    ],

    'access_admin'              => [
        /*
        1,
        */
    ],

    'feedback_before'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
         */
     ],


     'feedback_after'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
         */
     ],

     'feedback_register'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
         */
     ],

    'providers'                            => [
       /* 'vkontakte', 'github',
        */
     ],

    'logger'                        => [

        'method'                    => true,              // Использовать дефолтный вариант логирования(false - Кастомный логер)
        'path'                      => "logs/custom.log",  // Путь для кастомного логера

        'log_login'                 => true,               // Логировать успешую авторизацию
        'log_register'              => true,               // Логировать успешую регистрацию
    ],


```

2. Для модели `User` необходимо еще добавить наши новые поля которые будут дополнительно использоваться:

```php

    protected $fillable = [
        'img',
        'provider',
        'provider_id',
        'isUpdate',
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

##### Авторизация и регистрация

Вы можете использовать наш пакет не только для работы авторизации с провайдерами социальных сетей но и альтернативным способом через обычную форму они работают параллельно друг другу.
В конфигрурационном файле `/config/socials.php` параметр `isLoginForm` будет разрешать авторизовываться через обычную форму авторизации.
В разделе `genPass` параметр `method` так же отвечает за метод шифрования нашего пароля.

##### Методы шифрования

Мы не ограничеваемся в стандарном методе шифрования. Если используете другие методы шифрования, вы можете использовать как стандартный так и разные методы. Что позволяет использовать под разные проекты.

Доступные следующие методы шифрования пароля:

- `bcrypt`
- `md2` `md4` `md5`
- `password_hash`
- `sha1` `sha224` `sha256` `sha384` `sha512` `sha512/224` `sha512/256`
- `sha3-224` `sha3-256` `sha3-384` `sha3-512`
- `ripemd128` `ripemd160` `ripemd256` `ripemd320`
- `whirlpool`
- `tiger128,3` `tiger160,3` `tiger192,3` `tiger128,4` `tiger160,4` `tiger192,4`
- `snefru` `snefru256`
- `gost` `gost-crypto`
- `adler32`
- `crc32` `crc32b` `crc32c`
- `fnv132` `fnv1a32` `fnv164` `fnv1a64`
- `joaat`
- `murmur3a` `murmur3c` `murmur3f`
- `xxh32` `xxh64` `xxh3` `xxh128`
- `pbkdf2`
- `base64`

##### Режим обслуживания

Если необходимо ограничить доступ к авторизации через социальные сети. Вы можете это легко сделать в конфигрурационном файле `/config/socials.php` за это отвечает параметр `isActive`. Но так же вы можете вашим Администраторам или Определенным лицам дать доступ всегда авторизоваться даже если активно огранеичение. Для этого в разделе `access_admin` укажите через запятую id пользователей из модели `User`.
Для них всегда будет доступ.

```php

    'access_admin'              => [
        /*
        1,
        2,
        */
    ],

```

##### Директивы

Мы добавили возможность использовать `Blade директивы` для удобства вы можете использовать список сссылок для авторизации через провайдеры.
Просто добавьте директиву `@socials` где нибудь в <body> html.

```php
<body>
        @socials
</body>

```

Так же можно передавать свои классы и стили для ссылок `@socials(class="btn btn-primary", style="font-size: 16px; color: #ff2d20")`

```php
<body>
        @socials(class="btn btn-primary", style="font-size: 16px; color: #ff2d20")
</body>

```

##### Кастомные поля

Если у вас в модели `User` используются много полей которые так же необходимо добавлять, вы можете так же добавить их в конфигрурационном файле `/config/socials.php` в разделе `custom_fields`. Они будут добавлены вместе с входными данными от провайдеров при создание нового пользователя.

```php

    'custom_fields'             => [
        /*
        'phone'                 => '+1234567890',
        'address'               => '123 Main Street',
        */
    ],

```

Вы можете полность настроить абсалютно все названия полей которые используются. В разделе `user` так же можно настроить стоковые поля если у вас отличаются.

##### Фильтры генерации

Вы можете использовать разные фильтры для генерации пароля чтобы создавать разные комбинации в конфигрурационном файле `/config/socials.php` в разделе `genPass` параметр `filter`.

Доступные фильтры для генерации:

- `string` Только английские буквы верхнего и нижнего регистра.
- `number` Только цифры.
- `hard` Только английские буквы верхнего и нижнего регистра а так же цифры.
- `hard-unique` Только английские буквы верхнего и нижнего регистра а так же цифры и спец символы.
- `rus-string` Только русские буквы верхнего и нижнего регистра.
- `rus-hard` Только русские буквы верхнего и нижнего регистра а так же цифры.
- `rus-unique` Только русские буквы верхнего и нижнего регистра а так же цифры и спец символы.
- `custom-string` Задайте свой уникальный вариант букв, знаков, символов. Параметр `custom_string`
- `custom-hard` Задайте свой уникальный вариант букв, знаков, символов. Параметр `custom_hard`
- `custom-unique` Задайте свой уникальный вариант букв, знаков, символов. Параметр `custom_unique`

Для тестирования можно задать свой собственный дефолтный пароль. Настроить можно в разделе `genPass` за это отвечает параметры:
`default_gen` если `true` то будет использоваться строка `default_pass` в качестве вашего пароля, если `false` то будет рандомиться каждый раз новый пароль.

#### Длина пароля

Для более защищённого пароля так же важно и его длина. Вы можете так же контрлировать его длину в конфигрурационном файле `/config/socials.php` в разделе `genPass`.

Варианты длины:

- `stable_length` true Стабильная генерация на основе значения `length`, false Рандомная генерация на основе рандома `min` и `max`.

#### Генерация пароля

Для более эффективной защиты и уникальности пароля вы можете запустить процесс генерации пароля. Перед шифрованием и созданием пользователя будет создаваться массив с большим количесвом паролей, результатом будет рандомно выбран один из массива.
Чтобы изменить перейдите конфигрурационном файле `/config/socials.php` в разделе `genPass`.
`generation_stages` отвечает за количество стадий генерации строк.

#### Локализация

Вы можете задавать свои переводы для переводных фраз. Чтобы редактировать их опубликуйте ресурсы пакета.

#### Запуск кастомных функций

Вы можете запускать свои кастомные функции до и после авторизации пользователя. Например если хотите использовать дополнительно `spatie permission` можно запустить фунцию которая выдасть роль пользователю после регистрации. Чтобы назначить функции в конфигрурационном файле `/config/socials.php` в разделе `feedback_before` (до начала) и `feedback_after` (после), `feedback_register` (после регистрации)
Далее будет выполнена авторизации и редирект.

```php

    'feedback_before'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],

         */
     ],


     'feedback_after'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],

         */
     ],

     'feedback_register'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],

         */
     ],

```

#### Кастомные маршруты

Чтобы избежать любые конфликты с маршрутами и их именами можно задавать свои собственные. Для управления перейдите в конфигрурационном файле `/config/socials.php` в раздел `routes`. На данный момент можно управлять тремя роутами: `Вызов провайдера`, `Вызов коллбэка провайдера`, `выход с аккаунта`. Задайте для каждого роута свой url и имя роута.

```php

    'routes'                    => [
        'auth_login'            => [
                                    '/login/{provider}', // url на вызов провайдера
                                    'social-auth'        // route name
        ],
        'auth_login_callback'   => [
                                    '/login/{provider}/callback', // url на вызов коллбэк
                                    'social-callback'             // route name
        ],
        'social_logout'         => [
                                    '/logout/social',           // url на вызов выход с аккаунта
                                    'social-logout'             // route name
        ]
    ],

```

#### Кастомный логер

Если вы не хотите использовать стандартный файл для логирования, вы можете использовать отдельный файл и записывать данные логов туда.
Для управления перейдите в конфигрурационном файле `/config/socials.php` в раздел `logger`.

```php

    'logger'                        => [

        'method'                    => true,              // Использовать дефолтный вариант логирования(false - Кастомный логер)
        'path'                      => "logs/custom.log",  // Путь для кастомного логера

        'log_login'                 => true,               // Логировать успешую авторизацию
        'log_register'              => true,               // Логировать успешую регистрацию
    ],

```

#### Дополнительные возможности

Наш пакет предоставляет ряд дополнительных возможностей, которые могут быть полезны при работе с уведомлениями:

- `php artisan socials:install` - Данная команда установит все необходимые файлы.
- `php artisan socials:access {--u= : User search field  (ID)} {--a= : Access flag (0,1)}` - Данная команда может банить/разбанить пользователя.

#### Тестирование

Для проверки работоспособности можно выполнить специальную команду:

- ./vendor/bin/phpunit --configuration phpunit.xml

#### Лицензия

Social - это программное обеспечение с открытым исходным кодом, лицензированное по [MIT license](LICENSE.md).
