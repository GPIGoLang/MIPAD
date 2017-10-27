<h1>MIPAD</h1>
<h2>Account Verification Link Request</h2>

{{$user['first_name']}}, you have requested for your account verification code. 

<p>Please click on the link below to complete your registration.</p>

<p>Ignore this if you did not make this request.</p>

<a class="btn btn-lg btn success" href="{{$user['link']}}">Verify</a>
<br><br>

<p>
    &copy; {{ \Carbon\Carbon::now()->year }} MIPAD.
</p>