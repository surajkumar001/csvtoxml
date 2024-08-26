<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'file_name', 'file_path','json_data','xml_url','url','header','footer','body','body_2'];

}
