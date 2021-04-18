<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    use HasFactory;
    protected $table = "_z_country";    // country table name
    public $timestamps = false;         // to avoid time
    protected $guarded = [];            // use $fillable or $guarded your choice
}
