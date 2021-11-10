<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $link = mysqli_connect("localhost","root","","bulletin-board");
        //$result = mysqli_query($link,'SELECT*FROM board');
        return $response;
    });

    $app->post('/api', function (Request $request, Response $response) {
        $params = $request->getQueryParams();
        $link = mysqli_connect("localhost","root","","bulletin-board");
        $stmt = mysqli_prepare($link,"INSERT INTO board(name,messages) VALUES(?,?)");
        mysqli_stmt_bind_param($stmt, "ss", $params["name"], $params["messages"]);
        $result = mysqli_stmt_execute($stmt);
        mysqli_close($link);
        $response->getBody()->write($result);
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
