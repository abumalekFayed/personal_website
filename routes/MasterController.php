<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Notifications\Note;
use App\Notifications\ProtocolNotification;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class MasterController extends Controller
{


    public function index()
    {

        if (\request()->user_masters) return Master::where('user_id', auth()->id())->with('department:id,name')->get();

        if (Auth::user()->check(['admin', '/postgraduate/masters.show-all'])) {
            return Master::latest()->whereYear('created_at', request()->year)
                ->with(['user:id,name,email,telephone,img',  'department:id,name'])
                ->get()
                ->append('department_name');
        } else if (Auth::user()->check(['admin', '/postgraduate/masters.show-department'])) {
            return Master::latest()->whereYear('created_at', request()->year)
                ->where('department_id', Auth::user()->department_id)
                ->with(['user:id,name,email,telephone,img',  'department:id,name'])
                ->get()
                ->append('department_name');
        } else   return [];
    }


    public function show($id = 0)
    {
        //delete all watches rlated to this record id
        //  \App\Change::where('user_id', Auth::user()->id)->where('changable_type', 'App\Master')->where('changable_id', $id)->delete();
        if (request()->print_submission) {

            $master = Master::findOrFail($id);
            $types = ['ازهري', 'ترشيح وزارة الصحة', 'ترشيح جيش', 'ترشيح شرطه', 'خارجي'];
            $data = ['master' => $master, 'types' => $types];
            $config = ['instanceConfigurator' => function ($mpdf) {
                $mpdf->SetWatermarkImage('storage/imgs/logo.jpg', 0.10, [100, 100]);
                $mpdf->showWatermarkImage = true;
                $mpdf->autoLangToFont = true;
            }];
            $pdf = PDF::loadView('reports.masterUploadSuccess', $data, [], $config);
            $pdf->stream('document.pdf');
        }


        return Master::where('id', $id)->with(['activities', 'user:id,name,email,telephone,img', 'department:id,name', 'scores', 'scores.score'])->get()->first();
    }


    public function addfile($master, $request, $file)
    {
        $path = public_path('files/' . Auth::user()->id);
        File::makeDirectory($path, $mode = 0777, true, true);

        if ($request->has($file)) {
            @unlink(public_path('files/' . $master->user_id . '/' . $master[$file]));
            $file_name = time() . $file . '.' . $request[$file]->getClientOriginalExtension();
            $request[$file]->move($path, $file);
            $master[$file] = $file;
        }
    }

    // public function addChange($user_id, $changable_type, $changable_id, $state)
    // {
    //     $change = new \App\Change;
    //     $change->user_id = $user_id;
    //     $change->changable_type = $changable_type;
    //     $change->type_id = $changable_id;
    //     $change->state = $state;
    //     $change->save();
    // }

    public function store(Request $request)
    {
        // return response()->json(['error' => 'لم يتم فتح التسجيل بعد'], 404);

        $find = Master::where('user_id', auth()->id())->first();
        if ($find)        return response()->json(['error' => 'الحساب مسجل من قبل'], 404);


        // return request();

        //  return \request();
        DB::beginTransaction();
        try {
            $master = Master::create(\request()->only('department_id', 'user_grade_id', 'biography', 'type', 'signature') + ['user_id' => auth()->id()]);
            if (\request()->hasFile('cv_file')) $master->cvFile = \request()->cv_file->store('master_files', ['disk' => 'public']);
            if (\request()->hasFile('sc_file')) $master->scFile = \request()->sc_file->store('master_files', ['disk' => 'public']);
            if (\request()->hasFile('ex_file')) $master->exFile = \request()->ex_file->store('master_files', ['disk' => 'public']);
            if (\request()->hasFile('bc_file')) $master->bcFile = \request()->bc_file->store('master_files', ['disk' => 'public']);
            $master->save();
            //add scores
            $master->scores()->sync(request()->scores);
            DB::commit();

            return $master->load('department:id,name');
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 404);
            //throw $th;
        }



        //add watch to admins
        //add watch to admins

        // $users = User::role('department head')->get();
        // foreach ($users as $user) {
        //     \App\Change::Create(
        //         ['user_id' => $user->id, 'changable_type' => 'App\Master', 'changable_id' => $master->id, 'state' => 'new'],
        //     );
        // }

        // return ['success' => 'success'];
    }


    public function update(Master $master, $id = 0, Request $request)
    {

        if (request()->update_mark) {
            $master->total_mark = $request->total_mark;
            $master->save();
            $master->activities()->create(['user_id' => auth()->id(), 'name' => ' تحديث الدرجة الي  ' . $request->total_mark]);
            return response()->json(['success' => 'Master mark was updated'], 200);
        }
        $master->state = $request->state;
        // $master->accepted_at = new Carbon();

        $master->save();
        //adding acceptance reply


        //add notification to user
        $data = (object) [
            'link' => '/masters/' . $master->id,
            'title' => 'check your master registration'
        ];

        try {
            $user->notify(new Note($data));
        } catch (\Throwable $th) {
            //throw $th;
        }



        return ['success' => 'success'];

        return 'success';
    }




    public function reset()
    {
        $masters = Master::all();
        foreach ($masters as $master) {
            $master->state = 1;
            $master->save();

            echo ('done');
        }
    }

    public function destroy(Master $master)
    {




        //delete file if found
        @unlink(public_path() . '/files/' . $master->user->id . '/' . $master->cvfile);
        @unlink(public_path() . '/files/' . $master->user->id . '/' . $master->bcfile);
        @unlink(public_path() . '/files/' . $master->user->id . '/' . $master->exfile);
        @unlink(public_path() . '/files/' . $master->user->id . '/' . $master->scfile);
        //delete all watches rlated to this record id
        \App\Change::where('changable_type', 'App\Master')->where('changable_id', $master->id)->delete();

        $master->delete();
        return ['message' => 'master Deleted'];
    }
}
