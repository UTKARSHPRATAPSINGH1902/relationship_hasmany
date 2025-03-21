<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\ConnectionRequest;
use App\Models\Otp;

class AuthController extends Controller
{
    // Generate connection ID
    public function get_connection_id(Request $request)
    {
        $api_key = $request->api_key;
        if ($api_key === 'DEMOproject2O2S') {
            $connection_id = rand(100000000, 999999999);
            ConnectionRequest::create(['connection_id' => $connection_id]);
            return response()->json(['status' => 'success', 'message' => 'Connection ID generated successfully', 'data' => ['connection_id' => $connection_id]]);
        }
        return response()->json(['status' => 'error', 'message' => 'Failed to generate connection ID']);
    }

    // Request OTP
    public function request_otp(Request $request)
    {
        $valid = $this->validateConnection($request);
        if (!$valid) return response()->json(['status' => 'error', 'message' => 'Invalid Connection']);

        $phone = $request->phone;
        $otp = rand(1000, 9999);

        Otp::updateOrCreate(['phone' => $phone], ['otp' => $otp, 'expired_on' => now()->addMinutes(5)]);

        return response()->json(['status' => 'success', 'message' => 'OTP Sent Successfully']);
    }

    // Login with OTP
    public function login(Request $request)
    {
        $valid = $this->validateConnection($request);
        if (!$valid) return response()->json(['status' => 'error', 'message' => 'Invalid Connection']);

        $phone = $request->phone;
        $otp = $request->otp;
        $customer = Customer::where('phone_number', $phone)->first();

        if (!$customer) return response()->json(['status' => 'error', 'message' => 'Customer does not exist']);

        if (!$this->verify_otp($phone, $otp)) return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);

        $auth_code = Str::random(15);
        $valid->update(['auth_code' => $auth_code, 'user_id' => $customer->id]);

        return response()->json(['status' => 'success', 'message' => 'Login Successful', 'data' => ['auth_code' => $auth_code]]);
    }

    // Register Customer
    public function register_customer(Request $request)
    {
        $valid = $this->validateConnection($request);
        if (!$valid) return response()->json(['status' => 'error', 'message' => 'Invalid Connection']);

        $phone = $request->phone;
        $otp = $request->otp;

        if (!$this->verify_otp($phone, $otp)) return response()->json(['status' => 'error', 'message' => 'Invalid OTP']);

        if (Customer::where('phone_number', $phone)->exists()) return response()->json(['status' => 'error', 'message' => 'Customer already exists']);

        $customer = Customer::create([
            'name' => $request->name,
            'email_id' => $request->email,
            'phone_number' => $phone
        ]);

        $auth_code = Str::random(15);
        $valid->update(['auth_code' => $auth_code, 'user_id' => $customer->id]);

        return response()->json(['status' => 'success', 'message' => 'Registration Successful', 'data' => ['auth_code' => $auth_code]]);
    }

    private function validateConnection($request)
    {
        return ConnectionRequest::where('connection_id', $request->connection_id)->first();
    }

    private function verify_otp($phone, $otp)
    {
        return Otp::where(['phone' => $phone, 'otp' => $otp])->where('expired_on', '>', now())->exists();
    }
}
