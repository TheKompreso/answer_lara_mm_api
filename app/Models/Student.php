<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name','group_id'];
    public function lecture_views()
    {
        return $this->belongsToMany(Lecture::class,'student_lecture_views');
    }
}
