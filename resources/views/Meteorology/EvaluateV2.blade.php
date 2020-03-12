@php
$redis=new Redis ;
$redis->connect("127.0.0.1","6379");
@endphp



@extends('Meteorology.Layouts.master')
@section('title','任務清單')

@section('content')
<div class=" card row " style="margin-top: 0">
    <form method="post" action="{{route('Met.do_Evaluate')}}" class="col s12 loginform" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table class="highlight centered">

            <tbody>
                <tr>
                    <td>
                        <label>
                            <select name='year'>
                            @for ($year = 2016; $year <= (int)date("Y"); $year++)
                                <option value="{{$year}}">{{$year}}</option>
                            @endfor
                            </select>
                            <span>請選擇年分</span>
                        </label>
                    </td>
                    <td>
                        </label>
                        <select name='start_month'>
                            @for ($i = 1; $i <= 12 ; $i++) <option value="{{$i}}">{{$i}}月</option>
                                @endfor
                        </select>
                        <span>請選擇起始月分</span>
                        <label>
                    </td>
                    <td>
                        </label>
                        <select name='end_month'>
                            @for ($i = 1; $i <= 12 ; $i++) <option value="{{$i}}">{{$i}}月</option>
                                @endfor
                        </select>
                        <span>請選擇結束月分</span>
                        <label>
                    </td>
                    <td>
                        <button class="btn waves-effect waves-light evabtn" type="submit" name="action">進行性能評估
                            <i class="material-icons right"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<div id='2016' class="row container evabox">
    @if (session('error'))
    <p class="red-text">
        {{ session('error') }}
    </p>
    @endif
    <table class="highlight">
        <thead>
            <tr>
                <th>任務編號</th>
                <th>時間區間</th>
                <th>下載</th>
                <th>刪除</th>
                <th>查看結果</th>
            </tr>
        </thead>
    @if (count($Evaluate_List) !== 0)


        @foreach($Evaluate_List as $Eva)
        <tbody id="{{$Eva->id}}">
            <tr>
                <td>
                    {{ $loop->index +1 }}
                </td>
                <td>
                    {{str_replace("_","至",$Eva->Time_Period)}}
                </td>
                <td>
                    <a class="green-text" href="{{route('Met.download_Evaluate',['Time_Period'=>$Eva->Time_Period])}}">下載</a>
                </td>
                <td>
                    <a btnid="{{$Eva->id}}" class="red-text delEva" href="javascript:void(0)" url="{{route('Met.delete_Evaluate',['Met_eva'=>$Eva->id])}}">刪除</a>
                </td>
                <td>
                
                    <a class="green-text" href="{{route('Met.detail_Evaluate',['Met_evaluates'=>$Eva->id])}}">查看詳情</a>
                </td>
            </tr>
        </tbody>

        @endforeach

            @endif

            @if($First_unFinish !== null)
            <tbody>
                <td>
                    {{$First_unFinish->id}}
                </td>
                <td>
                    {{str_replace("_","至",$First_unFinish->Time_Period)}}
                </td>
                <td>
                    <p id="wait" time="{{$redis->ttl($First_unFinish->Time_Period)}}"> 待{{$redis->ttl($First_unFinish->Time_Period)}}秒後</p>
                    <p>執行完畢</p>
                </td>
                <script>
                    $(document).ready(function() {
                        let obj = $('#wait');
                        let time = parseInt(obj.attr('time')) * 1000;
                        let MyCounter = function() {
                            if (time <= 0) {
                                window.location.reload()
                            } else {
                                console.log((time / 1000) + " sec...");
                                obj.html(`約${time / 1000}秒後`);
                                setTimeout(MyCounter, 1000);
                            }
                            time -= 1000;
                        }
                        MyCounter();
                    })
                </script>
            </tbody>
            @endif

            @foreach($unFinish_List as $job)
            <tbody>
                <td>
                    {{$job->id}}
                </td>
                <td>
                    {{str_replace("_","至",$job->Time_Period)}}
                </td>
                <td>
                    <p>等待先前已丟出</p>
                    <p>的工作執行完畢後，</p>
                    <p>此工作還須執行{{$job->Execution_Time}}
                        秒</p>
                </td>
            </tbody>
            @endforeach
    </table>


</div>



<script src="{{ asset('js/Evaluate.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.tabs').tabs();
    })
</script>



@endsection