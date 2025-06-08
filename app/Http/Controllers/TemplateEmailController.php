<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateEmailController extends Controller
{
    public function template_email()
    {
        return view('admin.views.survey.template_email');
    }
}
