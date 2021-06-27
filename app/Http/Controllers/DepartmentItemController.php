<?php

namespace App\Http\Controllers;

use App\Models\DepartmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentItemController extends Controller
{

    public function index()
    {



        return DepartmentItem::latest()->when(\request()->search, function ($q) {
            return  $q->searchIn(['name', 'description'], \request()->search);
        })->where('department_id', request()->department_id)->paginate(request()->items_per_page);
    }

    public function show(DepartmentItem $department_item)
    {
        return $department_item->load('department', 'documents');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $file_path = '';
            $item = DepartmentItem::create(request()->only('department_id', 'name', 'description', 'description_summary', 'youtube_link'));
            if (request()->documents && is_array(request()->documents)) {
                foreach (request()->documents as $file) {

                    $image = getimagesize($file);

                    $file_path = $file->store('department_files', ['disk' => 'public']);
                    $item->documents()->create(['path' => $file_path, 'w' => $image[0], 'h' => $image[1]]);
                }
            }
            DB::commit();
            return $item->load('documents');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 404);
        }
    }

    public function update(DepartmentItem $department_item)
    {
        DB::beginTransaction();
        try {
            $file_path = '';
            $department_item->update(request()->only('department_id', 'name', 'description', 'description_summary', 'youtube_link'));
            if (request()->documents && is_array(request()->documents)) {
                foreach (request()->documents as $file) {
                    $file_path = $file->store('department_files', ['disk' => 'public']);
                    $department_item->documents()->create(['path' => $file_path]);
                }
            }
            DB::commit();
            return $department_item->load('documents');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 404);
        }
    }

    public function destroy(DepartmentItem $department_item)
    {
        return $department_item->delete();
    }
}
