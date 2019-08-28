<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AuthenticateMiddleware
{
    /**
     * Store the user data
     * @var
     */
    protected $user_data;

    /**
     * Validate request
     * @param Request $request
     */
    protected function validate(Request $request)
    {
        $token = $request->bearerToken();

        // If no token was specified
        if (!$token) {
            response()->json(['status_code' => 401, 'message' => 'Authorization failed, token not sent.'], 401)->send();
            exit;
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            response()->json(['status_code' => 403, 'message' => 'Provided token has expired.'], 403)->send();
            exit;
        } catch (\Exception $e) {
            response()->json(['status_code' => 403, 'message' => 'Invalid token sent.'], 403)->send();
            exit;
        }

        // Fetch the User data
        $this->user_data = $this->getUserData($credentials->sub);

        // If the user data is invalid
        if (!$this->user_data) {
            response()->json(['status_code' => 403, 'message' => 'Provided token is invalid.'], 403)->send();
            exit;
        }


    }

    /**
     * Fetch the User data
     * @param $id
     * @return mixed
     */
    protected function getUserData($id)
    {
        $user = Users::find($id);
        return $user;
    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check Permissions
        $this->validate($request);

        $request->auth = $this->user_data;
        return $next($request);
    }
}
