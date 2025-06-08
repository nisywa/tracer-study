<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateEmail extends Model
{
    protected $table = 'template_email';
    protected $fillable = ['type', 'subject', 'body'];
    
}