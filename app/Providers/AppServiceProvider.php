<?php

namespace App\Providers;

use App\Services\CurlRequestService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::setLocale($this->getLanguage());
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
