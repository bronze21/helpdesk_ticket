<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $this->data['title'] = "Profile";
        $this->data['crumbs'][] = [
            'title' => 'Profile',
            'url' => route('profile'),
        ];
        $this->data['data'] = auth()->user();
        return view('pages.profile.index', $this->data);
    }
}
