<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $guarded = [];
    //protected $dateFormat = 'Y-m-d H:i:s';

    public function documentable()
    {
        return $this->morphTo();
    }
}
