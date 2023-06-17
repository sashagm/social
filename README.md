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
Простая интеграция позволяет быстро подключить необходимые провайдеры и использовать их.
Расширенная кастомизация позволяет гибко настроить функционал под ваш проект без особых изменений.


### Оглавление:

- [Установка](#установка)
- [Использование](#использование)
  - [Методы шифрования](#методы-шифрования)
  - [Фильтры генерации](#фильтры-генерации)
  - [Длина пароля](#длина-пароля)
  - [Режим обслуживания](#режим-обслуживания)
  - [Кастомные поля](#кастомные-поля)
  - [Локализация](#локализация)
  - [Запуск кастомных функций](#запуск-кастомных-функций)
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

    'admin_prefix'              => '', // Префикс для маршрутов
    'isActive'                  => true,  // Доступ авторизации
    'isProvider'                => true,  // Проверка провайдера( запрет на использование одинаковых email)

    'user'                      => [
        'table'                 => 'users', // Таблица пользователей
        'avatar'                => "img",  //  Поле для аватарок
        'pass_colum'            => "password",  // Поле пароля
        'table_after'           => "password",  // После какого поля будут добавлены новые поля
        'access_colum'          => 'isBanned',  // Поле для учета блокировки/группа или роль
        'access_value'          =>  1,  // Какое значение необходимо получить заблокировать доступ
        'name_colum'            => 'name', // Поле логин
        'email_colum'           => 'email', // Поле почты
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

    ],

    'redirect'                  => [
        'auth'                  => 'home', // редирект после авторизации укажите именной роут
        'logout'                => 'home', // редирект после выхода укажите именной роут
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
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ],  
     
     
     'feedback_after'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ],

     'feedback_register'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ], 

```

2. Для модели `User` необходимо еще добавить наши новые поля которые будут дополнительно использоваться:

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
##### Методы шифрования

Мы не ограничеваемся в стандарном методе шифрования. Если используете другие методы шифрования, вы можете использовать как стандартный так и разные методы. Что позволяет использовать под разные проекты.

Доступные следующие методы шифрования пароля:

- `bcrypt`
- `md5`
- `password_hash`
- `sha1`
- `sha256`
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


##### Кастомные поля

Если у вас в модели `User` используются много полей которые так же необходимо добавлять, вы можете так же добавить их в конфигрурационном файле `/config/socials.php`  в разделе `custom_fields`. Они будут добавлены вместе с входными данными от провайдеров при создание нового пользователя. 

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

Вы можете использовать разные фильтры для генерации пароля чтобы создавать разные комбинации в конфигрурационном файле `/config/socials.php`  в разделе `genPass` параметр `filter`.

Доступные фильтры для генерации:

- `string` Только английские буквы верхнего и нижнего регистра. 
- `number` Только цифры.
- `hard` Только английские буквы верхнего и нижнего регистра а так же цифры.
- `hard-unique` Только английские буквы верхнего и нижнего регистра а так же цифры и спец символы.
- `rus-string` Только русские буквы верхнего и нижнего регистра. 
- `rus-hard` Только русские буквы верхнего и нижнего регистра а так же цифры. 
- `rus-unique` Только русские буквы верхнего и нижнего регистра а так же цифры и спец символы. 

Для тестирования можно задать свой собственный дефолтный пароль. Настроить можно в разделе `genPass` за это отвечает параметры:
`default_gen` если `true` то будет использоваться строка `default_pass` в качестве вашего пароля, если `false` то будет рандомиться каждый раз новый пароль.


#### Длина пароля

Для более защищённого пароля так же важно и его длина. Вы можете так же контрлировать его длину в конфигрурационном файле `/config/socials.php`  в разделе `genPass`. 

Варианты длины:

- `stable_length` true Стабильная генерация на основе значения `length`, false Рандомная генерация на основе рандома `min` и `max`.


#### Локализация

Вы можете задавать свои переводы для переводных фраз. Чтобы редактировать их опубликуйте ресурсы пакета.


#### Запуск кастомных функций

Вы можете запускать свои кастомные функции до и после авторизации пользователя. Например если хотите использовать дополнительно `spatie permission` можно запустить фунцию которая выдасть роль пользователю после регистрации. Чтобы назначить функции в конфигрурационном файле `/config/socials.php`  в разделе `feedback_before` (до начала) и `feedback_after` (после), `feedback_register` (после регистрации)
Далее будет выполнена авторизации и редирект.  

```php

    'feedback_before'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ],  
     
     
     'feedback_after'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ],  

     'feedback_register'                  => [

        /* [
             'class' => 'App\Services\Testing',
             'method' => 'one',
             'params' => []
         ],
 
         [
             'class' => 'App\Services\Testing',
             'method' => 'who',
             'params' => ['test']
         ],
         
         */
     ],      

```


#### Дополнительные возможности



#### Тестирование

Для проверки работоспособности можно выполнить специальную команду:

- ./vendor/bin/phpunit --configuration phpunit.xml

#### Лицензия

Social - это программное обеспечение с открытым исходным кодом, лицензированное по [MIT license](LICENSE.md ).
