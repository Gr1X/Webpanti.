<?php

use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AdminProgramController;
use App\Http\Controllers\UserProgramController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EditPasswordController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\EditGalleryController;
use App\Http\Controllers\UserGalleryController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\newsController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\BeritaPantiController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsRegularUser;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// -----------------------------
// Landing Page and Static Pages (User Routes)
// -----------------------------

Route::get('/', [LandingController::class, 'index'])->name('landing');  // Halaman utama

Route::get('/gallery', [UserGalleryController::class, 'index'])->name('gallery');
Route::get('/program', [ProgramController::class, 'index'])->name('program');
Route::view('/aboutus', 'user.aboutUs')->name('aboutus');

Route::get('/berita/{id}', [BeritaPantiController::class, 'details'])->name('berita.details');

Route::get('/donation', [UserProgramController::class, 'showDonations'])->name('donation');
Route::get('/donation/{id}', [UserProgramController::class, 'showDonationDetails'])->name('donateDetails');
Route::get('/donation/{id}/payment', [DonasiController::class, 'showPaymentForm'])->name('donation.payment');
Route::post('/donation/{id}/payment/confirm', [DonasiController::class, 'submitPaymentConfirm'])->name('donation.confirm');
Route::post('/donation/{id}/submit', [DonasiController::class, 'submitDonation'])->name('donation.submit');

// -----------------------------
// Auth Routes (Login, Register, Forgot/Reset Password)
// -----------------------------

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    // Forgot Password
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'passwordReset'])->name('password.update');
});

// -----------------------------
// Authenticated User Routes
// -----------------------------

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

   // Donation Routes
    // Route::get('/donation/{id}/payment', [DonasiController::class, 'showPaymentForm'])->name('donation.payment');
    // Route::post('/donation/{id}/payment/confirm', [DonasiController::class, 'submitPaymentConfirm'])->name('donation.confirm');
    // Route::post('/donation/{id}/submit', [DonasiController::class, 'submitDonation'])->name('donation.submit');


    // Volunteer Routes
    Route::get('/program/volunteer/daftar/{bidang}', [VolunteerController::class, 'showRegisterForm'])->name('program.volunteer.registerForm');
    Route::post('/program/volunteer/daftar/{bidang}', [VolunteerController::class, 'register'])->name('program.volunteer.register');

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// -----------------------------
// Admin Routes
// -----------------------------

Route::middleware(['auth'])->prefix('admin')->group(function () {
// Periksa apakah user adalah admin sebelum grup routes diakses
Route::group(['middleware' => function ($request, $next) {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
}], function () {


    Route::get('dashboard', [AdminProgramController::class, 'dashboard'])->name('admin.dashboard');
    
      // Program Management
    Route::get('programs', [AdminProgramController::class, 'index'])->name('admin.programs.index');
    Route::get('/programs/create', [AdminProgramController::class, 'create'])->name('admin.programs.create');
    Route::post('/programs', [AdminProgramController::class, 'store'])->name('admin.programs.store');
    Route::get('/programs/{id}/edit', [AdminProgramController::class, 'edit'])->name('admin.programs.edit');
    Route::put('/programs/{id}', [AdminProgramController::class, 'update'])->name('admin.programs.update');
    Route::delete('/programs/{id}', [AdminProgramController::class, 'destroy'])->name('admin.programs.destroy');

      // Gallery Management
    Route::get('/gallery', [EditGalleryController::class, 'index'])->name('admin.gallery.index');
    Route::get('/gallery/create', [EditGalleryController::class, 'create'])->name('admin.gallery.create');
    Route::post('/gallery', [EditGalleryController::class, 'store'])->name('admin.gallery.store');
    Route::get('/gallery/{id}/edit', [EditGalleryController::class, 'edit'])->name('admin.gallery.edit');
    Route::put('/gallery/{id}', [EditGalleryController::class, 'update'])->name('admin.gallery.update');
    Route::delete('/gallery/{id}', [EditGalleryController::class, 'destroy'])->name('admin.gallery.destroy');

        // News Management
    Route::get('/news', [newsController::class, 'index'])->name('admin.news.index');
    Route::get('/news/create', [newsController::class, 'create'])->name('admin.news.create');
    Route::post('/news', [newsController::class, 'store'])->name('admin.news.store');
    Route::get('/news/{id}/edit', [newsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/news/{id}', [newsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}', [newsController::class, 'destroy'])->name('admin.news.destroy');

      // Tampilkan semua volunteer
    Route::get('/volunteers', [VolunteerController::class, 'showAllVolunteers'])->name('admin.volunteers.index');
    Route::delete('/volunteers/{id}', [VolunteerController::class, 'destroy'])->name('admin.volunteers.destroy');

    //Tampilkan payments
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::put('/payments/{id}/confirm', [AdminPaymentController::class, 'confirm'])->name('admin.payments.confirm');
    Route::put('/payments/{id}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');

    });
});

// -----------------------------
// Password Update Routes (for Authenticated Users)
// -----------------------------

Route::get('password/edit', [EditPasswordController::class, 'edit'])->name('password.edit');
Route::post('password/update', [EditPasswordController::class, 'update'])->name('password.update');

// -----------------------------
// Program and Volunteer Registration Routes (User Routes)
// -----------------------------

Route::get('/program/volunteer/daftar', function() {
    return view('user.programComponent.registerRelawan');
})->name('daftarRelawan'); 

Route::get('/program/details', function() {
    return view('user.programComponent.detailBerita');
})->name('programDetails'); 


Route::get('/program/volunteer', function() {
    return view('user.programComponent.daftarRelawan');
})->name('relawan');
