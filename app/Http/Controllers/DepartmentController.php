<?php

namespace App\Http\Controllers;

use App\Exports\Committee;
use App\Models\Department;
use App\Models\DepartmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::orderBy('order')->get();
    }

    public function show(Department $department)
    {
        //todo: add pagination to department items

        return $department->load('items');
    }

    public function store()
    {
        \request()->validate([
            'name' => ['required', Rule::unique('departments')]
        ], ['name.unique' => 'هذ الاسم موجود قبل ذلك']);
        return Department::create(\request()->only('name', 'order', 'state', 'color', 'file_type'));
    }

    public function update(Department $department)
    {
        request()->validate([
            'name' => ['required', Rule::unique('departments')->ignore($department->id)]
        ], ['name.unique' => 'هذ الاسم موجود قبل ذلك']);
        $department->update(\request()->only('name', 'order', 'state', 'color', 'file_type'));
        return $department;
    }

    public function destroy(Department $department)
    {
        $department->delete();
    }
}
