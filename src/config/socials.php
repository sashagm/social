<?php

return [
    
    'admin_prefix'              => '', // Префикс для маршрутов
    'isActive'                  => true, // Доступ авторизации
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
        'length'                =>  8,  // Количество знаков для генерации пароля
        'min'                   =>  6,  // Минимальное количество знаков
        'max'                   =>  10, // Максимальное количество знаков
        'stable_length'         => true,   // Стабильная генерация 
        'secret'                => 'erb26vwu2', // Секретная фраза для метода md5
        'viewReg'               => true,     // Верхний регистр для метода md5
        'default_gen'           => true,    // Использовать default_pass как дефолтный пароль
        'default_pass'          => "123456", // Строка для дефолтного пароля

    ],

     'redirect'                 => [
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
    

];
