@php
$redis=new Redis ;
$redis->connect("127.0.0.1","6379");
$places =['鞍部', '淡水站', '竹子湖', '基隆', '台北', '新屋', '板橋', '新竹', '宜蘭', '蘇澳', '梧棲',
'台中', '花蓮', '日月潭', '阿里山', '嘉義', '玉山', '成功', '永康', '台南', '台東', '高雄', '大武', '恆春']
@endphp



@extends('backend.Layouts.master')
@section('title','性能評估結果')

@section('content')
<style>
    li.waves-effect:hover {
        background-color: #949e93;
    }

    img.lazyload {
        width: 100%;
        height: auto;
    }
</style>
<ul class="pagination white" style="margin-top: 0%">
    <li class="waves-effect"><a href="{{route('admin.detail_Evaluate',['Met_evaluates'=>$id])}}">查看資料</a></li>
    <li class="waves-effect"><a href="{{route('admin.download_Evaluate',['Time_Period'=>$Time_Period])}}">資料下載</a></li>
</ul>
<ul class="pagination">
    <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
    @foreach($places as $place)
    <li class="waves-effect @if($area == $place) active  @endif"><a href="{{route('admin.detail_img_Evaluate',['area'=>$place,'Met_evaluates'=>$id])}}">{{$place}}</a></li>
    @endforeach
    <li class="waves-effect"><a href="{{route('admin.detail_img_Evaluate',['area'=>$places[array_search($area,$places)+1],'Met_evaluates'=>$id])}}"><i class="material-icons">chevron_right</i></a></li>

</ul>

<div class='container row evabox'>
    <!-- <div class="carousel carousel-slider center">
        <div class=" carousel-item white-text" href="#two!">
            <img class='responsive-img' src="{{ asset($T2img)}}">
        </div>
        <div class="carousel-item white-text" href="#three!">
            <img  class='responsive-img' src="{{ asset($WSimg)}}">
        </div>
        <div class="carousel-item white-text" href="#four!">
            <img class='responsive-img' src="{{ asset($WDimg)}}">
        </div>
    </div> -->
    <div class="carousel carousel-slider center">
        <div class="carousel-item red white-text" href="#one!">
            <img class='responsive-img' src="{{ asset($T2img)}}">   
        </div>
        <div class="carousel-item amber white-text" href="#two!">
            <img class='responsive-img' src="{{ asset($T2img)}}">   
        </div>
        <div class="carousel-item green white-text" href="#three!">
            <img class='responsive-img' src="{{ asset($T2img)}}">p>
        </div>
    </div>

    <!-- <div class="my-slider">
        <div><img class="lazyload" loading="lazy" data-src="{{ asset($T2img)}}" width="320" height="240" alt=""></div>
        <div><img class="lazyload" loading="lazy" data-src="{{ asset($T2img)}}" width="320" height="240" alt=""></div>
        <div><img class="lazyload" loading="lazy" data-src="{{ asset($T2img)}}" width="320" height="240" alt=""></div>
    </div> -->
</div>





<script>
    $(document).ready(function() {
        $('.carousel').carousel({
            dist: 0,
            // padding: 0,
            fullWidth: true,
            // shift:0,
            // indicators: true,
            // duration: 100,
        });
    });
</script>
@endsection