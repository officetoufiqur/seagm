<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CKEditorController extends Controller
{
    public function index()
    {
        return Inertia::render('CKEditor');
    }
}
