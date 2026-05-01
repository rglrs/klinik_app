<?php
namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $penyakit = Penyakit::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->where(function($q) use ($term) {
                    $q->whereRaw('LOWER(nama_penyakit) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(kode_icd) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(kategori) LIKE ?', ["%{$term}%"]);
                });
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('medis.penyakit.index', compact('penyakit'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nama_penyakit' => 'required|string|max:255',
            'kode_icd' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);
        Penyakit::create($validated);
        return redirect()->route('medis.penyakit.index')->with('success', 'Penyakit berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nama_penyakit' => 'required|string|max:255',
            'kode_icd' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);
        Penyakit::findOrFail($id)->update($validated);
        return redirect()->route('medis.penyakit.index')->with('success', 'Penyakit berhasil diperbarui.');
    }

    public function destroy($id) {
        Penyakit::findOrFail($id)->delete();
        return redirect()->route('medis.penyakit.index')->with('success', 'Penyakit berhasil dihapus.');
    }
}