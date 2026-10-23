<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = User::query()
            ->with('role:id,name')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->trim()->toString();
                $query->where(fn ($users) => $users->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->input('status') === 'locked', fn ($query) => $query->where('is_locked', true))
            ->when($request->input('status') === 'active', fn ($query) => $query->where('is_locked', false))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['success' => true, 'data' => $accounts]);
        }

        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.accounts.create', ['roles' => Role::query()->orderBy('name')->get(['id', 'name'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $user = User::create([
            ...$data,
            'password' => $data['password'],
            'is_admin' => false,
            'create_by' => $request->user()->id,
            'update_by' => $request->user()->id,
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->respond($request, $user, __('accounts.messages.created'), 201);
        }

        return redirect()->route('admin.accounts.index')->with('success', __('accounts.messages.created'));
    }

    private function validatedData(Request $request, ?User $account = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($account?->id)],
            'password' => [$account ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'integer', Rule::exists('roles', 'id')],
        ]);
    }

    private function respond(Request $request, User $account, string $message, int $status = 200)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $account->load('role:id,name')->only(['id', 'name', 'email', 'role_id', 'is_locked', 'created_at']),
            ], $status);
        }

        return redirect()->route('admin.accounts.index')->with('success', $message);
    }
}
