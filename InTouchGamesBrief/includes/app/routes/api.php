<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require __DIR__ . '/../src/validator.php';
$validator = new Validator;
/*
*   This File defines all Api routes. This implements the Validator class to validate inputs and the database instance to submit requests.
*/

//Route for creating user in database. Api key must be given with every request.
$app->get('/create/{key}/{name}/{surname}/{dob}/{phone}/{email}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    //Errors array holds is empty, if data inputs cannot pass validation then errors are added to the array and output to the user.
    $errors = array('errors' => '');
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    $name = $validator->sanitiseString($request->getAttribute('name'));
    $surname = $validator->sanitiseString($request->getAttribute('surname'));
    $dob = $validator->validateDate($request->getAttribute('dob'));
    if ($dob == false) {
        $errors['errors'] = $errors['errors'] . " Date of Birth invalid.";
    }
    $phone = $validator->validateInt($request->getAttribute('phone'));
    if ($phone == 0) {
        $errors['errors'] = $errors['errors'] . " Phone Number invalid.";
    }
    $email = $validator->sanitiseEmail($request->getAttribute('email'));
    if ($email == '') {
        $errors['errors'] = $errors['errors'] . " Email Address invalid.";
    }
    if ($errors['errors'] == '') {
    return $database->createUser($key, $name, $surname, $dob, $phone, $email);          
    } else {
        return json_encode($errors);
    }

});

//Route for editing a user in database. Column represents the index of the data point that the user wants to edit.
$app->get('/edit/{key}/{id}/{column}/{data}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    //Errors array holds is empty, if data inputs cannot pass validation then errors are added to the array and output to the user.
    $errors = array('errors' => '');
    switch ($request->getAttribute('column'))
    {
        case 1:
            $data = $validator->sanitiseString($request->getAttribute('data'));
        break;
        case 2:
            $data = $validator->sanitiseString($request->getAttribute('data'));
        break;
        case 3:
            $data = $validator->validateDate($request->getAttribute('data'));
            if ($data == false) {
                $errors['errors'] = $errors['errors'] . " Date of Birth invalid.";
            }
        break;
        case 4:
            $data = $validator->validateInt($request->getAttribute('data'));
            if ($data == 0) {
                $errors['errors'] = $errors['errors'] . " Phone Number invalid.";
            }
        break;
        case 5:
            $data = $validator->sanitiseEmail($request->getAttribute('data'));
            if ($data == '') {
                $errors['errors'] = $errors['errors'] . " Email Address invalid.";
            }
        break;
        default:
        $errors['errors'] = $errors['errors'] . " Column does not exist.";
    }
    
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    $id = $validator->validateInt($request->getAttribute('id'));
    if ($id == 0) {
        $errors['errors'] = $errors['errors'] . " User Id invalid.";
    }
    $column = $validator->validateInt($request->getAttribute('column'));

    if ($errors['errors'] == '') {
        return $database->editUser($key, $id, $column, $data);        
        } else {
            return json_encode($errors);
        }
});

//Route for deleting a user in database.
$app->get('/delete/{key}/{id}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    $errors = array('errors' => '');
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    $id = $validator->validateInt($request->getAttribute('id'));
    if ($id == 0) {
        $errors['errors'] = $errors['errors'] . " User Id invalid.";
    }
    if ($errors['errors'] == '') {
        return $database->deleteUser($key , $request->getAttribute('id'));      
        } else {
            return json_encode($errors);
        }
});
//Route for requesting user information in database.
$app->get('/get/{key}/{id}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    $errors = array('errors' => '');
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    $id = $validator->validateInt($request->getAttribute('id'));
    if ($id == 0) {
        $errors['errors'] = $errors['errors'] . " User Id invalid.";
    }

    if ($errors['errors'] == '') {
        return $database->readUser($key , $request->getAttribute('id'));   
        } else {
            return json_encode($errors);
        }

});
//Route for requesting all user information in database.
$app->get('/getall/{key}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    $errors = array('errors' => '');
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    
    if ($errors['errors'] == '') {
        return $database->readAllUsers($key);
        } else {
            return json_encode($errors);
        }
});
//Route for checking if an api key is valid in database.
$app->get('/checkkey/{key}', function (Request $request, Response $response) use ($app, $database, $validator)
{
    $errors = array('errors' => '');
    $key = $validator->validateApiKey($request->getAttribute('key'));
    if ($key == false) {
        $errors['errors'] = $errors['errors'] . " Api Key invalid.";
    }
    if ($errors['errors'] == '') {
        return json_encode($database->checkApiKey($key));
        } else {
            return json_encode($errors);
        }

});
//Route for generating a new Api key.
$app->post('/requestkey', function (Request $request, Response $response) use ($app, $database)
{
    return $database->createApiKey();
});


