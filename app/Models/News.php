<?php


namespace App\Models;


class News extends BaseModel
{
    protected $fillable = ['title', 'body', 'created_by'];

    protected $hidden = ['created_by'];

    protected $appends = ['creator_name'];

    protected function getCreatorNameAttribute()
    {
        return $this->user()->first()->name;
    }


    public function user()
    {
        return $this->belongsTo('App\Models\Users', 'created_by', 'id');
    }
}