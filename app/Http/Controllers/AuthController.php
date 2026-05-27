<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', Rule::in([User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_ORGANIZATION, User::ROLE_ADMIN])],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role !== $credentials['role']) {
            return back()->withErrors([
                'email' => 'No account found with this email for the selected role.',
            ])->onlyInput('email');
        }

        if ($user->status === User::STATUS_INACTIVE) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ])->onlyInput('email');
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();

            return $this->redirectToDashboard($user, true);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $role = $request->input('role', User::ROLE_STUDENT);

        $rules = [
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::ROLE_STUDENT, User::ROLE_INSTRUCTOR, User::ROLE_ORGANIZATION])],
        ];

        if ($role === User::ROLE_ORGANIZATION) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['address'] = ['nullable', 'string', 'max:500'];
        } else {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
        }

        if ($role === User::ROLE_INSTRUCTOR) {
            $rules['designation'] = ['required', 'string', 'max:255'];
        }

        $data = $request->validate($rules);

        if ($role === User::ROLE_ORGANIZATION) {
            $data['first_name'] = null;
            $data['last_name'] = null;
            $data['designation'] = null;
        } else {
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
            $data['address'] = null;
        }

        $data['password'] = Hash::make($data['password']);
        $data['status'] = User::STATUS_ACTIVE;

        $user = User::create($data);

        Auth::login($user);

        $request->session()->regenerate();

        return $this->redirectToDashboard($user);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'designation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $user->$key = $value;
            }
        }

        if ($user->first_name && $user->last_name && !$user->name) {
            $user->name = $user->first_name . ' ' . $user->last_name;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function becomeInstructor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'designation' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_INSTRUCTOR,
            'designation' => $validated['designation'] ?? null,
            'bio' => $validated['about'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('instructor.dashboard.dashboard')
            ->with('success', 'Welcome! Your instructor account has been created.');
    }

    private function redirectToDashboard(User $user, bool $useIntended = false): RedirectResponse
    {
        $url = match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard.dashboard'),
            User::ROLE_INSTRUCTOR => route('instructor.dashboard.dashboard'),
            User::ROLE_ORGANIZATION => route('org.dashboard.dashboard'),
            default => route('dashboard'),
        };

        return $useIntended ? redirect()->intended($url) : redirect($url);
    }
}
