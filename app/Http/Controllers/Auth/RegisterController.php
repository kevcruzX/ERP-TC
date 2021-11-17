<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Utility;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
                     'name' => [
                         'required',
                         'string',
                         'max:255',
                     ],
                     'email' => [
                         'required',
                         'string',
                         'email',
                         'max:255',
                         'unique:users',
                     ],
                     'password' => [
                         'required',
                         'string',
                         'min:8',
                         'confirmed',
                     ],
                 ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    protected function create(array $request)
    {
        $user   = User::create(
            [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'type' => 'company',
                'default_pipeline' => 1,
                'plan' => 1,
                'lang' => \App\Utility::getValByName('default_language'),
                'avatar' => '',
                'created_by' => 1,

            ]
        );
        $role_r = Role::findByName('company');
        $user->assignRole($role_r);
        Utility::chartOfAccountTypeData($user->id);
        Utility::chartOfAccountData($user);
        Utility::pipeline_lead_deal_Stage($user->id);
        Utility::project_task_stages($user->id);
        Utility::labels($user->id);
        Utility::sources($user->id);
        Utility::jobStage($user->id);
        return $user;
    }

    public function showRegistrationForm($lang = '')
    {
        if($lang == '')
        {
            $lang = \App\Utility::getValByName('default_language');
        }

        \App::setLocale($lang);

        return view('auth.register', compact('lang'));
    }
}
