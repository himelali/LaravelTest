<?php

namespace App\Http\Middleware;

use App\Services\CurlRequestService;
use Closure;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = session(getLanguageKey()) ?? $this->getLanguage();
        App::setLocale($language);
        return $next($request);
    }

    private function getLanguage() {
        try {
            $data = (new CurlRequestService())->language();
            return isset($data->language) ? $data->language : "en";
        } catch (GuzzleException $e) {
            return "en";
        }
    }
}
