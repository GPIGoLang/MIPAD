<?php
/**
 * Created by PhpStorm.
 * User: gpiproject
 * Date: 9/30/17
 * Time: 6:13 PM
 */
namespace App\Utilities;
use App\Category;
use Illuminate\Http;
use App\User;
use App\UserTest;
class Assessment
{
    private $query_string, $secret_key, $csum, $base_url;
    protected $assessmentRepository;
    public function __construct()
    {
        $this->base_url = config('services.talentbridge.url');
        $this->query_string = [
            'cID' => config('services.talentbridge.cid'),
            'action' => 'getalltest',
        ];
        $this->secret_key = config('services.talentbridge.secret_key');
        $this->query_string = http_build_query($this->query_string, '', '&');

        //Encrypted Param
        $this->csum = sha1(strtolower($this->query_string).$this->secret_key);
    }
    public function getConfiguredTests(){
        $url = $this->base_url.$this->query_string.'&csum='.$this->csum;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $tests = curl_exec($ch);
        curl_close($ch);
        return json_decode($tests);
    }
    /* Associate user with test in Talent Bridge test engine
     and then add a reference to MIPAD user_tests table */
    public function associateUserWithTests(User $user, $cat_id=0){
        $category = Category::find($cat_id);
        $config_tests = $this->getConfiguredTests();
        $testRef = false;
        if($config_tests->success == 'true'){
            foreach($config_tests->testList as $c_test){
                if($category == null){
                    if(preg_match("/(Psychometer)/i", $c_test->value)){
                        $test = $this->registerUserForTest($user, $c_test);
                        $testRef['id'] = $c_test->key;
                        $testRef['user_id'] = $user->id;
                        $testRef['title'] = $c_test->value;
                        $testRef['link'] = $test->testLink;
                        $testRef['username'] = $test->uid;
                        $testRef['accessToken'] = $test->mobileAccessCode;
                        if($test->uid == $user->email){
                            $this->createTestReference($testRef);
                        }
                    }
                }elseif($category != null){
                    if(preg_match("/(".$category->title.")/i", $c_test->value)){ // If the config tests name prefix matches any of the specified names, register the user and store reference on User Test table
                        $test = $this->registerUserForTest($user, $c_test);
                        $testRef['id'] = $c_test->key;
                        $testRef['user_id'] = $user->id;
                        $testRef['title'] = $c_test->value;
                        $testRef['link'] = $test->testLink;
                        $testRef['username'] = $test->uid;
                        $testRef['accessToken'] = $test->mobileAccessCode;
                        if($test->uid == $user->email){
                            $this->createTestReference($testRef);
                        }
                    }
                }
            }
        }
        return $testRef;
    }
    private function createTestReference($rTest) // Configured Test, Referenced Test
    {
        if(UserTest::where('test_id', $rTest['id'])->where('username', $rTest['username'])->exists())
        {
            return true;
        }
        $testRef = UserTest::create([
            'user_id' => $rTest['user_id'],
            'test_id' => $rTest['id'],
            'title' => $rTest['title'],
            'username' => $rTest['username'],
            'access_token' => $rTest['accessToken'],
            'test_link' => $rTest['link']
        ]);
        if($testRef){
            return true;
        }
        return false;
    }
    /*
     * @params $user
     * @params $configured_test
     */
    private function registerUserForTest($user, $configured_test) {
        $query_string = array(
            'cID' => config('services.talentbridge.cid'), // Talent Bridge Customer Identity
            'testId' => $configured_test->key,
            'name' => $user->profile->fullname,
            'uid' => $user->email, // Username as ID
            'action' => 'testAccessRequest',
        );
        $secret_key = config('services.talentbridge.secret_key');
        $query_string = http_build_query($query_string, '', '&');
        //Encrypted Param
        $csum = sha1(strtolower($query_string).$secret_key);
        $url = config('services.talentbridge.url').$query_string.'&csum='.$csum;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $test = json_decode(curl_exec($ch));
        curl_close($ch);
        return $test;
    }
    // Gets user test result from test engine after test taken
    public function storeTestResult(Request $request){
        if(!empty($request)){
            //get user object by authentication token
            $user = User::where('api_token', $request['token'])->first();
            // If user exists
            if($user) {
                $test = new TestResult(); //::create($result);
                $test->test_id = $response['testId'];
                $test->user_id = $user->id;
                $test->result = $response['testResult'];
                $test->cut_off = $response['cutOff'];
                $test->test_name = $response['testName'];
                $test->pdf_path = $response['pdfPath'];
                $test->save();
                try{
                    //send email notification
                    Mail::send('emails.test-notice', [
                        'name' => $user->first_name.' '.$user->last_name,
                        'result_link' => $response['pdfPath']
                    ], function ($mail) use ($user) {
                        $mail->from('noreply@my-gpi.com', 'Global Performance Index');
                        $mail->to('info@my-gpi.com', 'GPI Info')
                            ->subject('GPI - New Test Notification');
                    });
                    \Log::info('Email sent');
                    return response()->json(['msg' => 'Success', 'success' => true]);
                }catch(\Exception $e){
                    \Log::error($e->getMessage());
                    return response()->json(['msg' => 'Error', 'success' => false]);
                }
            }else{
                flash()->error('Server Error! We were unable to process your result');
                return response()->json(['msg' => 'Error', 'success' => false]);
            }
        }else{
            flash()->error('Server Error! We were unable to process your result');
            return response()->json(['msg' => 'Error', 'success' => false]);
        }
    }
    public function displayResults(){
        $results = TestResult::where('user_id', auth()->user()->id)->get();
        return view('tests::self-assessment-results', compact('results'));
    }
    public function resetTest($testID, $uid, $fullname){
        $query_string = array(
            'cID' => config('services.talentbridge.cid'),
            'testId' => $testID,
            'name' => $fullname,
            'uid' => $uid,
            'action' => 'resetOrResumeTest',
            'comment' => 'Comment',
            'resetType' => 'reset'
        );
        $secret_key = config('services.talentbridge.secret_key');
        $query_string = http_build_query($query_string, '', '&');
        //Encrypted Param
        $csum = sha1(strtolower($query_string).$secret_key);
        $url = config('services.talentbridge.url').$query_string.'&csum='.$csum;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $resultObject = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($resultObject, true);

        $success = $result['success'];

        return $success;
    }
}