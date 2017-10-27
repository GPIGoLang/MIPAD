<?php

namespace App\Http\Controllers;

use App\UserTest;
use App\User;
use Illuminate\Http\Request;
use App\Utilities\Assessment;
class AssessmentController extends Controller
{
    protected $assessment;
    public function __construct(Assessment $assessment)
    {
        $this->middleware('auth:api')
        ->except('getTestResult');
        $this->assessment = $assessment;
    }
    // Retrieve tests for logged in nominees
    public function tests(Request $request) {
        $assessments = UserTest::where('username', $request->user()->email)
            ->select('id','test_id','title','status','access_token','test_link')
            ->get();
        if($assessments->count()){
            if($request->user()->category_id != 0) {
                $tests = array();
                foreach($assessments as $assessment) {
                    if($assessment->title == 'Politics and Government') {
                        $tests[0] = $assessment;
                    }elseif($assessment->title == 'Business and Entrepreneurship') {
                        $tests[1] = $assessment;
                    }elseif($assessment->title == 'Media and Culture') {
                        $tests[2] = $assessment;
                    }elseif($assessment->title == 'Religious and Humanitarian'){
                        $tests[3] = $assessment;
                    }
                }
            }else{
                $tests[] = $assessments[0];
            }
            return response()->json([
                'found'=>true,
                'assessments' => $tests
            ]);
        }
        return response()->json([
            'found'=>false,
            'message' => 'No assessments where found for you.'
        ]);
    }
    public function getTestsByStatus(Request $request, $username, $status) {
        $tests = UserTest::where('user_id', $request->user()->id)
            ->select('id','test_id','title','username','status','access_token','test_link')
            ->where('username', $username)
            ->where('status', $status)
            ->get();
        if($tests->count()){
            return response()->json([
                'found'=>true,
                'assessments' => $tests
            ]);
        }
        return response()->json([
            'found'=>false,
            'message' => 'No assessments where found for this user.'
        ]);
    }
    /*
    * Retrieves users test details after assessment have been submitted
    */
    public function getTestResult(Request $request){
        //get user object
        $test = UserTest::where('username', $request->uid)->where('test_id', $request->testId)->first();
        if($test) {
            $test->score = $request->testResult;
            $test->cut_off = $request->cutOff;
            $test->report_link = $request->pdfPath;
            $test->status = 'closed';
            if($test->save()){
                $user['score'] = $test->score;
                $user['test_title'] = $test->title;

                //send mail notification

                return response()->json([
                    'msg' => 'Success:Connection Successful',
                    'success' => true
                    ],200);
            }
            return response()->json([
                'msg' => 'Success:Connection Successful',
                'success' => false
                ],404);
        }
        return response()->json([
            'msg' => 'Success:Connection Successful',
            'success' => false
            ],404);
    }
    public function getNomineeAssessments(Request $request) {
        $user = User::where('api_token', $request->api_token)
            ->where('email', $request->user()->email)
            ->first();
        if($user && $user->isAdmin()) {
            $assessments = UserTest::where('username', $request->email)
                ->select('id','test_id','title','status')
                ->get();
            if($assessments->count()) {
                if($assessments->count()>1) {
                    $tests = array();
                    foreach($assessments as $assessment) {
                        if($assessment->title == 'Politics and Government') {
                            $tests[0] = $assessment;
                        }elseif($assessment->title == 'Business and Entrepreneurship') {
                            $tests[1] = $assessment;
                        }elseif($assessment->title == 'Media and Culture') {
                            $tests[2] = $assessment;
                        }elseif($assessment->title == 'Religious and Humanitarian'){
                            $tests[3] = $assessment;
                        }
                    }
                    return response()->json([
                        'found' => true,
                        'tests' => $tests
                    ]);
                }else{
                    $tests = array();
                    foreach($assessments as $assessment) {
                        $tests[0] = $assessment;
                    }
                    return response()->json([
                        'found' => true,
                        'tests' => $tests
                    ]);
                }
            }
            return response()->json([
                'found' => false,
                'message' => 'No assessment found for this nominee'
            ]);
        }
        return response()->json([
            'found' => false,
            'message' => 'Unauthorized.'
        ]);
    }
    public function resetTest(Request $request) {
        $this->validate($request, [
                'testId' => 'required',
                'uid' => 'required',
                'fullname' => 'required'
            ]);
        $user = User::where('api_token', $request->api_token)
        ->first();
        $test = UserTest::where('username', $request->uid)
            ->where('test_id', $request->testId)
            ->first();
        if($user && $user->isAdmin()) {
            $res = $this->assessment->resetTest($request->testId, $request->uid, $request->fullName);
            if($res) {
                $test->score = 0;
                $test->cut_off = 0;
                $test->report_link = '';
                $test->status = 'open';
                $test->save();
            }
            return response()->json(['success' => $res]);
        }
        return response()->json(['success' => false, 'message' => 'Operation not available']);
    }
    public function displayResult(Request $request) {
        $user = User::where('id', $request->user()->id)
            ->whereOr('api_token', $request->api_token)->first();
        if($user && $user->isAdmin()) {
            $test = UserTest::where('username', $request->email)
                ->where('test_id', $request->test_id)
                ->where('status', 'closed')
                ->select('report_link','score','cut_off','title')
                ->first();
            if($test) {
                return response()->json([
                    'found' => true,
                    'test' => $test
                ]);
            }
            return response()->json([
                'found' => false,
                'message' => 'Test either not taken or does not exist.'
            ]);
        }else{
            $test = UserTest::where('username', $request->user()->email)
                ->where('test_id', $request->test_id)
                ->where('status', 'closed')
                ->select('score','cut_off','title')->first();
            if($test) {
                return response()->json([
                    'found' => true,
                    'test' => $test
                ]);
            }
            return response()->json([
                'found' => false,
                'message' => 'Test either not taken or does not exist.'
            ]);
        }
    }

    public function getConfigTest() {
        $tests = $this->assessment->getConfiguredTests();

        return response()->json([
            $tests
        ]);
    }
}