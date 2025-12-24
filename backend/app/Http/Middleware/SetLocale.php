<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');
        
        if ($locale) {
            // Получаем первые два символа из Accept-Language
            $locale = substr($locale, 0, 2);
            
            // Проверяем, поддерживается ли этот язык
            if (in_array($locale, config('app.available_locales'))) {
                App::setLocale($locale);
            }
        }
        
        return $next($request);
    }
} 