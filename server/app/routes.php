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

    $app->get('/api/page', function (Request $request, Response $response) {
        $link = mysqli_connect("localhost","root","","bulletin-board");
        $result = mysqli_query($link,'SELECT*FROM board');
        $messages = mysqli_fetch_all($result);
        mysqli_close($link);

        $response->getBody()->write(json_encode($messages, JSON_UNESCAPED_UNICODE));
        return $response;
    });

    $app->post('/api/page', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        $link = mysqli_connect("localhost","root","","bulletin-board");
        $stmt = mysqli_prepare($link,"INSERT INTO board(name,messages) VALUES(?,?)");
        mysqli_stmt_bind_param($stmt, "ss", $params["name"], $params["messages"]);
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_close($link);

        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        return $response;
    });

    $app->post('/api/page/put', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        $link = mysqli_connect("localhost","root","","bulletin-board");
        $stmt = mysqli_prepare($link,"UPDATE board set name = ?, messages = ? where id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $params["name"], $params["messages"], $params["id"]);
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_close($link);
        
        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        return $response;
    });

    $app->post('/api/page/delete', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        $link = mysqli_connect("localhost","root","","bulletin-board");
        $stmt = mysqli_prepare($link,"DELETE FROM board WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $params["id"]);
        $result = mysqli_stmt_execute($stmt);
        
        mysqli_close($link);
        
        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
