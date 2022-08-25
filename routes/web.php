<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Picqer\Barcode\BarcodeGeneratorJPG;

Route::get('test', function () {
    $generator = new BarcodeGeneratorJPG();
    $resource = $generator->getBarcode('12345678', $generator::TYPE_EAN_13, 2, 100);

    $border = 10;
    $im = Image::make($resource);
    $width = $im->getWidth();
    $height = $im->getHeight();
    $img_adj_width = $width + (2 * $border);
    $img_adj_height = $height + (2 * $border);

    $im->resizeCanvas($img_adj_width, $img_adj_height)->save('test.jpg');

    echo '<img src="data:image/png;base64,'.base64_encode($im).'">';
});

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Customer
    Route::resource('customers', 'CustomerController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Statistics
    Route::get('statistics', 'StatisticsController@index')->name('statistics.index');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
