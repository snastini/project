<?php

use App\Controllers\AuthController;
use App\Controllers\DepartmentController;
use App\Controllers\Home;
use App\Controllers\LeaveController;
use App\Controllers\LeaveTypeController;
use App\Controllers\PasswordResetController;
use App\Controllers\User;
use App\Controllers\WebhookController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

$routes->get('/', [Home::class, 'index']);

$routes->group('api', static function ($routes) {
    $routes->options('(:any)', static function(){});

    $routes->post('hit-webhook', [WebhookController::class,'hitWebhook']);
    $routes->post('login', [AuthController::class,'login']);
    $routes->post('register', [AuthController::class,'register']);
    $routes->post('send-reset-link', [PasswordResetController::class,'sendResetLink']);
    $routes->get('profile', [AuthController::class,'getProfile'] ,['filter' => 'custom-jwt']);
    $routes->post('reset-password', [PasswordResetController::class,'resetPassword']);

    $routes->group('users', static function ($routes) {
        $routes->get('/', [User::class,'index']);
    });

    $routes->group('leave-types', static function ($routes) {
        $routes->get('', [LeaveTypeController::class,'index']);
        $routes->post('', [LeaveTypeController::class,'store']);
        $routes->get('(:num)', [LeaveTypeController::class, 'show']);
        $routes->put('(:num)', [LeaveTypeController::class, 'update']);
        $routes->delete('(:num)', [LeaveTypeController::class, 'delete']);
    });

    $routes->group('leaves', static function ($routes) {
        $routes->get('', [LeaveController::class,'index']);
        $routes->post('', [LeaveController::class,'store']);
        $routes->get('(:num)', [LeaveController::class, 'show']);
        $routes->put('(:num)', [LeaveController::class, 'update']);
        $routes->delete('(:num)', [LeaveController::class, 'delete']);
    });

    $routes->group('departments', ['filter' => 'custom-jwt'], static function ($routes) {
        $routes->get('', [DepartmentController::class,'index'], ['filter' => ['custom-jwt', 'permission:view_departments']]);
        $routes->post('', [DepartmentController::class,'store']);
        $routes->get('(:num)', [DepartmentController::class, 'show']);
        $routes->put('(:num)', [DepartmentController::class, 'update']);
        $routes->delete('(:num)', [DepartmentController::class, 'delete']);
    });
});
