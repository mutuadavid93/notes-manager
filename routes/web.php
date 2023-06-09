<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    NoteController,
    TrashedNoteController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ListNotes app routes ::

// Auth middleware allows only logged in users to access a route
// TIP: Laravel takes care of naming all the routes created with resource()
Route::resource("/notes", NoteController::class)->middleware(["auth"]);

/*
// Trashed Notes Route.
// Only authenticated users can view their trashed notes
// Also give the route a name
Route::get("/trashed", [TrashedNoteController::class, 'index'])
->middleware(["auth"])
->name("trashed.index");
// Show a soft deleted note
Route::get("/trashed/{note}", [TrashedNoteController::class, 'show'])
// Only Trashed Notes
->withTrashed()
->middleware(["auth"])
->name("trashed.show");
Route::put("/trashed/{note}", [TrashedNoteController::class, 'update'])
->withTrashed()
->middleware(["auth"])
->name("trashed.update");
Route::put("/trashed/{note}", [TrashedNoteController::class, 'destroy'])
->withTrashed()
->middleware(["auth"])
->name("trashed.destroy");
*/

Route::prefix("/trashed")->name("trashed.")->middleware(["auth"])->group(function () {
    Route::get("/", [TrashedNoteController::class, 'index'])->name("index");
    Route::get("/{note}", [TrashedNoteController::class, 'show'])->name("show")->withTrashed();
    Route::put("/{note}", [TrashedNoteController::class, 'update'])->name("update")->withTrashed();
    Route::delete("/{note}", [TrashedNoteController::class, 'destroy'])->name("destroy")->withTrashed();
});

require __DIR__ . '/auth.php';
