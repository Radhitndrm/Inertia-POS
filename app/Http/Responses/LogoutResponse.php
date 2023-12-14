<?php 


//untuk membuat fitur logout laravel fortify di inertia.js membutuhkan beberapa customisasi atau ovveride dari kode bawaannya
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
    return redirect('/login');
    }
}