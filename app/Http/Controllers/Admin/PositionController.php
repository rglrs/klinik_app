<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $positions = Position::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('admin.positions.index', compact('positions'));
    }

    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:positions,name']);
        Position::create($validated);
        return redirect()->route('admin.positions.index')->with('success', 'Posisi ditambahkan.');
    }

    public function update(Request $request, $id) {
        $position = Position::findOrFail($id);
        $validated = $request->validate(['name' => 'required|string|max:255|unique:positions,name,'.$id]);
        $position->update($validated);
        return redirect()->route('admin.positions.index')->with('success', 'Posisi diperbarui.');
    }

    public function destroy($id) {
        Position::findOrFail($id)->delete();
        return redirect()->route('admin.positions.index')->with('success', 'Posisi dihapus.');
    }
}