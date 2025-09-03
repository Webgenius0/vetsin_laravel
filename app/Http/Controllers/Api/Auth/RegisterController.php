<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistationOtp;
use App\Models\EmailOtp;
use App\Models\ProfileOption;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{

    use ApiResponse;

    /**
     * Send a Register (OTP) to the user via email.
     *
     * @param  \App\Models\User  $user
     * @return void
     */

    private function sendOtp($user)
    {
        $code = rand(1000, 9999);

        // Store verification code in the database
        $verification = EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_code' => $code,
                'expires_at'        => Carbon::now()->addMinutes(15),
            ]
        );

        Mail::to($user->email)->send(new RegistationOtp($user, $code));
    }

    /**
     * Register User
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userRegister(Request $request)
    {
        // fetch allowed keys from DB
        $idealValues = ProfileOption::where('group', 'ideal_connection')->pluck('label')->toArray();
        $relocateValues = ProfileOption::where('group', 'willing_to_relocate')->pluck('label')->toArray();

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'avatar'         => 'required|image|mimes:jpeg,png,jpg,svg|max:20480',
            'images'         => 'nullable|array',
            'images.*'       => 'image|mimes:jpeg,png,jpg,svg|max:20480',
            'password'       => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'agree_to_terms' => 'required|boolean',

            // Dating app fields
            'date_of_birth'       => 'nullable|date_format:m/d/Y|before:today', // MM/DD/YYYY
            'location'            => 'nullable|string|max:255',
            'relationship_goal'   => 'nullable|in:casual,serious,friendship,marriage',
            'ideal_connection'    => ['nullable', Rule::in($idealValues)],
            'willing_to_relocate' => ['nullable', Rule::in($relocateValues)],
            'preferred_age_min'   => 'nullable|integer|min:18|max:120',
            'preferred_age_max'   => 'nullable|integer|min:18|max:120',
            'preferred_property_type' => 'nullable|in:apartment,house,condo,townhouse,studio,any',
            'identity'            => 'nullable|in:buyer,seller,renter,investor',
            'budget_min'          => 'nullable|numeric|min:0',
            'budget_max'          => 'nullable|numeric|min:0',
            'preferred_location'  => 'nullable|string|max:255',
            'perfect_weekend'     => 'nullable|string|max:1000',
            'cant_live_without'   => 'nullable|string|max:1000',
            'quirky_fact'         => 'nullable|string|max:1000',
            'about_me'            => 'nullable|string|max:2000',
            'tags'                => 'nullable|array',
            'tags.*'              => 'string|max:50',
        ], [
            'password.min' => 'The password must be at least 8 characters long.',
            'preferred_age_max.gte' => 'The preferred age max must be greater than or equal to preferred age min.',
            'budget_max.gte' => 'The budget max must be greater than or equal to budget min.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        DB::beginTransaction();
        try {

            // upload avatar if exists
            if ($request->hasFile('avatar')) {
                $avatarPath = uploadImage($request->file('avatar'), 'User/Avatar');
                $request->merge(['avatar' => $avatarPath]);
            } else {
                $request->merge(['avatar' => null]);
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->avatar = $request->input('avatar');
            $user->password = Hash::make($request->input('password'));
            $user->agree_to_terms = $request->input('agree_to_terms');

            // Dating app fields
            $user->date_of_birth = $request->input('date_of_birth');
            $user->location = $request->input('location');
            $user->relationship_goal = $request->input('relationship_goal');
            $user->ideal_connection = $request->input('ideal_connection');
            $user->willing_to_relocate = $request->input('willing_to_relocate');
            $user->preferred_age_min = $request->input('preferred_age_min');
            $user->preferred_age_max = $request->input('preferred_age_max');
            $user->preferred_property_type = $request->input('preferred_property_type');
            $user->identity = $request->input('identity');
            $user->budget_min = $request->input('budget_min');
            $user->budget_max = $request->input('budget_max');
            $user->preferred_location = $request->input('preferred_location');
            $user->perfect_weekend = $request->input('perfect_weekend');
            $user->cant_live_without = $request->input('cant_live_without');
            $user->quirky_fact = $request->input('quirky_fact');
            $user->about_me = $request->input('about_me');

            $user->save();

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = uploadImage($image, 'User/Images');
                    $user->images()->create([
                        'image_url' => $imagePath,
                    ]);
                }
            }

            if ($request->has('tags')) {
                $tags = $request->input('tags');
                $user->tags()->sync($tags, ['created_at' => Carbon::now()]);
            }

            $this->sendOtp($user);

            if ($request->has('device_token')) {
                $user->device_token = $request->input('device_token');
                $user->save();
                NotificationService::sendWelcomeNotification($user);
            }

            DB::commit();
            return $this->success($user, 'User registered successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }


    /**
     * Verify the OTP sent to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otpVerify(Request $request)
    {

        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:4',
            'device_token' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Retrieve the user by email
            $user = User::where('email', $request->input('email'))->first();

            $verification = EmailOtp::where('user_id', $user->id)
                ->where('verification_code', $request->input('otp'))
                ->where('expires_at', '>', Carbon::now())
                ->first();


            if ($verification) {
                // Update device token if provided
                if ($request->has('device_token')) {
                    $user->device_token = $request->input('device_token');
                    $user->save();
                }

                $user->email_verified_at = Carbon::now();
                $user->save();

                $verification->delete();

                $token = JWTAuth::fromUser($user);

                $user->setAttribute('token', $token);


                return $this->success($user, 'OTP verified successfully', 200);
            } else {

                return $this->error([], 'Invalid or expired OTP', 400);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Resend an OTP to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function otpResend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Retrieve the user by email
            $user = User::where('email', $request->input('email'))->first();

            $this->sendOtp($user);

            return $this->success($user, 'OTP has been sent successfully.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }


    function emailExists()
    {
        // check the email exists or not
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|unique:users,email',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }
        // If the email does not exist, return a success response
        return $this->success([], 'Email does not exist', 200);
    }
}
