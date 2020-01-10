@php
$redis=new Redis ;
$redis->connect("127.0.0.1","6379");
@endphp

@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('backend.Layouts.master')
@section('title','任務清單')

@section('content')
<!-- <input type="file" name="file[]" multiple="multiple" required="required" draggable="true" /> -->
<div class=" card row " style="margin-top: 0">
    <!-- <div class="row">
    <div class="col s12 center">
        <ul class="tabs">
            <li class="tab col s6"><a href="#2016">2016</a></li>
            <li class="tab col s6"><a href="#2019">2019</a></li>
        </ul>
    </div> -->
    <!-- </div> -->
    <form method="post" action="{{route('admin.do_Evaluate')}}" class="col s12 loginform" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table class="highlight centered">

            <tbody>
                <tr>
                    <td>
                        <label>
                            <select name='year'>
                                <option value="2016">2016</option>
                                <option value="2020">2020</option>
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
    @if (count($Evaluate_List) !== 0)
    <table class="highlight">
        <thead>
            <tr>
                <th>任務編號</th>
                <th>時間區間</th>
                <th>下載</th>
            </tr>
        </thead>

        @foreach($Evaluate_List as $Eva)
        <tbody>
            <tr>
                <td>
                    {{$Eva->id}}
                </td>
                <td>
                    {{$Eva->Time_Period}}
                </td>
                <td>
                    @if($Eva->Finish)
                    <a href="{{route('admin.download_Evaluate',['Time_Period'=>$Eva->Time_Period])}}">Download</a>
                    @else
                    <p>請稍後
                        {{$redis->ttl($Eva->Time_Period)}}
                    </p>
                    <div class="preloader-wrapper active">
                        <div class="spinner-layer spinner-green-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
        </tbody>
        @endforeach
    </table>


</div>

@else
<h5 class=" teal-text text-lighten-2">暫時無任何性能評估</h6>
    @endif

    <!-- <script src="{{ asset('js/Evaluate.js') }}"></script> -->
    <script>
        $(document).ready(function() {
            $('.tabs').tabs();
        })
    </script>



    @endsection