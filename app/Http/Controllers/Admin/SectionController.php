<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Department;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $sections = Section::with('department')->when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                      ->orWhereHas('department', fn($q) => $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]));
            }
        })->latest()->paginate($perPage)->appends($request->query());

        $departments = Department::all();

        return view('admin.sections.index', compact('sections', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255'
        ]);
        
        Section::create($validated);
        return redirect()->route('admin.sections.index')->with('success', 'Section ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255'
        ]);
        
        $section->update($validated);
        return redirect()->route('admin.sections.index')->with('success', 'Section diperbarui.');
    }

    public function destroy($id)
    {
        Section::findOrFail($id)->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section dihapus.');
    }
}