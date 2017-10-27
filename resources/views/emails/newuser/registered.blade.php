<h1>MIPAD</h1>
<h2>Congratulations!</h2>

{{$user['first_name']}}, you have successfully registered on MIPAD!

<p>Please click on the link below to complete your registration.</p>

<a href="{{$user['link']}}">Verify</a>
<br><br>

<p>
    &copy; {{ \Carbon\Carbon::now()->year }}
</p>