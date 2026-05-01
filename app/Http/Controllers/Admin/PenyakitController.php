<?php
namespace App\Http\Controllers\Admin;

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
                $query->whereRaw('LOWER(nama_penyakit) LIKE ?', ["%{$term}%"]);
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('admin.penyakit.index', compact('penyakit'));
    }

    public function store(Request $request) {
        $validated = $request->validate(['nama_penyakit' => 'required|string|max:255']);
        Penyakit::create($validated);
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit ditambahkan.');
    }

    public function update(Request $request, $id) {
        $penyakit = Penyakit::findOrFail($id);
        $validated = $request->validate(['nama_penyakit' => 'required|string|max:255']);
        $penyakit->update($validated);
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit diperbarui.');
    }

    public function destroy($id) {
        Penyakit::findOrFail($id)->delete();
        return redirect()->route('admin.penyakit.index')->with('success', 'Penyakit dihapus.');
    }
}