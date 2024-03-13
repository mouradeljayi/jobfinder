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
    <h1>Accepted Candidacies</h1>
    <table class="w-full">
        <tr>
            <td class="w-half">
                @if($employer->image)
                <img src="{{ asset('/images/employers/logos/' . $employer->image) }}" alt="employer logo" width="200" />
                @else
                <img src="{{ asset('/images/placeholders/default-logo.png') }}" alt="No Logo Available" width="200" />
                @endif
            </td>
            <td class="w-half">
                <h2>Offer Reference: {{ $offer->id }}</h2>
                <h2>Offer Title: {{ $offer->title }}</h2>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="candidates">
            <tr>
                <th>Candidate Full Name</th>
                <th>Candidate Email</th>
                <th>Candidate Phone Number</th>
                <th>Candidate Education Level</th>
                <th>Candidate Experience</th>
            </tr>
            <tr class="items">
                @foreach($candidacies as $candidacy)
                <td>
                    @if($candidacy->candidate->image)
                    <img src="{{ asset('images/candidates/avatars/' .  $candidacy->candidate->image) }}" width="60" height="60" alt="Candidate Image">
                    @else
                    <img src="{{ asset('/images/placeholders/default-avatar.png') }}" alt="No Image Available" width="60" height="60" />
                    @endif
                </td>
                <td>{{ $candidacy->candidate->first_name ?? 'N/A' }} {{ $candidacy->candidate->last_name ?? 'N/A' }}</td>
                <td>{{ $candidacy->candidate->email ?? 'N/A' }}</td>
                <td>{{ $candidacy->candidate->phone_number ?? 'N/A' }}</td>
                <td>{{ $candidacy->candidate->education_level ?? 'N/A' }}</td>
                <td>{{ $candidacy->candidate->experience ?? 'N/A' }}</td>
                @endforeach
            </tr>
        </table>
    </div>

    <div class="footer margin-top">
        <div>Thank you</div>
        <div>&copy; JobFinder</div>
    </div>
</body>

</html>