<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenagaMedis;
use App\Models\User;
use Illuminate\Http\Request;

class TenagaMedisController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $tenagaMedis = TenagaMedis::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->where(function($q) use ($term) {
                    $q->whereRaw('LOWER(nama_tenaga_medis) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(kode_tenaga_medis) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(jabatan) LIKE ?', ["%{$term}%"]);
                });
            }
        })->latest()->paginate($perPage)->appends($request->query());

        $users = User::whereIn('role', ['dokter', 'perawat'])->get();
        return view('admin.tenaga_medis.index', compact('tenagaMedis', 'users'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user|unique:tenaga_medis,id_user',
            'kode_tenaga_medis' => 'required|string|max:255',
            'nik' => 'required|string|unique:tenaga_medis,nik',
            'nama_tenaga_medis' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);
        TenagaMedis::create($validated);
        return redirect()->route('admin.tenaga-medis.index')->with('success', 'Data ditambahkan.');
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user|unique:tenaga_medis,id_user,'.$id.',id_tenaga_medis',
            'kode_tenaga_medis' => 'required|string|max:255',
            'nik' => 'required|string|unique:tenaga_medis,nik,'.$id.',id_tenaga_medis',
            'nama_tenaga_medis' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);
        TenagaMedis::findOrFail($id)->update($validated);
        return redirect()->route('admin.tenaga-medis.index')->with('success', 'Data diperbarui.');
    }

    public function destroy($id) {
        TenagaMedis::findOrFail($id)->delete();
        return redirect()->route('admin.tenaga-medis.index')->with('success', 'Data dihapus.');
    }
}