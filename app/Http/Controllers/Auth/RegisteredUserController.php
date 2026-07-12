<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $activeYear = AcademicYear::getActiveYear();

        return view('auth.register', [
            'registrationOpen' => $this->registrationIsOpen($activeYear),
            'activeYear'       => $activeYear,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // jaga-jaga kalau ada yang submit langsung tanpa lewat halaman create()
        if (! $this->registrationIsOpen(AcademicYear::getActiveYear())) {
            return back()->withErrors([
                'registration' => 'Pendaftaran sudah ditutup.',
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name.required'      => 'Nama wajib diisi.',
            'name.max'           => 'Nama maksimal 255 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Cek apakah periode pendaftaran masih buka berdasarkan tahun ajaran aktif.
     * Buka kalau ada tahun ajaran yang is_active = true DAN belum melewati end_date.
     */
    private function registrationIsOpen(?AcademicYear $activeYear): bool
    {
        if (! $activeYear) {
            return false;
        }

        // pakai endOfDay supaya user masih bisa daftar sampai jam 23:59
        // di tanggal end_date, bukan langsung ketutup pas tengah malam
        return Carbon::now()->lte($activeYear->end_date->copy()->endOfDay());
    }
}