<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
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
        $string = $request->string;

		if(preg_match("/^[A-z\s]+$/", $string) === 1) {
			if(preg_match("/-/", $string) === 1) {
				$string = $this->treatWords($string, '-');
			} elseif (preg_match("/\s/", $string) === 1) {
				$string = $this->treatWords($string, ' ');
			} else {
				$string = $this->translateWord($string);
			}
		}

        if (!Post::where('original', '=', $request->string)->exists()) {
            $post = new Post();

            $post->user_id = $request->user()->id;
            $post->original = $request->string;
            $post->translation = $string;

            $post->save();
        }
		
		return $this->encodeMessage(0, array("translation" => $string));
    }

    public function getPost(Request $request, $id) {
        $post = Post::where('id', '=', $id);

        if($post->exists()) {
            return $this->encodeMessage(0, $post->first()->translation);
        }
        
        return $this->encodeMessage(1, "Couldn't find the requested translation");
    }

    private function treatWords($string, $delimiter) {
        $words = explode($delimiter, $string);
        $stringAux = "";
        
        foreach ($words as $word) {
            $stringAux .= $this->translateWord($word).$delimiter;
        }

        return substr($stringAux, 0, -1);
    }
	
	private function translateWord($word) {
		if(preg_match("/^[AaEeIiOoUu]/", $word) === 1) {
			return $word .= "way";
		} else {
			$arrAux = str_split($word);
			$i = 0;
			$up = false;
			
			if(ctype_upper($arrAux[0])) {
				$up = true;
				$arrAux[0] = strtolower($arrAux[0]);
			}
			
			$regexp = (strtolower($arrAux[$i]) === 'y') ? "/^[AaEeIiOoUu]/" : "/^[AaEeIiOoUuYy]/";
			
			while(preg_match($regexp, $arrAux[$i]) === 0) {
				$arrAux = $this->swapLetter($arrAux, $i);
				$i++;
				if(strtolower($arrAux[$i]) === 'u' && strtolower(end($arrAux)) === 'q') {
					$arrAux = $this->swapLetter($arrAux, $i);
					$i++;
				}
			}
			
			if($up)
				$arrAux[array_keys($arrAux)[0]] = strtoupper($arrAux[array_keys($arrAux)[0]]);
			
			return implode("",$arrAux)."ay";
		}
	}
	
	private function swapLetter($arrAux, $i) {
		$let = $arrAux[$i];
		unset($arrAux[$i]);
		array_push($arrAux, $let);
		
		return $arrAux;
	}

    private function encodeMessage($status, $message) {
        return json_encode(["status" => ($status == 0) ? "Ok" : "Error", "message" => $message]);
    }
}
