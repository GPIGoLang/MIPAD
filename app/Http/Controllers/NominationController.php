<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserNominated;
use App\Nominee;
use App\User;
use DB;

class NominationController extends Controller
{
    protected $nominationRepository;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
    }

    public function nominate(Request $request)
    {
        $this->validate($request, [
            'first_name'    => 'required|max:100|min:3',
            'last_name'     => 'required|max:100|min:3',
            'mobile'        => 'required',
            'email'         => 'required|email|unique:users|unique:nominations'
        ]);

        DB::beginTransaction();
        try{
            $nominated = new Nominee();
            $nominated->nominator_id        = \Auth::user()->id;
            $nominated->first_name          = $request->first_name;
            $nominated->last_name           = $request->last_name;
            $nominated->mobile              = $request->mobile;
            $nominated->email               = $request->email;
            $nominated->code                = str_random(40);
            $nominated->status              = 'pending';

            /* Build data form sending email to nominee */
            $user['link'] = 'http://mipad.my-gpi.org/#/register?code='.$nominated->code;
            $user['first_name'] = $request->first_name;

            if($nominated->save()){
                Mail::to($nominated->email)->send(new UserNominated($user));
                DB::commit();
                return response()->json([
                    'nominated'=>true,
                    'message'=>'Your nomination was successful.'
                ]);
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'nominated'=>false,
                'message'=>'Your nomination was not successful.'
            ]);
        }
    }

    public function getNomination(Request $request)
    {
        $nomination = Nominee::where('code', $request->code)
            ->select('email','first_name','last_name','mobile')
            ->first();

        if($nomination) {
            return response()->json([
                'found'=>true,
                'nomination'=>$nomination
            ]);
        }

        return response()->json([
            'found'=>false,
            'message'=>'Nomination not found.'
        ]);
    }

    public function getNominations(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|exists:users'
        ]);

        $user = User::where('email', $request->email)->first();
        if($user->isAdmin()) {
            $nominees = User::where('group_id', 3)
                ->get();
            
            $data = array();
            
            foreach($nominees as $nominee) {
                $n = array(
                    'email' => $nominee['email'],
                    'fullname' => $nominee->profile->fullname
                );

                array_push($data, $n);
            }

            if($nominees->count()) {
                return response()->json([
                    'found' => true,
                    'nominees' => $data
                ]);
            }
    
            return response()->json([
                'found' => false,
                'message' => 'Seems you have not nominated anyone yet.'
            ]);
        }
    }
}
