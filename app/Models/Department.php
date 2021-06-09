<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;


    // public static function boot()
    // {
    //     parent::boot();
    //     static::created(function ($ac) {
    //         $name = $ac->name;
    //         $parent = $ac;
    //         redo:
    //         $parent = Department::find($parent->parent_id);
    //         if ($parent && $parent->root_id > 0) {
    //             $name = $parent->name . ' > ' . $name;
    //             goto redo;
    //         }
    //         $ac->update(['cascade_name' => $name]);
    //     });

    //     static::updated(function ($ac) {
    //         $name = $ac->name;
    //         $parent = $ac;
    //         redo:
    //         $parent = Department::find($parent->parent_id);
    //         if ($parent && $parent->root_id > 0) {
    //             $name = $parent->name . ' > ' . $name;
    //             goto redo;
    //         }
    //         $ac->cascade_name = $name;

    //         $ac->saveQuietly();
    //     });
    // }

    protected $guarded = []; //

    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('App\Models\DepartmentItem');
    }
}
