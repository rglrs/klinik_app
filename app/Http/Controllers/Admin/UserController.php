<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $users = User::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->where(function($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(email) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(role) LIKE ?', ["%{$term}%"]);
                });
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'dokter', 'perawat'])],
        ]);
        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id, 'id_user')],
            'role' => ['required', Rule::in(['admin', 'dokter', 'perawat'])],
        ]);
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }
        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }
}