<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
  use HasFactory;
  //  public $table = "job_applications";


  protected $fillable = [
    'job_id',
    'user_id',
    'employer_id',
    'applied_date',
  ];
}
