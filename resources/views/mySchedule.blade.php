<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendar</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <h4>スケジュール確認</h4>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">日付</th>
            <th scope="col">時間</th>
            <th scope="col">コース名</th>
            <th scope="col">スタッフ名</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($schedules as $schedule)
            <tr>
                <th scope="row">{{substr($schedule->schedule_date,0,10)}}</th>
                <td>{{$schedule->cource()->first()->name}}</td>
                <td>{{$schedule->Time()->first()->name}}</td>
                <td>{{$schedule->Staff()->first()->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
