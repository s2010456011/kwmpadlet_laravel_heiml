<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<!-- Blade Syntax-->

<h1>{{$padlet->title}}</h1>
<p>{{$padlet->description}}</p>
<p>Öffentlich zugänglich: {{$padlet->is_public}}</p>
<p>Admin: User mit der ID {{$padlet->user_id}}</p>
<hr/>
<a href="../padlets/">Zurück zur Übersicht</a>

</body>
</html>
