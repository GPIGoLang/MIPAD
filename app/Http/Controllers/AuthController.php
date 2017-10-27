<?php
namespace App\Http\Controllers;
use App\Category;
use App\UserTest;
use App\Utilities\Assessment;
use App\Utilities\Nomination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use App\UserProfile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserRegistered;
use App\Mail\NewUserCodeResent;
class AuthController extends Controller
{
    protected $assessment;
    public function __construct(Assessment $assessment)
    {
        $this->middleware('auth:api')
            ->only('logout');

        $this->assessment = $assessment;
    }
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
    }

    public function register(Request $request) {
        // Validate new user fields
        $this->validate($request, [
            'email' => 'required|email|unique:users|max:255',
            'fullname' => 'required|max:150|min:3',
            'mobile' => 'required|max:14|min:11',
            'password' => 'required|confirmed|min:6'
        ]);
        //Register user
        DB::beginTransaction();
        try {
            $user = new User();
            $user->email = $request->email;
            $user->activation_code = base64_encode(str_random(60));
            $user->api_token = base64_encode(str_random(40));
            $user->password = Hash::make('password');
            $user->org_id = 2;
            $user->group_id = 3;
            $user->verified = 1;
            if($user->save()) {
                $profile = new UserProfile();
                $profile->fullname = $request->fullname;
                $profile->dob = \Carbon\Carbon::now();
                $profile->mobile = $request->mobile;
                $user->profile()->save($profile);
            }
            DB::commit();
            return response()->json(['registered'=>true, 'email'=>$user->email, 'activation'=>$user->activation_code]);
        }catch(\Exception $qe){
            \Log::debug($qe);
            DB::rollback();
            // If not successful, send false (boolean) response to the consumer
            return response()->json(['registered'=>false]);
        }
    }

    public function checkOrgRefId(Request $request) {
        $refId = $request->org_refid;
        $ref = \DB::table('organizations')->where('refid', $refId)->first();
        if($ref){
            $user = User::where('activation_code', $request->activation_code)
                ->where('org_id', $ref->id)
                ->with('profile')
                ->first();
                if($user){
                    return response()->json([
                        'found' => true,
                        'fullname' => $user->profile->fullname,
                        'email' => $user->email
                    ]);
                }
            return response()->json(['found'=>false, 'message'=>'This user account does not exist.']);
        }
        return response()->json(['found'=>false, 'message'=>'This organization does not exist']);
    }
    public function login(Request $request) {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->input('email'))
            ->first();
        if($user && $user->verified == 0){
            return response()->json([
                'authenticated' => false,
                'message' => 'Your account have not been activated. Check your email and click on the verify link.',
                'statusCode' => 423
            ], 423);
        }else if($user){
            if(Hash::check($request->input('password'), $user->password)){

                if($user->api_token != null) {
                    return response()->json([
                        'authenticated' => true,
                        'email' => $user->email,
                        'api_token' => $user->api_token,
                        'admin' => $user->isAdmin()
                    ], 200);
                }
                $api_token = base64_encode(str_random(40));
                User::where('email', $request->input('email'))->update(['api_token' => $api_token]);
                return response()->json([
                    'authenticated' => true,
                    'email' => $user->email,
                    'api_token' => $api_token,
                    'admin' => $user->isAdmin()
                ], 200);
            }else{
                return response()->json(['authenticated'=>false, 'message' => 'Invalid username or password.', 'statusCode'=>401], 401);
            }
        }else{
            return response()->json(['authenticated'=>false, 'message'=>'This user does not exist on MIPAD system.', 'statusCode'=>404], 404);
        }
    }
    public function logout(Request $request) {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return response()->json([
            'logged_out' => true
        ]);
    }
    public function setNomineeUpForTest(Request $request) {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6'
        ]);
        if(UserTest::where('username', $request->email)->exists()) {
            return response()->json([
                'registered'=>true,
                'message' => 'Your account have already been setup. Just login and take them.'
            ]);
        }
        $refId = $request->orgrefid;
        $ref = \DB::table('organizations')->where('refid', $refId)->first();

        if($ref) {
            $user = User::where('email', $request->email)
            ->where('org_id', $ref->id)
            ->where('activation_code', $request->code)
            ->first();
            if($user) {
                $user->password = Hash::make($request->password);
                $user->category_id = $request->cat_id;
                $user->save();

                if($this->assessment->associateUserWithTests($user, $request->cat_id))
                {
                    return response()->json([
                        'registered'=>true
                    ]);
                }else{
                    return response()->json([
                        'registered' => false,
                        'message' => 'You could not be registered at this moment.'
                    ]);
                }
            }else{
                return response()->json([
                    'registered' => false,
                    'message' => 'This account could not be found.'
                ]);
            }
        }

        return response()->json([
           'registered' => false,
            'message' => "We don't have your organization registered."
        ]);
    }
    public function verify(Request $request) {
        $this->validate($request, [
            'email' => 'required'
        ]);
        $user = User::where('email', $request->email)
            ->where('activation_code', $request->code)
            ->first();
        if($user)
        {
            if($user->verified == 0){
                $user->verified = 1;

                if($user->save())
                {
                    if($this->assessment->associateUserWithTests($user))
                    {
                        return response()->json([
                            'verified'=>true,
                            'status'=>1
                        ]);
                    }
                }
            }elseif($user->verified == 1)
            {
                return response()->json([
                    'verified'=>true,
                    'status'=>2
                ]);
            }
        }
        return response()->json([
            'verified'=>false,
            'status'=>3
        ]);
    }
    public function resendVerificationCode(Request $request) {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $user = User::where('email', $request->email)
            ->first();
        if($user){
            $data['email'] = $user->email;
            $data['first_name'] = $user->profile->first_name;
            $data['last_name'] = $user->profile->last_name;
            $data['subject'] = 'Activation link';
            $data['link'] = config('app.base_url').'verify?email=' . $user->email . '&code=' . $user->activation_code;
            Mail::to($user->email)->send(new NewUserCodeResent($data));
            return response()->json([
                'resent'=>true,
                'message'=>'Verification link have been resent to your email, '.$request->email.' Please check and click on the link to verify your account.'
            ]);
        }
        return response()->json([
            'resent'=>false,
            'message'=>'We do not have your record.'
        ]);
    }
    // Validate user email
    public function validateEmail($email) {
        return User::where('email', $email)->exists();
    }
    // Validate user's username
    public function validateUsername($username) {
        return User::where('username', $username)->exists();
    }
    public function getCountries() {
        return null; // \DB::table('countries')->get();
    }
    public function getStates($country_id) {
        return \DB::table('states')
            ->where('country_id', '=', $country_id)
            ->get();
    }
    private function calculateUserAge($date)
    {
        $curYear = Carbon::now();
        $age = $curYear->year - $date->year;
        return $age;
    }    
}
