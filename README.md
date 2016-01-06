# php-simple-router 
[![Build Status](https://travis-ci.org/alexpts/php-simple-router.svg?branch=master)](https://travis-ci.org/alexpts/php-simple-router)
[![Test Coverage](https://codeclimate.com/github/alexpts/php-simple-router/badges/coverage.svg)](https://codeclimate.com/github/alexpts/php-simple-router/coverage)
[![Code Climate](https://codeclimate.com/github/alexpts/php-simple-router/badges/gpa.svg)](https://codeclimate.com/github/alexpts/php-simple-router)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/php-simple-router/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-simple-router/?branch=master)

Simple router compatible with the PSR-7


Роутер предназначен для передачи управления обработчику запроса по входному http запросу.
Весь роутинг делиться на небольшие компоненты, которые занимаются определенной задачей.

#### Router
Объект, который храни в себе путь/регулярку и ряд ограничений на http метод или переменную запроса, а также обработчик, который нужно вызвать для этого пути.

```php
$route2 = new Route('/blog/{id}/', $endPoint2, ['id' => '\d+']);

$route = new Route('/{lang}/users/{id}/{action}/', $endPoint, [
	'lang' => 'ru|en',
	'action' => '[a-z0-9_]+',
	'id' => '\d+',
], Route::ONLY_XHR);
```

#### CollectionRoute
Объект для работы с коллекцией роутов. Роуты добавляются в коллекцию роутов с определенным приоритетом.
Метод `getRoutes` возвращает массив всех роутов отсортированных по приоритету.


#### Matcher
Объект осуществляет поиск активного роута на основе соллекции роутов и текущего uri запроса.
Простой поиск первого совпадения (наиболее приоритетного роута):

```php
$matcher = new Matcher();
$uri = '/profile/23/';
$routes = ...; // CollectionRoute instance
$endPoint = $matcher->matchFirst($routes, $uri); // IPoint instance
```

Можно получить все `endPoint` объекты, а не только первый. Метод `match` возвращает генератор. Через генератор достаются все объекты `endPoint` совпавших роутов.

```php
 foreach ($matcher->match($routes, $uri) as $endPoint) {
 	...
 }
```

Брагодаря такой конструкции, можно выполнять маленькие обработчики друг за другом и др.

#### EndPoint
Все обработчики поддерживают интерфейс IPoint и служат для создания обработчика запроса с нужными параметрами и вызова обработчика с нужными параметрами. В комплекте идет ряд реализаций endPoint, которые могут помочь коротко и лаконично описать правила роутинга вашего приложения.

###### CallablePoint
Предназначен для вызова любого обработчика callable типа.

```php
$endPoint = new Point\CallablePoint([
	'callable' => function () {
		return '404';
	}
]);
```

##### ControllerPoint
Явно задает класс контроллера и метод.
```php
$endPoint = new Point\ControllerPoint([
	'controller' => 'CollectionRouteTest',
	'action' => 'action'
]);
```


###### DynamicController
Класс контроллера формируется динамически из переменной запрос `:controller`, которая является обязательной.
Action берется из переменной запроса `:action` (по умолчанию `index`)

```php
$endPoint = new Point\DynamicController([
	'prefix' => 'Demo'
]);
```


###### ControllerDynamicAction
Похож на `DynamicController` за исключением того, что контрллер указывается явно, а не берется динамически из переменной запроса `:controller`.

```php
$endPoint = new Point\ControllerDynamicAction([
	'controller' => SomeController::class
]);
```

##### Создавайте новые endPoint
Охватить специфику каждого проекта очень сложно и это будет избыточно. Вы можете легко создать более специфичный класс типа `IPoint` на основе базовых классов или с нуля. Примером такого класса для своего проекта служит класс `DynamicBundleController`. Он призван показать как легко и просто делаются endPoint-ы под свой проект, если дефолтные по какой-то причине не подошли.

