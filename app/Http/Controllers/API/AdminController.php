<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function create(Request $request)
    {
        $data_validator = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
            $user = User::create($request->all());
            $response = [
                'code' => 201,
                'message' => 'User created',
                'data' => $user,
            ];
            return response()->json($response, 200);
        }
    }

    public function read(Request $request, $id = '')
    {
        if (empty($id)) {
            $users = User::all();
            $response = [
                'code' => 200,
                'message' => 'Users read',
                'data' => $users,
            ];
            return response()->json($response, 200);
        } else {
            $user = User::find($id);
            if ($user) {
                $response = [
                    'code' => 200,
                    'message' => 'User read',
                    'data' => $user,
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'User not found',
                ];
                return response()->json($response, 200);
            }
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $data_validator = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ];

            $validator = Validator::make($request->all(), $data_validator);

            if ($validator->fails()) {
                $response = [
                    'code' => 400,
                    'message' => $validator->errors()->all(),
                    'data' => [],
                ];
                return response()->json($response, 200);
            } else {
                $user->update($request->all());
                $response = [
                    'code' => 200,
                    'message' => 'User updated',
                    'data' => $user,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function update_password(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $data_validator = [
                'password' => 'required|string|min:6',
            ];

            $validator = Validator::make($request->all(), $data_validator);

            if ($validator->fails()) {
                $response = [
                    'code' => 400,
                    'message' => $validator->errors()->all(),
                    'data' => [],
                ];
                return response()->json($response, 200);
            } else {
                $user->update(['password' => password_hash($request['password'], PASSWORD_DEFAULT)]);
                $response = [
                    'code' => 200,
                    'message' => 'User password updated',
                    'data' => $user,
                ];
                return response()->json($response, 200);
            }
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            $response = [
                'code' => 200,
                'message' => 'User deleted',
                'data' => [],
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'code' => 404,
                'message' => 'User not found',
            ];
            return response()->json($response, 200);
        }
    }

    public function login(Request $request)
    {
        $data_validator = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $data_validator);

        if ($validator->fails()) {
            $response = [
                'code' => 400,
                'message' => $validator->errors()->all(),
                'data' => [],
            ];
        } else {
            $user = User::where('email', $request['email'])->first();
            if ($user) {
                if (password_verify($request['password'], $user->password)) {
                    $response = [
                        'code' => 200,
                        'message' => 'User logged in',
                        'data' => $user,
                    ];
                } else {
                    $response = [
                        'code' => 401,
                        'message' => 'Wrong password',
                        'data' => [],
                    ];
                }
            } else {
                $response = [
                    'code' => 404,
                    'message' => 'User not found',
                    'data' => [],
                ];
            }
        }
        return response()->json($response, 200);
    }
}
