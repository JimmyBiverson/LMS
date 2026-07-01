<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ProfileManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct(
        private readonly ProfileManagementService $profileService,
    ) {}
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No account found with this email.',
            ])->withInput($request->only('email', 'selected_role'));
        }

        if ($user->status === User::STATUS_INACTIVE) {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact support.',
            ])->withInput($request->only('email', 'selected_role'));
        }

        $selectedRole = $request->input('selected_role');
        if ($selectedRole && $user->role !== $selectedRole) {
            $roleLabels = [
                User::ROLE_STUDENT => 'Student',
                User::ROLE_INSTRUCTOR => 'Instructor',
                User::ROLE_ORGANIZATION => 'Organization',
                User::ROLE_ADMIN => 'Admin',
            ];
            $actualLabel = $roleLabels[$user->role] ?? ucfirst($user->role);
            $selectedLabel = $roleLabels[$selectedRole] ?? $selectedRole;
            return back()->withErrors([
                'email' => "This account is registered as {$actualLabel}, not {$selectedLabel}. Please select the correct tab.",
            ])->withInput($request->only('email', 'selected_role'));
        }

        if ($user->email_verified_at === null) {
            return back()->withErrors([
                'email' => 'Please verify your email address before logging in.',
            ])->withInput($request->only('email', 'selected_role'));
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();

            return $this->redirectToDashboard($user, true);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email', 'selected_role'));
    }

    public function adminLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->where('role', User::ROLE_ADMIN)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No admin account found with this email.']);
        }

        if ($user->status === User::STATUS_INACTIVE) {
            return back()->withErrors(['email' => 'Your account has been deactivated. Please contact support.']);
        }

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard.dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
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
        $user->email_verified_at = now();
        $user->save();

        if ($role === User::ROLE_INSTRUCTOR) {
            \App\Notifications\InstructorRegistered::send($user);
        }

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
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $this->profileService->updateProfile($user, $validated);

        if ($request->hasFile('profile_image')) {
            $this->profileService->uploadProfileImage($user, $request->file('profile_image'));
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
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

        $user->email_verified_at = now();
        $user->save();

        \App\Notifications\InstructorRegistered::send($user);

        Auth::login($user);

        return redirect()->route('instructor.pending-approval')
            ->with('success', 'Your instructor account has been created and is pending admin approval.');
    }

    private function redirectToDashboard(User $user, bool $useIntended = false): RedirectResponse
    {
        $url = match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard.dashboard'),
            User::ROLE_STAFF => route('admin.dashboard.dashboard'),
            User::ROLE_INSTRUCTOR => route('instructor.dashboard.dashboard'),
            User::ROLE_ORGANIZATION => route('org.dashboard.dashboard'),
            default => route('dashboard'),
        };

        if ($useIntended) {
            $intended = session()->get('url.intended');
            if ($intended) {
                if ($user->role === User::ROLE_INSTRUCTOR && !str_contains($intended, '/instructor')) {
                    session()->forget('url.intended');
                } elseif ($user->role === User::ROLE_ORGANIZATION && !str_contains($intended, '/org')) {
                    session()->forget('url.intended');
                } elseif (($user->role === User::ROLE_ADMIN || $user->role === User::ROLE_STAFF) && !str_contains($intended, '/admin')) {
                    session()->forget('url.intended');
                } elseif ($user->role === User::ROLE_STUDENT && (str_contains($intended, '/admin') || str_contains($intended, '/instructor') || str_contains($intended, '/org'))) {
                    session()->forget('url.intended');
                }
            }
            return redirect()->intended($url);
        }

        return redirect($url);
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required', 
                'confirmed', 
                \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);

        $this->profileService->updatePassword(
            $request->user(),
            $request->current_password,
            $request->password
        );

        return back()->with('status', 'Password changed successfully!');
    }
}
