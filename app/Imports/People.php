namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class People implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows[0] as $row) 
        {
          DB::table('people').insert($row);
        }
    }
}