# Установка Composer

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

php composer-setup.php

php -r "unlink('composer-setup.php');"
```

## Команды

``php composer.phar --version``

PhpStorm

В IDE встроена поддержка composer:

- Откройте настройки: File → Settings (Windows/Linux) или PhpStorm → Preferences (macOS)
- Перейдите в раздел Languages & Frameworks → PHP → Composer
- В поле Composer executable выберите composer.phar
- Установите флажок Download composer.phar from getcomposer.org — IDE сама скачает файл в корень проекта
- В поле PHP interpreter выберите ваш PHP-интерпретатор
- Нажмите OK

После настройки в PhpStorm:

- Tools → Composer → Init Composer — создаст composer.json (о нём дальше)
- Tools → Composer → Install — выполнит php composer.phar install
- Tools → Composer → Add Dependency... — добавит новый пакет

---

## Файл composer.json

``` json
{
    "name": "your-name/semester-project",
    "description": "Мой семестровый проект",
    "type": "project",
    "require": {
        "php": ">=8.1"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

### Ключевые секции:

- **name** - название проекта, всегда в формате вендор/название
- **require** - зависимости, и требуемые внешние модули, и требования к среде выполнения (в примере - требование к
  версии интерпретатора)
- **require-dev** - зависимости на время разработки, которые не нужны в проде (всякие генераторы кода, тесты,
  инструменты отладки и т.д.)
- **autoload** - мы используем PSR-4, но вообще говоря есть и другие варианты правил автозагрузки

---

## Основные команды Composer

Буду считать, что вы настроили альяс, если нет, просто вместо composer в примерах подставляйте php composer.phar

- ``composer install`` - устанавливает зависимости. Конкретные версии фиксируются в composer.lock, если этот файл уже
  есть - устанавливаются в точности те версии, которые там есть (даже если есть более новые)
- ``composer update`` - обновляет зависимости до последних версий, удовлетворяющих composer.json
- ``composer dump-autoload`` - перегенерирует автозагрузчик (нужно при изменении секции autoload в конфиг)
- ``composer remove vendor/package`` - удаление пакета

## Полезные пакеты для веб-приложений

- ``symfony/var-dumper`` - красивый аналог var_dump, добавляет функции dump() и dd()
- ``monolog/monolog`` - гибкая и универсальня реализация логирования
- ``vlucas/phpdotenv`` - работа с конфигом в формате .env

Пример установки:
``composer require symfony/var-dumper``
---

## Структура приложения с Composer

Примерная структура вашего приложения будет выглядеть так:

* project/ - или любое другое название - корень вашего проекта
    * public/ - статика и фронтэнд контроллер
        * index.php - не забываем подключить vendor/autoload.php
        * assets/ - CSS, JS, изображения и любая другая статика
    * src/ - ваш код, корень пространства имён App\
        * Controllers/ - обработка http запросов
            * Core/ - роутер, базовый контроллер и другие общие классы
            * Models/ - модели
            * Services/ - классы, которые отвечают за бизнес-логику
    * vendor/ - сторонние пакеты и служебные файлы composer
    * .gitignore
    * composer.json
    * composer.lock
    * composer.phar

---

### Пример файла .env:

``` dotenv
APP_NAME="Мой проект"
APP_ENV=dev
APP_DEBUG=true

DB_HOST=localhost
DB_NAME=myapp
DB_USER=root
DB_PASS=secret
```

``APP_ENV`` — окружение в котором запущено приложение. Обычно три варианта:

- dev — локальная разработка
- staging — тестовый сервер
- production — боевой сервер

В коде можно проверять окружение и менять поведение — например показывать подробные ошибки только в dev, а в production
скрывать и логировать.

``APP_DEBUG`` — режим отладки. Когда ``true``:

Показываются подробные ошибки с трассировкой стека
Выводится отладочная информация

Когда ``false`` — пользователь видит только общую страницу ошибки, подробности только в логах.

Связь между ``APP_ENV`` и ``APP_DEBUG`` простая:

``` 
dev         → APP_DEBUG=true
production  → APP_DEBUG=false  // никогда не показывай ошибки пользователям
```

На боевом сервере ``APP_DEBUG=true`` — это дыра в безопасности. Пользователь увидит пути к файлам, структуру базы данных
и другую чувствительную информацию.

---

## Методы Monolog/monolog логирование

``` php
$logger->debug('Отладочная информация');      // самый низкий уровень
$logger->info('Общая информация');             // успешные операции
$logger->notice('Важное но не ошибка');        // нештатно но не критично
$logger->warning('Предупреждение');            // потенциальная проблема
$logger->error('Ошибка');                      // ошибка, но приложение работает
$logger->critical('Критическая ошибка');       // компонент недоступен
$logger->alert('Требует немедленного действия'); // нужно разбудить админа
$logger->emergency('Система не работает');     // самый высокий уровень
```

| Метод         | Когда использовать                        |
|---------------|-------------------------------------------|
| ``emergency`` | Сервер упал                               |
| `alert`       | База данных недоступна                    |
| `critical`    | Критический компонент сломан              | 
| `error`       | Пойманное исключение                      | 
| `warning`     | Устаревший метод, нежелательное поведение |
| `notice`      | Нештатная но ожидаемая ситуация           | 
| `info`        | Успешный запрос, загрузка конфига         |
| `debug`       | Значения переменных при отладке           |


Основные handlers:

```php
// В файл
new StreamHandler('logs/app.log')

// В консоль
new StreamHandler('php://stdout')

// По email при критических ошибках
new NativeMailerHandler('admin@site.com', 'Критическая ошибка', 'from@site.com')

// В системный лог (syslog)
new SyslogHandler('myapp')

// В браузерную консоль (для разработки)
new BrowserConsoleHandler()

// В Telegram, Slack и другие — через сторонние расширения
```
Можно комбинировать несколько handlers:

```php
$logger = new Logger('app');

// debug и выше — в файл
$logger->pushHandler(new StreamHandler('logs/app.log', Logger::DEBUG));

// error и выше — дополнительно на почту
$logger->pushHandler(new NativeMailerHandler('admin@site.com', 'Ошибка', 'from@site.com', Logger::ERROR));
```
Теперь обычные логи идут в файл, а критические ошибки — ещё и на почту.


Уровень важности в handler — второй параметр определяет минимальный уровень который handler будет обрабатывать:
```php
new StreamHandler('logs/debug.log', Logger::DEBUG)  // все сообщения
new StreamHandler('logs/error.log', Logger::ERROR)  // только ошибки и выш
````
---

## Методы vlucas/phpdotenv обработка .env

Создание объекта:
``` php
Dotenv::createImmutable($path)   // не перезаписывает существующие переменные
Dotenv::createMutable($path)     // перезаписывает существующие переменные
Dotenv::createUnsafeImmutable($path) // также помещает в getenv() — небезопасно
```

Загрузка:

``` php
$dotenv->load()      // бросает исключение если .env не найден
$dotenv->safeLoad()  // молча игнорирует если .env не найден
```
Валидация обязательных ключей:

``` php
// проверить что ключи существуют
$dotenv->required(['DB_HOST', 'DB_NAME']);

// проверить что не пустые
$dotenv->required(['DB_HOST'])->notEmpty();

// проверить допустимые значения
$dotenv->required(['APP_ENV'])->allowedValues(['dev', 'staging', 'production']);

// проверить что значение — целое число
$dotenv->required(['DB_PORT'])->isInteger();

// проверить что булево значение
$dotenv->required(['APP_DEBUG'])->isBoolean();
```

### vlucas/phpdotenv по умолчанию кладёт данные в:
`$_ENV` — суперглобальный массив:
``` php
$_ENV['DB_HOST'] // 'localhost'
```
`$_SERVER` — суперглобальный массив сервера:
```php
$_SERVER['DB_HOST'] // 'localhost'
```
`getenv()` — встроенная функция PHP:
```php
getenv('DB_HOST') // 'localhost'
```

Какой использовать?

`$_ENV` — предпочтительнее в современном PHP. Явно и понятно откуда берутся данные.

`getenv()`— работает везде, но в многопоточных средах может быть небезопасен — поэтому `createImmutable` не кладёт данные в `getenv()` по умолчанию. Для этого есть `createUnsafeImmutable`.

`$_SERVER` — лучше не использовать для конфига — он предназначен для данных сервера и HTTP запроса, а не для переменных приложения.