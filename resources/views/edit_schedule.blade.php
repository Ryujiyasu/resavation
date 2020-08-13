<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <form method="POST">
        @csrf
        <div class="form-group">
            <label for="id">id</label>
            <input class="form-control" id="id" value="{{$schedule->id}}" disabled name="id">
        </div>
        <div class="form-group">
            <label for="name">名前</label>
            <input class="form-control" id="name" value="{{$schedule->name}}" name="name">
        </div>
        <div class="form-group">
            <label for="tel">電話番号</label>
            <input class="form-control" id="tel" value="{{$schedule->tel}}" name="tel">
        </div>



        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" value="{{$schedule->email}}" name="email">
        </div>
        <div class="form-group">
            <label for="cource">コース</label>
            <input class="form-control" id="cource" value="{{$schedule->Cource()->first()->name}}" name="cource">
        </div>
        <div class="form-group">
            <label for="staff">スタッフ</label>
            <input class="form-control" id="staff" value="{{$schedule->Staff()->first()->name}}" name="staff">
        </div>
        <div class="form-group">
            <label for="schedule_date">日付</label>
            <input class="form-control" id="schedule_date" value="{{$schedule->schedule_date}}" name="schedule_date">
        </div>
        <button type="submit" class="btn btn-primary">変更</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>

