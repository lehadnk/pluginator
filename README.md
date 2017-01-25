# Pluginator
An easy way to provide plugin support in your projects.

## Usage
```php
// Application Bootstrap
\Pluginator\Core::setConfig([
    'pluginsDir' => __DIR__.'/plugins/',
]);
\Pluginator\Core::init();
```
```php
// Pluginable app code
class Cart {
    public function addToCart(Product $product) {
        doSomething();    
        \Pluginator\EvenHandler::trigger('addToCart', [$cart, $product]);
    }
}
```
```php
/**
* Black Friday 25% off plugin code
* in /plugins/blackfriday/plugin.php
*/
\Pluginator\EventHandler::bind('addToCart', function (Cart $cart, Product $product) {
    if (date('m-d') == date('m-d', strtotime('fourth friday of november'))) {
        $cart->total = $cart->calculate() * 0.75;
    }
});
```

## Event System
While developing the system, you could create as many triggers as you want, creating entry points for plugin developers to customize the system's behaviour.
```php
class BlogController extends ControllerBase {
    public function savePost() {
        \Pluginator\EventHandler::trigger('beforeSavingPost');
        $this->post->save();
        \Pluginator\EventHandler::trigger('afterSavingPost');
    }
}
```

You could also provide plugin developers an access to the objects and variables. Notice that you should always pass them into the function as an array:
```php
$post = $db->getEntity('post', ['id' => 16]);
\Pluginator\EventHandler::trigger('loadPost', [$post]);
```

Passing variables by link will also allow a plugin developer to change them:
```php
$price = $cart->calculatePrice();
\Pluginator\EventHandler::trigger('priceCalculate', [&$price]);
// Black friday plugin mentioned above recalculates 25% off
$user->card->charge($price);
```

## Plugin Structure
```
pluginName
└── plugin.php
```
plugin.php is the only plugin entry point. It's being called once plugin is being loaded and should contain a list of all the binders for the system events plugin going to hook:
```php
<?php

\Pluginator\EventHandler::bind('addToCart', 'addToCart');
\Pluginator\EventHandler::bind('makeOrder', 'Plugin::makeOrder');
\Pluginator\EventHandler::bind('emailEntered', function() {});
```

#### Binders
You could use all types of callable entities to bind your handlers:
```php
\Pluginator\EventHandler::bind('testFunction', function (&$b) {
    $b += 10;
});

$a = 15;
\Pluginator\EventHandler::bind('testClosure', function (&$b) use ($a) {
    $b += $a;
});

class TestPlugin {
    public static function testStatic(&$a) {
        $a += 20;
    }

    public function testObject(&$a) {
        $a += 40;
    }
}

\Pluginator\EventHandler::bind('testStatic', 'TestPlugin::testStatic');

$plugin = new TestPlugin();
\Pluginator\EventHandler::bind('testObject', [$plugin, 'testObject']);
```

#### Integration with an application
Your handlers will be executed by the application, so as long as some auto-loading feature is there, you could access all the framework/application features:
```php
// Plugin for yii2-powered project
\Pluginator\EventHandler::bind('pageView', function (Page $page) {
    $connection = Yii::$app->getDb();
    $command = $connection->createCommand("
        UPDATE pages SET views = views + 1 WHERE id = :id
    ", [':id' => $page->id])->queryAll();
});
```

#### Passing by reference
You can pass a variable by reference to a function so the function can modify the variable. The syntax is as follows: 
```php
\Pluginator\EventHandler::bind('testFunction', function (&$b) {
    $b += 10;
});
```
Please note that you should follow the application plugin developer documentation, or contact with the application developer before using this feature, since you're allowed to get by reference only variables application developer fine to share. Else, an exception will be thrown:
```
Parameter 1 to TestPlugin::testStatic() expected to be a reference, value given
```
This means that said developer has not allowed the use of references for you, passing the values instead, so there's no way to modify them.

## Plugin Installation
Put the plugin under the directory which stated as your "pluginsDir". Generally, a project documentation should state which one. There's no extra steps needed.
```
plugins
├── blackfriday
├── googlefonts
└── seotags
```