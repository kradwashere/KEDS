<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Lists;
use Illuminate\Http\Request;
use App\Models\User;
use App\Libraries\OnewaySms;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index(){
        return inertia('Home/Index');
    }

    public function sms(){
        $mobile = '09171531652';
        $message = 'hahaha';
        $debug = false;
        $client = new Client();
        $result = $client->request('GET', 'http://gateway.onewaysms.ph:10001/api.aspx', [
            'query' => [
                'apiusername' => 'API7NK269OJTH',
                'apipassword' => 'API7NK269OJTHJCRHE',
                'senderid' => 'WEEFIX',
                'mobileno' => $mobile,
                'message' => $message,
                'languagetype' => 1
            ]
        ]);

        $response = json_decode($result->getBody()->getContents());

        // set return code
        $response_code = $response;
        if ($response_code > 0) {
            // Positive value – Success
            $response_status = true;
            $response_message = 'Success';
        } else {
            $response_status = false;
            switch ($response_code) {
                case -100:
                    $response_message = 'apipassname or apipassword is invalid';
                    break;
                case -200:
                    $response_message = 'senderid parameter is invalid';
                    break;
                case -300:
                    $response_message = 'mobileno parameter is invalid';
                    break;
                case -400:
                    $response_message = 'languagetype is invalid';
                    break;
                case -500:
                    $response_message = 'Invalid characters in message';
                    break;
                case -600:
                    $response_message = 'Insufficient credit balance';
                    break;

                default:
                    $response_message = 'Unknown reason';
                    break;
            }
        }

        if ($debug) {
            $result = [
                'status' => $response_status,
                'code' => $response_code,
                'message' => $response_message
            ];

            // result sample:
            // array:3 [▼
            //     "status" => false
            //     "code" => -100
            //     "message" => "apipassname or apipassword is invalid"
            // ]
        } else {
            $result = $response_status; // Boolean
        }

        return $result;
    }

    public function update(Request $request){

        $validatedData = $request->validate([
            'password' => 'required|string|min:9|confirmed',
            'password_confirmation' => 'same:password',
        ]);

        $id = ($request->id) ? $request->id : \Auth::user()->id;


        User::find($id)->update(['password'=> \Hash::make($request->input('password')),'is_active' => 1]);
        return back()->with([
            'message' => 'Password Changed',
            'data' => 'wew',
            'type' => 'bxs-check-circle'
        ]); 
        
    }

    public function about(){
        $data = Lists::where('type','Brand')->get();
        return inertia('About',['brands' => $data]);
    }

    public function service(){
        $data = Service::all();
        return inertia('Service',['services' => $data]);
    }
}
