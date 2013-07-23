<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();

//Mode debug activé
$app['debug'] = true;


/*** CONFIG DES ERREURS ******/
$app->error(function(\Exception $e) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        $content = vsprintf('<h1>%d - %s (%s)</h1>', array(
           $e->getStatusCode(),
           Response::$statusTexts[$e->getStatusCode()],
           $app['request']->getRequestUri()
        ));
        return new Response($content, $e->getStatusCode());
    }
    if ($e instanceof HttpException) {
        return new Response('<h1>You should go eat some cookies while we\'re fixing this feature!</h1>', $e->getStatusCode());
    }
});

/****** CONFIG DE TWIG ******/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));



/****** ROUTING ********/
$app->get('/', function () use ($app) {
   return $app['twig']->render('index.twig', array(
        'hello' => "hello you",
    ));
});

return $app;

?>