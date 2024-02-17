<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    //Vote
    $router->resource('/product', AProductController::class);
    $router->resource('/language', ALanguageController::class);
    $router->resource('/program', AProgramController::class);
    $router->resource('/province', AProvinceController::class);
    $router->resource('/zone', AZoneController::class);
    $router->resource('/program-product', AProgramProductController::class);
    $router->resource('/extension', AExtensionController::class);
    $router->resource('/layout', ALayoutController::class);
    $router->resource('/vote-banner', AVoteBannerController::class);
    $router->resource('/vote-banner-detail', AVoteBannerDetailController::class);
    $router->resource('/module', AModuleController::class);
    $router->resource('/module-detail', AModuleDetailController::class);
    $router->resource('/config-seo', AConfigSeoController::class);
    $router->resource('/vote', AVoteController::class);

});
