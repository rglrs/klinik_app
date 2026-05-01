<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $pegawai = Pegawai::with(['department', 'section', 'position'])
            ->when($search, function($query) use ($search) {
                $terms = array_filter(explode(' ', strtolower(trim($search))));
                foreach ($terms as $term) {
                    $query->where(function($q) use ($term) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(nik) LIKE ?', ["%{$term}%"])
                          ->orWhereRaw('LOWER(gender) LIKE ?', ["%{$term}%"])
                          ->orWhereHas('department', fn($q2) => $q2->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]))
                          ->orWhereHas('section', fn($q2) => $q2->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]))
                          ->orWhereHas('position', fn($q2) => $q2->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]));
                    });
                }
            })->latest()->paginate($perPage)->appends($request->query());

        $departments = Department::all();
        $sections = Section::all();
        $positions = Position::all();

        return view('admin.pegawai.index', compact('pegawai', 'departments', 'sections', 'positions'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nik' => 'required|string|unique:pegawai,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'position_id' => 'nullable|exists:positions,id',
        ]);
        Pegawai::create($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $pegawai = Pegawai::findOrFail($id);
        $validated = $request->validate([
            'nik' => 'required|string|unique:pegawai,nik,'.$id,
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'phone' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'position_id' => 'nullable|exists:positions,id',
        ]);
        $pegawai->update($validated);
        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai diperbarui.');
    }

    public function destroy($id) {
        Pegawai::findOrFail($id)->delete();
        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai dihapus.');
    }
}