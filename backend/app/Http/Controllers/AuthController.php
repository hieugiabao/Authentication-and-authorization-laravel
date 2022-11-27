<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
			'password' => 'required|string|min:6',
		]);
		if ($validator->fails()) {
			return response()->json($validator->errors(), 422);
		}
		JWTAuth::factory()->setTTL(1);
		$refresh_token = JWTAuth::attempt($validator->validated());
		JWTAuth::factory()->setTTL(config('jwt.ttl'));
		$access_token = JWTAuth::attempt($validator->validated());
		if (!$access_token) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		return $this->createNewToken($access_token, $refresh_token);
	}
	/**
	 * Register a User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|between:2,100',
			'email' => 'required|string|email|max:100|unique:users',
			'password' => 'required|string|confirmed|min:6',
		]);
		if ($validator->fails()) {
			return response()->json([
				'message' => 'The given data was invalid.',
				'errors' => $validator->errors()
			], 400);
		}
		$user = User::create(array_merge(
			$validator->validated(),
			['password' => bcrypt($request->password)]
		));
		return response()->json([
			'message' => 'User successfully registered',
			'user' => $user
		], 201);
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout()
	{
		auth()->logout();
		return response()->json(['message' => 'User successfully signed out']);
	}
	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh()
	{
		try {
			$new_token = auth()->refresh();

			$payload_array = JWTAuth::manager()->getJWTProvider()->decode($new_token);

			JWTAuth::factory()->setTTL(1);
			$payload = JWTFactory::make($payload_array);
			$refresh_token = JWTAuth::encode($payload);
			return $this->createNewToken($new_token, $refresh_token);
		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json(['error' => 'Refresh token is expired'], 401);
		}
	}
	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function userProfile()
	{
		return response()->json(auth()->user());
	}
	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function createNewToken($access_token, $refresh_token)
	{
		return response()->json([
			'access_token' => [
				'token' => $access_token,
				'type' => 'bearer',
				'expires_in' => config('jwt.ttl') * 60,
			],
			'refresh_token' => [
				'token' => str($refresh_token),
				'type' => 'bearer',
				'expires_in' => config('jwt.refresh_ttl') * 60,
			],
			'user' => auth()->user()
		]);
	}
}
