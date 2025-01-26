<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    protected $database;
    public function __construct()
    {
        $this->database = app('firebase.database');
        $database->getReference('path/to/child/location');
    }
}
