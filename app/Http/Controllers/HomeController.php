<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Laravel\Pennant\Feature;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->data['title'] = 'Dashboard';
        $this->data['is_admin'] = false;
        $this->data['role'] = '';
        $user = auth()->user();
        $tickets = Ticket::latestUpdate();
        if(Feature::active('isAdmin')){
            $this->data['role'] = 'admin';
            $this->data['is_admin'] = true;
        }else{
            $this->data['role'] = 'user';
            $this->data['is_admin'] = false;
            $tickets = $tickets->where('created_by',$user->id);
        }
        $this->data['datas'] = $tickets->orderBy('latest_update','desc')->paginate(5);
        // dd($this->data);
        return view('home',$this->data);
    }
}
