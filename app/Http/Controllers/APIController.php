<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return json_encode(["Version" => "1.0"]);
    }

    public function register(Request $request) {
        if (User::where('email', '=', $request->email)->exists()) {
            return $this->encodeMessage(1, "User already registered.");
        } else {
            $user = new User;

            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->confirmation_token = str_random(32);;
            $user->confirmed = false;

            $user->save();
        
            return $this->encodeMessage(0, "User registered. Please confirm registration with URL: ".url()."/confirm/".$user->confirmation_token);
        }
    }

    public function confirm(Request $request, $token) {
        $user = User::where('confirmation_token', '=', $token);
        
        if ($user->exists()) {
            $user = $user->first();

            $user->confirmed = true;
            $user->api_token = str_random(50);

            $user->save();

            return $this->encodeMessage(0, "User activated. Please use the following token to access methods: $user->api_token");
        } else {
            return $this->encodeMessage(1, "Couldn't find user.");
        }
    }

    public function storePost(Request $request) {

    }

    private function encodeMessage($status, $message) {
        return json_encode(["status" => ($status == 0) ? "Ok" : "Error", "message" => $message]);
    }
}