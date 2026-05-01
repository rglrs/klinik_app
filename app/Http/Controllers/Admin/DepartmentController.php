<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $departments = Department::when($search, function($query) use ($search) {
            $terms = array_filter(explode(' ', strtolower(trim($search))));
            foreach ($terms as $term) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
            }
        })->latest()->paginate($perPage)->appends($request->query());

        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required|string|max:255|unique:departments,name']);
        Department::create($validated);
        return redirect()->route('admin.departments.index')->with('success', 'Departemen ditambahkan.');
    }

    public function update(Request $request, $id) {
        $department = Department::findOrFail($id);
        $validated = $request->validate(['name' => 'required|string|max:255|unique:departments,name,'.$id]);
        $department->update($validated);
        return redirect()->route('admin.departments.index')->with('success', 'Departemen diperbarui.');
    }

    public function destroy($id) {
        Department::findOrFail($id)->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Departemen dihapus.');
    }
}