<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
<h1>KWM PADLET</h1>
<ul>
    @foreach ($padlets as $padlet)
        <li><h3>{{$padlet->title}}</h3>{{$padlet->description}}<br>
            <i>Wurde angelegt von: User {{$padlet->user_id}}</i>
            <a href="padlets/{{$padlet->id}}">Padlet anzeigen</a>
        </li>
    @endforeach

        @foreach ($entries as $entrie)
            <li><h3>{{$entrie->title}}</h3>
            </li>
        @endforeach
</ul>
</body>
</html>
