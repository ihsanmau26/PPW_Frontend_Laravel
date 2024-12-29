<?php

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

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');

Route::get('/reset-password', function () {
    return view('reset-password', ['token' => request()->get('token'), 'email' => request()->get('email')]);
})->name('reset-password');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::group(['prefix' => 'articles'], function () {
    Route::get('/', function () {
        return view('articles');
    })->name('articles.index');

    Route::get('/add', function () {
        return view('articles-add');
    })->name('articles.add');

    Route::get('{articleId}/detail', function () {
        return view('articles-detail');
    })->name('articles.detail');

    Route::get('{articleId}/edit', function () {
        return view('articles-edit');
    })->name('articles.edit');
});

Route::group(['prefix' => 'checkups'], function () {
    Route::get('/', function () {
        return view('checkups');
    })->name('checkups.index');

    Route::get('/add', function () {
        return view('checkups-add');
    })->name('checkups.add');

    Route::get('{checkupId}/detail', function () {
        return view('checkups-detail');
    })->name('checkups.detail');

    Route::get('{checkupId}/edit', function () {
        return view('checkups-edit');
    })->name('checkups.edit');

    Route::get('/{checkupId}/prescription', function ($checkupId) {
        return view('checkups-prescription', ['checkupId' => $checkupId]);
    })->name('checkups.prescription.add');
});

Route::group(['prefix' => 'histories'], function () {
    Route::get('/', function () {
        return view('histories');
    })->name('histories.index');

    Route::get('{historyId}/detail', function () {
        return view('histories-detail');
    })->name('histories.detail');

    Route::get('{historyId}/edit', function () {
        return view('histories-edit');
    })->name('histories.edit');
});

Route::group(['prefix' => 'medicines'], function () {
    Route::get('/', function () {
        return view('medicines');
    })->name('medicines.index');

    Route::get('/add', function () {
        return view('medicines-add');
    })->name('medicines.add');

    Route::get('{medicineId}/detail', function () {
        return view('medicines-detail');
    })->name('medicines.detail');

    Route::get('{medicineId}/edit', function () {
        return view('medicines-edit');
    })->name('medicines.edit');
});

Route::group(['prefix' => 'shifts'], function () {
    Route::get('/', function () {
        return view('shifts');
    })->name('shifts.index');

    Route::get('/add', function () {
        return view('shifts-add');
    })->name('shifts.add');

    Route::get('{shiftId}/detail', function () {
        return view('shifts-detail');
    })->name('shifts.detail');

    Route::get('{shiftId}/edit', function () {
        return view('shifts-edit');
    })->name('shifts.edit');
});

Route::group(['prefix' => 'doctors'], function () {
    Route::get('/', function () {
        return view('doctors');
    })->name('doctors.index');

    Route::get('/add', function () {
        return view('doctors-add');
    })->name('doctors.add');

    Route::get('{doctorId}/detail', function () {
        return view('doctors-detail');
    })->name('doctors.detail');

    Route::get('{userId}/edit', function () {
        return view('doctors-edit');
    })->name('doctors.edit');
});

Route::group(['prefix' => 'patients'], function () {
    Route::get('/', function () {
        return view('patients');
    })->name('patients.index');

    Route::get('/add', function () {
        return view('patients-add');
    })->name('patients.add');

    Route::get('{patientId}/detail', function () {
        return view('patients-detail');
    })->name('patients.detail');

    Route::get('{userId}/edit', function () {
        return view('patients-edit');
    })->name('patients.edit');
});

Route::get('/profiles', function () {
    return view('profiles');
})->name('profiles.index');