<?php

namespace App\Http\Controllers;

use App\Events\AdminNotificationEvent;
use App\Events\TestEvent;
use App\Events\UserNotificationEvent;
use App\Models\Cart;
use App\Models\User;
use App\Helpers\Cryptor;
use App\Helpers\RoleHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'status' => 1,
                'role' => RoleHelper::getKey('customer')
            ]);

            Cart::firstOrCreate([
                'user_id' => $user->id
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Account registration successful!'
            ]);
        } catch (\Exception $e) {
            \Log::info($e);

            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('username', $request->credentials)
                ->orWhere('email', $request->credentials)
                ->first();

            if (!isset($user)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Credentials'
                ], 401);
            }

            if (Hash::check($request->password, $user->password)) {
                $user->tokens()->delete();
                $user->save();

                // Role helper to get string value of roles
                $role = RoleHelper::getName($user->role);

                $token = $user->createToken('auth_token', [$role])->plainTextToken;

                // Insert activity logs

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Successful',
                    'data' => [
                        'username' => $user->username,
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'role' => $role
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials.'
                ], 401);
            }
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Account logged out successfully.'
            ], 200);
        } catch (\Exception $e) {
            \Log::info($e);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function profile()
    {
        try {
            $user = User::find(auth()->user()->id);

            if (!isset($user)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No user found.',
                ], 404);
            }

            $user->_id = Cryptor::encrypt($user->id);
            $user['role'] = RoleHelper::getName($user->role);

            unset($user->id, $user->created_by, $user->updated_by, $user->created_at, $user->updated_at);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile retrieved.',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            \Log::info($e);
        }
    }

    public function broadcast(Request $request)
    {
        TestEvent::dispatch(auth()->user(), $request->message);
    }

    public function broadcastToPlayer()
    {
        $message = 'Successful Deposit';

        $user = User::where('id', 2)->first();

        UserNotificationEvent::dispatch($user, $message);
    }

    public function broadcastToAdmins()
    {
        $user = User::where('id', 2)->first();

        $message = 'Player ' . $user->username . ' sumbitted a deposit';

        AdminNotificationEvent::dispatch($user, $message);
    }
}
