<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminChatController extends Controller
{
    public function index()
    {
        return view('chat_page');
    }

}
