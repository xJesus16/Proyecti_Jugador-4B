<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use App\Models\Usuarios;

class LoginController extends Controller
{
    //Se crea la funcion por la version de laravel 12 
     //use AuthenticatesUsers;
     function login()
    {
        return view('auth.login', ['usuario' => (object)['id' => '']]);
    }

     function redirectPath()
    {
        switch (Auth::user()->idrol) {
            case 1:
                // Home del jugador
            // return 'home/jugador';
            return '/jugador/profile';
            break;

            default:
                return '/jugador';
                break;
        }
    }

     function iniciar_sesion(Request $context)
    {
        $request = $context->all();
        

         if (Auth::attempt(['email' => $context['email'], 'password' => $context['password']])) {
        //  dd('Todo bien'); 
         return redirect()->intended($this->redirectPath());
        // return redirect()->route('jugador');
         
        } 
        else {
        // dd('Todo mal'); 
        return redirect()->route('login');
        }

    }

     function logout()
    {
        Auth::logout();       // Cierra la sesión
        Session::flush();     // Limpia la sesión
        return redirect()->route('login'); // Redirige al login
    }
}
