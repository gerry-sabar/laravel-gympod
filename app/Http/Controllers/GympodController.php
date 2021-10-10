<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use DB;

class GympodController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="testing@testing.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Invalid username/password")
     *        )
     *     )
     * )
    */
    public function login(Request $request){
        $credentials = request(['email', 'password']);
        if (!Auth::guard('web')->attempt($credentials, false, false)) {
            return response()->json([
                'error' => 'Invalid username/password'
            ], 401);
        }

        $user = Auth::guard('web')->user();
        $client = DB::table('oauth_clients')->find(2);
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $credentials['email'],
            'password' => $credentials['password'],
            'scope' => '',
        ]);

        $data = [];
        $proxy = Request::create('oauth/token', 'POST');
        $dispatch = \Route::dispatch($proxy);
        $response = json_decode($dispatch->getContent());
        $data['email'] = $user->email;
        $data['access_token'] = $response->access_token;
        $data['refresh_token'] = $response->refresh_token;
        return response()->json($data, 200);        
    }

    /**
     * @OA\Get(
     * path="/api/refresh",
     * summary="Refresh token",
     * description="Refresh token",
     * operationId="refreshToken",
     * tags={"auth"},
     *   @OA\Parameter(
     *       name="RefreshToken",
     *       in="header",
     *       required=true,
     *       description="refresh token",
     *       @OA\Schema(
     *           type="string"
     *        ) 
     *      ),      
     *    @OA\Response(
     *      response=422,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *          @OA\Property(property="error", type="string", example="invalid_request"),
     *          @OA\Property(property="message", type="string", example="The refresh token is invalid."),
     *        )
     *     )
     *    )
    */
    public function refreshToken(Request $request){
        $input = $request->all();
        $client = DB::table('oauth_clients')->find(2);
        $refreshToken = $request->header('RefreshToken');

        $request->request->add([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => ''
        ]);

        $proxy = Request::create('oauth/token', 'POST');
        $data = \Route::dispatch($proxy);
        $response = json_decode($data->getContent());
        if (!isset($response->token_type)) {
            $error = [];
            $error['error'] = $response->error;
            $error['message'] = $response->message;
            return response()->json($error, 403);
        }        

        $result = [];
        $result['access_token'] = $response->access_token;
        $result['refresh_token'] = $response->refresh_token;
        return response()->json($result, 200);

    }

    /**
     * @OA\Get(
     * path="/api/pods",
     * summary="List user pods",
     * description="List user pods",
     * operationId="Pod",
     * security={
     *  {"bearerAuth": {}}
     * },
     * tags={"auth"},
     *    @OA\Response(
     *      response=422,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *          @OA\Property(property="error", type="string", example="Invalid username/password")
     *        )
     *     )
     *    )
    */
    public function getPods(Request $request){
        $user = $request->user();
        $response = [];
        foreach($user->bookings as $booking){
            $temp = [];
            $temp['id'] = $booking->uuid;
            $temp['pod_name'] = $booking->pod->pod_name;
            $temp['username'] = $booking->user->email;
            $temp['phone'] = $booking->phone;
            $temp['status'] = $booking->status;
            $temp['price'] = $booking->pod->price;
            $temp['booking_date'] = date("d/m/Y", strtotime($booking->booking_datetime));
            $temp['booking_time'] = date("h:i A", strtotime($booking->booking_datetime));
            $response[] = $temp;
        }
        return response()->json($response, 200);

    }

    /**
     * @OA\Get(
     ** path="/api/detail/{id}",
     *   tags={"Detail"},
     *   summary="Get booking detail",
     *   operationId="bookingDetail",
     *   security={
     *      {"bearerAuth": {}}
     *   },
     *
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getPodDetail($id, Request $request){
        $user = $request->user();
        $booking = Booking::where('uuid', $id)->first();
        if ($booking->user->id != $user->id){
            return response()->json([
                'error' => 'Not found'
            ], 401);
        }

        $response = [];
        $response['id'] = $booking->uuid;
        $response['pod_name'] = $booking->pod->pod_name;
        $response['username'] = $booking->user->email;
        $response['phone'] = $booking->phone;
        $response['status'] = $booking->status;
        $response['price'] = $booking->pod->price;
        $response['booking_date'] = date("d/m/Y", strtotime($booking->booking_datetime));
        $response['booking_time'] = date("h:i A", strtotime($booking->booking_datetime));

        return response()->json($response, 200);
    }
}
