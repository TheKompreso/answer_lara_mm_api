<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function lectures()
    {
        return $this->belongsToMany(Lecture::class)->orderBy('group_lecture.id');
    }
    public function lecture_views()
    {
        return $this->belongsToMany(Lecture::class,'group_lecture_views');
    }
}
