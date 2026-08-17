<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'penjualan'], function () use ($router) {
    $router->get('/', function () {
        return response()->json([
            [
                "id" => 1,
                "nomor" => "SALE/001",
                "customer" => "Budi"
            ],
            [
                "id" => 2,
                "nomor" => "SALE/0012",
                "customer" => "Mulyon"
            ],
        ]);
    });
    $router->get('/{id}', function ($id) {
        return response()->json(['data' => [
                "id" => 2,
                "nomor" => "SALE/0012",
                "customer" => "Mulyon",
                "total" => 1000000,
                "alamat" => "Bandung"
            ]]);
    });
    $router->post('/', function() {
        return response()->json([
            'msg' => 'berhasil',
            'data' => 4
        ]);
    });
    $router->put('/{id}', function (Request $request, $id) {
        $nomor = $request->input('nomor');
        return response()->json(['data' => [
                "id" => $id,
                "nomor" => "$nomor",
                "customer" => "Mulyon",
                "total" => 1000000,
                "alamat" => "Bandung"
            ]]);
    });
    $router->delete('/{id}', function($id) {
        return response()->json(['data' => "data berhasil dihapus"]);
    });
});
