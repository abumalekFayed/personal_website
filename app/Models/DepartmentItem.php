<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class DepartmentItem extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['main_image'];
    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function documents()
    {
        return $this->morphMany('App\Models\Document', 'documentable');
    }

    public function getMainImageAttribute()
    {
        if ($this->documents->first()) return $this->documents->first()->path;
        return 'test';
    }
}
