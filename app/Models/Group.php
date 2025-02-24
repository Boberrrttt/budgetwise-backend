<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'groups'; // Ensure this matches the database table name

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}