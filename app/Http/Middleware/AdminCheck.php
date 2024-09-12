<?php

namespace App\Http\Middleware;

use App\Http\Controllers\GestoresActas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        // Validar si el usuario es administrador
        $user = GestoresActas::getSessionGestor($request);
        if($user->original->rol == 'administrador'){
            return $next($request);
        } 
        return response()->json(false);
    }
}
