<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name','description'];
    public function groups_views()
    {
        return $this->belongsToMany(Group::class, 'group_lecture_views');
    }
    public function student_views()
    {
        return $this->belongsToMany(Student::class, 'student_lecture_views');
    }
}
