<?php

namespace App\Http\Controllers\Auth;

use App\Services\CurlRequestService;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['agreement'] = (boolean) $data['agreement'];
        return Validator::make($data, [
            'code' => ['required', 'string', 'alpha_num', 'min:15', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'agreement' => ['required', 'boolean'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function create(array $data)
    {
        $curl = new CurlRequestService();
        $created = $curl->create($data);
        if($created) {
            return new User((array) $created);
        } return null;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        if(!$user) {
            $errors = json_decode(Session::get('curl_error'));
            $errors = isset($errors->errors) ? $errors->errors : null;
            return redirect()->back()->withErrors($errors);
        }
        $curl = new CurlRequestService();
        $auth = $curl->login([
            "email" => $request->post("email"),
            "password" => $request->post("password")
        ]);
        $user = new User((array) $auth);
        $this->guard()->login($user);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
