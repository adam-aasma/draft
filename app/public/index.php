<?php
require_once dirname(__FILE__) . '/../bootstrap.php';

use Walltwisters\controllers\ContentController;
use Walltwisters\controllers\UserController;
use Walltwisters\lib\exceptions\NotAuthenticatedException;

function invokeControllerWithMethod($controller, $methodName, $request, $response, $app, $needsAuthentication = true) {
    try {
        $app->logger->info(sprintf("Calling /%s/%s", $controller, $methodName));
        $className = getControllerName($controller);
        $reflectionClass = new ReflectionClass('Walltwisters\controllers\\' . $className);
        $method = $reflectionClass->getMethod($methodName);
        $obj = $reflectionClass->newInstance($request, $response, $needsAuthentication, $app);
        return $method->invoke($obj);
    } catch (NotAuthenticatedException $ex) {
        return $response->withHeader('Location', '/user/login');
    } catch (Exception $ex) {
        $app->logger->error($ex);
        throw new \RuntimeException($ex->getMessage());
    }
}

function getControllerName($pathComponent) {
    return strtoupper(substr($pathComponent, 0, 1)) . substr($pathComponent, 1) . 'Controller';
}

// TODO.... logger is set up in the DIC and now it should be used like this
// inside the router $this->logger->addInfo('Something interesting happened');

/*
$app->get('/section/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('SectionController', $method, $request, $response, $this, false);
});

$app->post('/section/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('SectionController', $method, $request, $response, $this);
});
 * 
 */

$app->get('/', function ($request, $response, $args) use ($app) {
    $response = $this->view->render($response, '/user/login.php', ['error' => '']);
    return $response;
});

/*
$app->get('/user/login', function ($request, $response, $args) use ($app) {
    return invokeControllerWithMethod('UserController', 'getLogin', $request, $response, $this, false);
});

$app->get('/user/logout', function ($request, $response, $args) use ($app) {
    return invokeControllerWithMethod('UserController', 'doLogout', $request, $response, $this, false);
});

$app->post('/user/login', function ($request, $response, $args) use ($app) {
    return invokeControllerWithMethod('UserController', 'doLogin', $request, $response, $this, false);
});

$app->get('/admin/panel', function ($request, $response, $args) use ($app) {
    return invokeControllerWithMethod('AdminController', 'panel', $request, $response,$this);
});

$app->get('/content', function ($request, $response, $args) use ($app) {
   $method = '';
    return invokeControllerWithMethod('ContentController', $method, $request, $response, $this);
});


$app->get('/content/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('ContentController', $method, $request, $response, $this);
});


$app->get('/product/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('ProductController', $method, $request, $response, $this);
});

$app->post('/product/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('ProductController', $method, $request, $response, $this);
});

$app->get('/printer/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('PrinterController', $method, $request, $response, $this);
});

$app->get('/image/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('ImageController', $method, $request, $response, $this);
});

$app->post('/printer/{method}', function ($request, $response, $args) use ($app) {
    $method = $args['method'];
    return invokeControllerWithMethod('PrinterController', $method, $request, $response, $this);
});
 * 
 */

$app->get('/test', function ($request, $response, $args) use ($app) {
    $response = $this->view->render($response, '/tests/sizestests.php', ['error' => '']);
    return $response;
});

$app->get('/{controller}', function ($request, $response, $args) use ($app) {
    $controller = $args['controller'];
    $method = 'index';
    return invokeControllerWithMethod($controller, $method, $request, $response, $this);
});

$app->get('/{controller}/{method}', function ($request, $response, $args) use ($app) {
    $controller = $args['controller'];
    $method = $args['method'];
    return invokeControllerWithMethod($controller, $method, $request, $response, $this);
});

$app->post('/{controller}/{method}', function ($request, $response, $args) use ($app) {
    $controller = $args['controller'];
    $method = $args['method'];
    return invokeControllerWithMethod($controller, $method, $request, $response, $this);
});

$app->run();