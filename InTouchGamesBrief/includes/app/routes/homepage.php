<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
/*
*   This File defines all routes for the homepage. This redirects to Api routes based on form post data.
*/

// Application route - Homepage.
$app->get('/', function (Request $request, Response $response) use ($app)
{
    return $this->view->render($response, 'homepage.twig', [
        'url' => ROOT_URL,
    ]);
});
// Post route for generate key button.
$app->post('/genkey', function (Request $request, Response $response) use ($app, $database)
{
    return $this->view->render($response, 'homepage.twig', [
        'key' => 'value = "' . $database->createApiKey() . '"',
        'url' => ROOT_URL,]);
});
//Post route for submitted forms. Redirects to api routes depending on data input.
$app->post('/formsubmit', function (Request $request, Response $response) use ($app, $database)
{
    switch($request->getParams()['endpoints']) {
        case 'select':
        
        break;
        case 'create':

            return $response->withRedirect(ROOT_URL . "/create/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['firstname'] . "/" . $request->getParams()['surname'] . "/" . $request->getParams()['dob'] . "/" . $request->getParams()['phone_number'] . "/"  . $request->getParams()['email']);
        break;
        case 'getallusers':
            return $response->withRedirect(ROOT_URL . "/getall/" . $request->getParams()['inputkey']);
        break;
        case 'getuser':
            return $response->withRedirect(ROOT_URL . "/get/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid']);
        break;
        case 'edituser':
            switch($request->getParams()['editoptions']) {
                case 'firstname':
                    return $response->withRedirect(ROOT_URL . "/edit/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid'] . "/1/" . $request->getParams()['firstname']);
                break;
                case 'surname':
                    return $response->withRedirect(ROOT_URL . "/edit/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid'] . "/2/" . $request->getParams()['surname']);
                break;
                case 'dob':
                    return $response->withRedirect(ROOT_URL . "/edit/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid'] . "/3/" . $request->getParams()['dob']);
                break;
                case 'phone':
                    return $response->withRedirect(ROOT_URL . "/edit/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid'] . "/4/" . $request->getParams()['phone_number']);
                break;
                case 'email':
                    return $response->withRedirect(ROOT_URL . "/edit/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid'] . "/5/" . $request->getParams()['email']);
                break;
            }
        break;
        case 'deleteuser':
            return $response->withRedirect(ROOT_URL . "/delete/" . $request->getParams()['inputkey'] . "/" . $request->getParams()['userid']);
        break;
        }

});