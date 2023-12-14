<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

     //membuat data secara global agar bisa digunakan berulang kali
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            //session (untuk menampilkan alert di hlm frontend)
            'session' => [
                'status' => fn () => $request->session()->get('status'),
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            //user authenticated 
            'auth' => [
                'user' => $request->user() ? $request->user() : null, //(yg berisi data user yang sedang login)
                'permissions' => $request->user() ? $request->user()->getPermissionArray() : [] // (berisi list permission dari user yang sedang login)
            ],
            //route
            'route' => function () use ($request){
                return [
                    'params' => $request->route()->parameters(), // (digunakan untuk mendapatkan nilai dari sebuah parameter)
                    'query' => $request->all(), // (digunakan untuk mendapatkan semua request)
                ];
            },
        ]);
    }
}
