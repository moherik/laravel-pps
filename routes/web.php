<?php

use App\Http\Livewire\CandidateLivewire;
use App\Http\Livewire\RoomLivewire;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return redirect()->route('main');
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {
    Route::get('/', RoomLivewire::class)->name('main');
    Route::get('/rooms/{id}/candidates', CandidateLivewire::class)->name('candidate');

    Route::get('/recap/{title}/{ids}', function($title, $ids) {
        $ids = explode(',', $ids);
        $rooms = Room::whereIn('id', $ids)->get();
        $pdf = PDF::loadView('pdf-recap', ['rooms' => $rooms, 'title' => $title]);
        $filename = $title . ' - ' . now()->format('d-m-Y') . '.pdf';
        return $pdf->stream($filename);
        // return view('pdf-recap', ['rooms' => $rooms, 'title' => $title]);
    })->name('recap');
});