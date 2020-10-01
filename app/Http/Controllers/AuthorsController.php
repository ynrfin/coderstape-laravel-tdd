<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorsController extends Controller
{
    function store()
    {
        Author::create(request()->only([
            'name', 'dob',
        ]));
    }

}
