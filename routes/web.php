  
<?php

use App\Http\Controllers\ExportReportController;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Exports\Peoples;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('test', function () {


    $cs = DB::table('departments')->where('root_id', 3)->get();
    $ts = DB::table('departments')->where('root_id', 6)->get();
    $ps = DB::table('people')->get();

    foreach ($ps as $p) {
        $rc = $cs->random();
        $rt = $ts->random();
        DB::table('people')->where('id', $p->id)->update(['committee_id' => $rc->id, 'committee_details' => $rc->cascade_name, 'family_details' => $rt->cascade_name]);
        # code...
    }

    return 'ok';
    DB::table('people')->where('gender', 'male')->update(['gender' => 'ذكر']);
    DB::table('people')->where('gender', 'female')->update(['gender' => 'انثي']);
    return 'ok';
});




require __DIR__ . '/auth.php';

//redirect to vue routes
Route::get('/{any?}', function () {
    $settings =  DB::table('settings')->get();
    foreach ($settings as $s) {
        $s->value = json_decode($s->value);
    }
    return view('welcome', ['settings' => $settings]);
})->where('any', '^(?!api\/)[\/\w\.-]*');
