<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/home/posts/all';
    // protected $redirectTo = '/businessuserprofile';

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function createTourist(){
        $data = request()->all(); // ← ここで取得
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 1,
        ]);
    }

    public function storeTourist(Request $request){
        $data = $request->all();
        $user = $this->createTourist($data);

        Auth::login($user);
        return redirect()->route('posts.all');
    }

    public function registerBusiness()
    {
        return view('auth.register_business');
    }

    public function registerTourist()
    {
        return view('auth.register');
    }

    protected function createBusiness(array $data)
{
    return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'phonenumber' => $data['phonenumber'],
        'role_id' => 2, // Business user 固定
    ]);
}

public function register(Request $request)
{
    $data = $request->all();

    $user = $this->storeTourist($data);

    // ログインさせる場合
    Auth::login($user);

    // マイページへリダイレクト
    return redirect()->route('posts.all');
}

    public function storeBusiness(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phonenumber' => 'required|string|max:20',
    ]);

    $user = $this->createBusiness($request->all());

    // 自動ログインさせたい場合
    auth()->login($user);

    return redirect()->route('posts.all');
}

    }

