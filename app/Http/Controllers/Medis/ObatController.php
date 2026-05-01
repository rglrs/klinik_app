<?php
namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ObatController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $obat = Obat::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->where(function($q) use ($term) {
                    $q->whereRaw('LOWER(nama_obat) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(nama_batch) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(jenis_obat) LIKE ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(satuan) LIKE ?', ["%{$term}%"]);
                });
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('medis.obat.index', compact('obat'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'nama_batch' => 'required|string|max:255',
            'stok_saat_ini' => 'required|integer|min:0',
            'satuan' => 'required|string|max:255',
            'expired_date' => 'required|date',
            'reorder_level' => 'required|integer|min:0',
            'jenis_obat' => ['required', Rule::in(['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'tetes', 'puyer'])],
        ]);
        Obat::create($validated);
        return redirect()->route('medis.obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'nama_batch' => 'required|string|max:255',
            'stok_saat_ini' => 'required|integer|min:0',
            'satuan' => 'required|string|max:255',
            'expired_date' => 'required|date',
            'reorder_level' => 'required|integer|min:0',
            'jenis_obat' => ['required', Rule::in(['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'tetes', 'puyer'])],
        ]);
        Obat::findOrFail($id)->update($validated);
        return redirect()->route('medis.obat.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy($id) {
        Obat::findOrFail($id)->delete();
        return redirect()->route('medis.obat.index')->with('success', 'Obat berhasil dihapus.');
    }
}