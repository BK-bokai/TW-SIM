@php
$redis=new Redis ;
$redis->connect("127.0.0.1","6379");
$area = ['north'=>'北','center'=>'中','south'=>'南','YunJia'=>'雲嘉','east'=>'東部','ChungYunJia'=>'中雲嘉','all'=>'全台']
@endphp



@extends('backend.Layouts.master')
@section('title','性能評估結果')

@section('content')


<!-- {{$data[0][0][1]}} -->
<!-- 區域、列數、行數 -->
<style>
li.waves-effect:hover{
    background-color: #949e93;
}
</style>
<ul class="pagination white highlight" style="margin-bottom: 0%; margin-top: 0%">
    <li class="waves-effect"><a href="{{route('admin.detail_img_Evaluate',['area'=>'台北','Met_evaluates'=>$id])}}">查看圖片</a></li>
    <li class="waves-effect"><a href="{{route('admin.download_Evaluate',['Time_Period'=>$Time_Period])}}">資料下載</a></li>
</ul>
<div class="tabs-wrapper" style="margin-top: 0%">
    <div id="tabs">
        <div class="col s12 nav-content">
            <ul class="tabs">
                @foreach($area as $key=>$val)
                <li class="tab"><a href="#{{$key}}">{{$val}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>




<div class="row container evabox">
    @foreach($data as $ddd)
    <div id='{{array_keys($area)[$loop->index]}}' class="col s12">
        <table>
            <tbody>
                @foreach($ddd as $dd)
                <tr>
                    @foreach($dd as $d)
                    <td>{{$d}}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>



<script>
    // AFFIX TABS ON SCROLL
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 130) {
            $("#main-nav").addClass("z-depth-0"); // Removes box shadow from #main-nav
            $("#tabs").addClass("tabs-fixed z-depth-0"); // Makes tabs fixed and adds a box shadow
            $(".tabs").addClass("red lighten-3 tabs-transparent"); // Change the color to red
        } else {
            $("#tabs").removeClass("tabs-fixed z-depth-0");
            $(".tabs").removeClass("red lighten-3 tabs-transparent"); // Change the color back from red
        }
    });


    // MATERIALIZE'S JQUERY
    $(document).ready(function() {
        //TABS
        $('ul.tabs').tabs();
        //SCROLLSPY
        $('.scrollspy').scrollSpy();
    });
</script>
@endsection