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

    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
    <script>
        $(function(){
            $('#mySchedule').on('click',function(){
                let member_id =$("#number").val();
                window.location.href = '/getMySchedule/'+member_id;
            });
            $('#button').on("click",function(){
                let member_id =$("#number").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax('http://127.0.0.1:8000/getMember',
                    {
                        type:"post",
                        data:{
                            "member_id":member_id,
                        }
                    }).done(function(data){
                    console.log(data.data.name);
                    $('#name').val(data.data.name);
                    $('#email').val(data.data.email);
                    $('#tel').val(data.data.tel);
                }).fail(function(data) {
                    alert(data);
                });;




            });
            $('.day').on({
                'mouseenter': function(){$(this).addClass('focus')},
                'mouseleave': function(){$(this).removeClass('focus')},
                'click':function(){
                    let day = $('.month').text().substr(0,4)+"-"+$('.month').text().substr(5,2)+"-"+( '00' + $(this).text() ).slice( -2 );

                    $("#booking_calender").hide();
                    $("#date").val(day);
                    $("#date_row").show();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax('/schedule/getData',
                        {
                            type:"get",
                            data:{
                                date:day,
                                staff: $('#staff').val()
                            }
                        }).done(function(data){
                        $schedules=data.schedules;
                        $.each($schedules,function(index,item){
                            $("#schedule_choice").append("<option value="+item.id+">"+item.name+"</option>");
                        })
                    });

                    $("#booking_schedule_choice").show();
                    $('#booking_cource_choice').show();
                    $("#submit").show();
                }
            });
            if ( {{ $target_staff }} != 0 ){
                $('#staff').val({{ $target_staff }});
                let today = new Date();
                if(today.getMonth() == {{$month_check}}){
                    $("#prev").remove();
                    $("#next").attr("href", "?ym={{ $next }}&staff={{ $target_staff }}");
                }else{
                    $("#prev").attr("href", "?ym={{ $prev }}&staff={{ $target_staff }}");
                    $("#next").remove();
                }

                $('#booking_calender').show();

            }

            $('#staff').on("change",function(){
                $("#prev").remove();
                $("#next").attr("href", "?ym={{ $next }}&staff="+$(this).val());
                $('#booking_calender').show();
            });
        });

    </script>
    <script>

    </script>
</head>
<body>
@if (session('flash_message'))
    <div class="flash_message">
        {{ session('flash_message') }}
    </div>
@endif
<form method="POST" >
    <div class="container m-3">
        <p>あなたの情報を教えてください。</p>
        <p>会員番号または、<br>名前・メール・電話番号を入力してください</p>

        @csrf
        <div class="form-group row">
            <label for="number" class="col-3 col-form-label">会員番号</label>
            <div class="col-4">
                <input name="number"  class="form-control" type="text" placeholder="000999" id="number">
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-primary" id="button">情報を取得</button>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-success" id="mySchedule">日程確認</button>
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-3 col-form-label">名前</label>
            <div class="col-9">
                <input name="name"  class="form-control" type="text" placeholder="田中太郎" id="name">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-3 col-form-label">メール</label>
            <div class="col-9">
                <input name="email" class="form-control" type="email" placeholder="mail@gmail.com" id="email">
            </div>
        </div>
        <div class="form-group row">
            <label for="tel" class="col-3 col-form-label">電話番号</label>
            <div class="col-9">
                <input class="form-control" type="tel" placeholder="09099999999" id="tel" name="tel">
            </div>
        </div>
        <div class="form-group row">
            <label for="straff" class="col-3 col-form-label">スタッフ</label>
            <div class="col-9">
                <select id="staff" class="form-control">
                    <option value="">------</option>
                    @foreach ($staffs as $staff)
                        <option value="{{$staff->id}}">{{$staff->name}}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div id="date_row"class="form-group row" style="display:None;">
            <label for="date" class="col-3 col-form-label">日付</label>
            <div class="col-9">
                <input type="text" class="form-control" id="date" name="date">
            </div>
        </div>
        <div id="booking_schedule_choice" style="display: None"class="form-group row">
            <label for="schedule_choice" class="col-3 col-form-label">時間</label>
            <div class="col-9">
                <select name="schedule_choice" id="schedule_choice" class="form-control">

                </select>
            </div>
        </div>
        <div id="booking_cource_choice" style="display: None"class="form-group row">
            <label for="cource_choice" class="col-3 col-form-label">コース</label>
            <div class="col-9">
                <select name="cource_choice" id="cource_choice" class="form-control">
                    @foreach($cources as $cource)
                    <option value="{{$cource->id}}">{{$cource->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="submit" id="submit" style="display:None">
    </div>
    <div id="booking_calender" style="display:None;" class="flex-center position-ref">
        <div class="content">
            <div>
                <a id ="prev" href="?ym={{ $prev }}">&lt;</a>
                <span class="month">{{ $month }}</span>
                <a id = "next" href="?ym={{ $next }}">&gt;</a>
            </div>

            <table class="table table-bordered">
                <tr>
                    <th>日</th>
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th>土</th>
                </tr>
                @foreach ($weeks as $week)
                    {!! $week !!}
                @endforeach
            </table>

        </div>
        {{-- .content --}}
    </div>
    <div id="schedule" style="display: None"></div>
</form>
</body>
<footer style="text-align:center">Send Grid</footer>

</html>
