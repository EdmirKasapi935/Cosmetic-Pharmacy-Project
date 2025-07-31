<?php

require './src/Router.php';
require './src/Controller.php';
require './src/Controllers/HomeController.php';
require './src/Controllers/AdminController.php';
require './src/Controllers/MailController.php';

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\MailController;
use App\Router;

$router = new Router();
// home routes
$router->get('/', HomeController::class, 'index');

$router->get('/contactUs', HomeController::class, 'contactUs');
$router->get('/ourMission', HomeController::class, 'ourMission');
// admin routes
$router->get('/admin', AdminController::class, 'index');
$router->get('/adminLogin', AdminController::class, 'showLogin');
$router->post('/adminLoginProcess', AdminController::class, 'processLogIn');
$router->post('/adminLogout', AdminController::class, 'processLogOut');
$router->get('/admin/addProduct', AdminController::class, 'showAddForm');
$router->post('/admin/addProductReq', AdminController::class, 'addProduct');
$router->post('/admin/deleteProduct', AdminController::class, 'deleteProduct');
$router->get('/admin/editProduct', AdminController::class, 'showEditForm');
$router->post('/admin/editProductReq',AdminController::class,'editProduct');
$router->post('/admin/insertImage',AdminController::class,'insertImage');
$router->post('/admin/removeImage',AdminController::class,'removeImage');
$router->get('/admin/customerMenu',AdminController::class,'showCustomersMenu');
$router->get('/admin/messagePanel',AdminController::class,'showMessagePanel');
$router->post('/admin/deliverMessage',AdminController::class,'deliverMessage');
$router->post('/admin/unsubCustomer',AdminController::class,'unsubCustomer');
// Mailer Routes
$router->post('/sendcontact', MailController::class, 'recieveContactForm');
$router->post('/getsubscription', MailController::class, 'recieveSubscription');

$router->dispatch();