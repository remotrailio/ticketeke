<?php

use App\Http\Controllers\OrganizerOnboardingController;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events/{event}', function (Event $event) {
    // Event resolved by slug via getRouteKeyName()
    // Replace closure with a controller when building the public UI
    abort_unless($event->status->value === 'published', 404);
    abort_unless($event->visibility->value === 'public', 404);

    return response()->json($event);
})->name('events.show');

Route::get('/categories/{category}', function (Category $category) {
    // Category resolved by slug via getRouteKeyName()
    // Replace closure with a controller when building the public UI
    abort_unless($category->is_active, 404);

    return response()->json($category);
})->name('categories.show');

Route::middleware('auth')->group(function () {
    Route::get('/become-organizer', [OrganizerOnboardingController::class, 'show'])
        ->name('organizer.onboard');
    Route::post('/become-organizer', [OrganizerOnboardingController::class, 'store'])
        ->name('organizer.onboard.store');
});
