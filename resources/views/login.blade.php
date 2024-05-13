<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accepted Candidacies</title>
    <link rel="stylesheet" href="{{ asset('pdf.css') }}" type="text/css">
</head>

<body>
    <h1>Authelication with google</h1>


    <div class="margin-top">
      <button> <a href="{{route('google-auth')}}">Continue with google</a></button>
    </div>
    <div class="margin-top">
        <button> <a href="{{route('github-auth')}}">Continue with github</a></button>
      </div>

    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; JobFinder</div>
    </div>
</body>

</html>
