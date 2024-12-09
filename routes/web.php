<?php

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleUseController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\ErrorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    //users
    Route::get('/userManagement', [UserController::class, 'AllUsers'])->middleware('isAdmin');
    Route::post('/createUser', [UserController::class, 'CreateUser'])->middleware('isAdmin')->name("createUser");  
    Route::post('/editUser', [UserController::class, 'UpdateUser'])->middleware('isAdmin')->name("updateUser");  
    Route::post('/deleteUser', [UserController::class, 'DeleteUser'])->middleware('isAdmin')->name("deleteUser");  
    Route::get('/createUser', [UserController::class, 'CreateUserView'])->middleware('isAdmin');  
    Route::get('/editUser', [UserController::class, 'EditUserView'])->middleware('isAdmin')->name("editUser");  
    
    //vehicles
    Route::get('/vehicleManagement', [VehicleController::class, 'AllVehicles'])->middleware('isAdmin');
    Route::post('/createVehicle', [VehicleController::class, 'CreateVehicle'])->middleware('isAdmin')->name("createVehicle");  
    Route::post('/editVehicle', [VehicleController::class, 'UpdateVehicle'])->middleware('isAdmin')->name("updateVehicle");  
    Route::post('/deleteVehicle', [VehicleController::class, 'DeleteVehicle'])->middleware('isAdmin')->name("deleteVehicle");  
    Route::get('/createVehicle', [VehicleController::class, 'CreateVehicleView'])->middleware('isAdmin');  
    Route::get('/editVehicle', [VehicleController::class, 'EditVehicleView'])->middleware('isAdmin')->name("editVehicle");  

    //vehicle uses
    Route::get('/vehicleUseManagement', [VehicleUseController::class, 'AllVehicleUses'])->middleware('isAdmin');
    Route::post('/createVehicleUse', [VehicleUseController::class, 'CreateVehicleUse'])->middleware('isAdmin')->name("createVehicleUse");  
    Route::post('/editVehicleUse', [VehicleUseController::class, 'UpdateVehicleUse'])->middleware('isAdmin')->name("updateVehicleUse");  
    Route::post('/deleteVehicleUse', [VehicleUseController::class, 'DeleteVehicleUse'])->middleware('isAdmin')->name("deleteVehicleUse");  
    Route::get('/createVehicleUse', [VehicleUseController::class, 'CreateVehicleUseView'])->middleware('isAdmin');  
    Route::get('/editVehicleUse', [VehicleUseController::class, 'EditVehicleUseView'])->middleware('isAdmin')->name("editVehicleUse");  

    //errors
    Route::get('/errorManagement', [ErrorController::class, 'AllErrors'])->middleware('isAdmin');
    Route::post('/createError', [ErrorController::class, 'CreateError'])->middleware('isAdmin')->name("createError");  
    Route::post('/editError', [ErrorController::class, 'UpdateError'])->middleware('isAdmin')->name("updateError");  
    Route::post('/deleteError', [ErrorController::class, 'DeleteError'])->middleware('isAdmin')->name("deleteError");  
    Route::get('/createError', [ErrorController::class, 'CreateErrorView'])->middleware('isAdmin');  
    Route::get('/editError', [ErrorController::class, 'EditErrorView'])->middleware('isAdmin')->name("editError");  
    Route::get('/viewError', [ErrorController::class, 'ViewError'])->middleware('isAdmin')->name("viewError");  

    //objects
    Route::get('/objectManagement', [ObjectController::class, 'AllObjects'])->middleware('isAdmin');
    Route::post('/createObject', [ObjectController::class, 'CreateObject'])->middleware('isAdmin')->name("createObject");  
    Route::post('/editObject', [ObjectController::class, 'UpdateObject'])->middleware('isAdmin')->name("updateObject");  
    Route::post('/deleteObject', [ObjectController::class, 'DeleteObject'])->middleware('isAdmin')->name("deleteObject");  
    Route::get('/createObject', [ObjectController::class, 'CreateObjectView'])->middleware('isAdmin');  
    Route::get('/editObject', [ObjectController::class, 'EditObjectView'])->middleware('isAdmin')->name("editObject");  

    //reservations
    Route::get('/reservationManagement', [ReservationController::class, 'AllReservations'])->middleware('isAdmin');
    Route::post('/createReservation', [ReservationController::class, 'CreateReservation'])->middleware('isAdmin')->name("createReservation");  
    Route::post('/editReservation', [ReservationController::class, 'UpdateReservation'])->middleware('isAdmin')->name("updateReservation");  
    Route::post('/deleteReservation', [ReservationController::class, 'DeleteReservation'])->middleware('isAdmin')->name("deleteReservation");  
    Route::get('/createReservation', [ReservationController::class, 'CreateReservationView'])->middleware('isAdmin');  
    Route::get('/editReservation', [ReservationController::class, 'EditReservationView'])->middleware('isAdmin')->name("editReservation");  
    
    //user 
    Route::post('/startUsing', [VehicleUseController::class, 'StartUsingVehicle'])->name("startUsing");  
    Route::get('/startUsing', [VehicleUseController::class, 'StartUsingVehicleView'])->name("startUsingView"); 
    Route::get('/confirm', [VehicleUseController::class, 'ConfirmVehicleUsage']); 
    Route::post('/stopUsing', [VehicleUseController::class, 'StopUsingVehicle'])->name("stopUsing");
    Route::get('/stopUsing', [VehicleUseController::class, 'StopUsingVehicleView']);
    Route::get('/myDrives', [VehicleUseController::class, 'MyVehicleUses'])->name("myVehicleUses");  
    Route::get('/myActiveDrives', [VehicleUseController::class, 'MyActiveVehicleUses'])->name("myActiveVehicleUses");  

    Route::get('/makeReservation', [ReservationController::class, 'MakeReservation'])->name("makeReservation");
    Route::post('/makeReservationOrStartUsing', [ReservationController::class, 'ReservationOrUse'])->name("makeReservationOrStartUsing");
    
    Route::post('/removeReservation', [ReservationController::class, 'RemoveReservation'])->name("removeReservation");   
    Route::get('/myReservations', [ReservationController::class, 'MyReservations'])->name("myReservations");
    
    Route::get('/vehicleReservationSelection', [ReservationController::class, 'GetReservationSelection'])->name("reservationS");
    
    Route::get('/user', [UserController::class, 'UserView']);
    
    Route::get('/calendarData', [CalendarController::class, 'GetCalendar']);
    Route::get('/todaysCalendarData', [CalendarController::class, 'GetTodaysCalendar']);
    Route::get('/logout', [UserController::class, 'Logout']);

    Route::get('/synchronizeObjects', [ObjectController::class, 'synchronizeObjects']);

    Route::get('/mail', [VehicleUseController::class, 'SendM']);
});
Route::view('/unauthorized', 'main.unauthorized')->name("unauthorized");
Route::get('/login', [UserController::class, 'ShowLogin'])->name("login");
Route::post('/login', [UserController::class, 'RecieveLogin']);
Route::view('/', 'main.publicPage')->name("public");