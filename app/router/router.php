<?php
function routes(): array
{
    return require 'routes.php';
}

function getUriInArrayRoutes($uri, $routes): array
{
    if (array_key_exists($uri, $routes)) {
        return [$uri => $routes[$uri]];
    }
    return [];
}

function getDimamicUri($uri, $routes):array
{
    return array_filter(
        $routes,
        function ($value) use ($uri) {
            $regex = str_replace('/', '\/', ltrim($value, '/'));
            return preg_match("/^$regex$/", ltrim($uri, '/'));
        },
        ARRAY_FILTER_USE_KEY
    );
}
function params($uri, $matchedUri):array
{
    if(!empty($matchedUri)){
        $matchedToGetParams = array_keys($matchedUri)[0];
        return array_diff(
            explode('/', ltrim($uri, '/')),
            explode('/', ltrim($matchedToGetParams, '/'))
        );
    }
    return [];
}
function paramsFormat($uri, $params):array{
    $uri = explode('/',ltrim($uri, '/'));
    $paramsData = [];
    foreach($params as $index => $param){
        $paramsData[$uri[$index - 1]] = $param;
    }
    return $paramsData;
}

function router():void
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $routes = routes();

    $matchedUri = getUriInArrayRoutes($uri, $routes);

    if (empty($matchedUri)) {
        $matchedUri = getDimamicUri($uri,$routes);
        $params = params($uri, $matchedUri);
        $params = paramsFormat($uri, $params);
        var_dump($params);
        die();
    }
    var_dump($matchedUri);
    
}
