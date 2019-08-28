<?php


namespace App\Http\Controllers\Oauth;


use App\Http\Controllers\Controller;
use App\Models\Users;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OauthController extends Controller
{

    /**
     * Generate the Token
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function generateToken(Request $request): JsonResponse
    {
        // Validate the Users Request
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');


        $user = Users::where('username', $username)->first();


        // If the user is not found
        if (!$user)
            return $this->respondWithError('Account does not exist', 401);


        if (!password_verify($password, $user->password))
            return $this->respondWithError('Invalid password, please try again!', 401);

        return $this->respondWithSuccess([
            'message' => 'Authentication Successful',
            'token' => $this->createJWT($user->id)
        ]);
    }

    /**
     * Check if a token is valid
     * @param Request $request
     * @return JsonResponse
     */
    public function checkToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token)
            return $this->respondWithError('Token was not sent', 403);

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return $this->respondWithError('Token has expires, login again!', 401);
        } catch (\Exception $e) {
            return $this->respondWithError('Token is invalid, login again!', 401);
        }


        $account = Users::find($credentials->sub);

        if (!$account)
            return $this->respondWithError('Account details does not exist', 401);


        return $this->jsonResponse(['data' => $account], 200);
    }

    /**
     * Create the JWT
     * @param string $id
     * @return string
     */
    protected function createJWT(string $id): string
    {
        $payload = [
            'iss' => env('APP_URL'),
            'sub' => $id,
            'iat' => time(),
            'exp' => time() + 950400, // 11 Days
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }
}