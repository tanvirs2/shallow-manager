<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Payment;
use App\Models\User;
use App\Models\WaterEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /* ─── Dashboard ─── */
    public function index()
    {
        $totalOwners  = User::where('is_admin', false)->count();
        $activeOwners = User::where('is_admin', false)
            ->where('expires_at', '>', now())
            ->count();
        $expiredOwners = $totalOwners - $activeOwners;
        $expiringSoon  = User::where('is_admin', false)
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->count();

        $totalFarmers     = Farmer::count();
        $totalWaterEntries = WaterEntry::count();
        $totalPayments    = Payment::sum('amount');

        $recentUsers = User::where('is_admin', false)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $expiringUsers = User::where('is_admin', false)
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->orderBy('expires_at')
            ->get();

        return view('admin.dashboard', compact(
            'totalOwners', 'activeOwners', 'expiredOwners', 'expiringSoon',
            'totalFarmers', 'totalWaterEntries', 'totalPayments',
            'recentUsers', 'expiringUsers'
        ));
    }

    /* ─── User List ─── */
    public function users(Request $request)
    {
        $query = User::where('is_admin', false)->withCount(['farmers']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        if ($request->filter === 'active') {
            $query->where('expires_at', '>', now());
        } elseif ($request->filter === 'expired') {
            $query->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '<=', now()));
        } elseif ($request->filter === 'expiring') {
            $query->where('expires_at', '>', now())->where('expires_at', '<=', now()->addDays(7));
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /* ─── Create User ─── */
    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
            'duration_type'  => 'required|in:days,months,years',
            'duration_value' => 'required|integer|min:1|max:999',
        ]);

        $expiresAt = now()->{'add' . ucfirst($data['duration_type'])}($data['duration_value']);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'expires_at' => $expiresAt,
            'is_admin'   => false,
        ]);

        return redirect()->route('admin.users')
            ->with('success', "✅ {$user->name} এর অ্যাকাউন্ট তৈরি হয়েছে। মেয়াদ: " . $expiresAt->format('d/m/Y'));
    }

    /* ─── Edit User ─── */
    public function editUser(User $user)
    {
        abort_if($user->is_admin, 403, 'Admin ব্যবহারকারী এডিট করা যাবে না।');
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        abort_if($user->is_admin, 403);

        $rules = [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6', 'confirmed'];
        }

        $data = $request->validate($rules);

        $update = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if ($request->filled('password')) {
            $update['password'] = Hash::make($request->password);
        }

        $user->update($update);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', '✅ তথ্য আপডেট হয়েছে।');
    }

    /* ─── Grant / Extend Access ─── */
    public function grantAccess(Request $request, User $user)
    {
        abort_if($user->is_admin, 403);

        $data = $request->validate([
            'duration_type'  => 'required|in:days,months,years',
            'duration_value' => 'required|integer|min:1|max:999',
            'extend'         => 'nullable|boolean',
        ]);

        $base = ($request->boolean('extend') && $user->expires_at?->isFuture())
            ? $user->expires_at->copy()
            : now();

        $newExpiry = $base->{'add' . ucfirst($data['duration_type'])}($data['duration_value']);
        $user->update(['expires_at' => $newExpiry]);

        return redirect()->route('admin.users.edit', $user)
            ->with('success', "✅ মেয়াদ দেওয়া হয়েছে। নতুন মেয়াদ: " . $newExpiry->format('d/m/Y'));
    }

    /* ─── Revoke Access ─── */
    public function revokeAccess(User $user)
    {
        abort_if($user->is_admin, 403);
        $user->update(['expires_at' => now()->subDay()]);

        return redirect()->route('admin.users.edit', $user)
            ->with('warning', "⚠️ {$user->name} এর অ্যাক্সেস বাতিল করা হয়েছে।");
    }

    /* ─── Delete User ─── */
    public function deleteUser(User $user)
    {
        abort_if($user->is_admin, 403, 'Admin ডিলিট করা যাবে না।');
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "🗑️ {$name} এর অ্যাকাউন্ট মুছে ফেলা হয়েছে।");
    }
}
