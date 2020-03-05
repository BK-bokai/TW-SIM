@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('backend.Layouts.master')
@section('title','資料庫管理系統')

@section('content')
<div class="row container evabox ">
    <div class="col s12 center">
        <ul class="tabs">
            @for ($year=2016; $year <= (int)date("Y") ; $year++) <li class="tab col s3"><a href="#{{$year}}">{{$year}}</a></li>
                @endfor
        </ul>
    </div>
    @for ($year = 2016; $year <= (int)date("Y"); $year++) <div id='{{$year}}' class="row ">
        <div class="col s12 card">
            <table class="highlight centered">
                <thead>
                    <tr>
                        <th>資料時間</th>
                        <th class="row">
                            <div class="col s12">
                                觀測資料筆數
                            </div>
                            <div class="col s12">
                                [溫度,風速,風向]
                            </div>
                        </th>
                        <th class='row'>
                            <div class="col s12">
                                模擬資料筆數
                            </div>
                            <div class="col s12">
                                [溫度,風速,風向]
                            </div>
                        </th>
                        <th>編輯資料</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 12 ; $i++) <tr>
                        <td>{{$year}}年{{$i}}月</td>
                        <td>
                            {{$obsnum_t2[$year][$i]}},
                            {{$obsnum_ws[$year][$i]}},
                            {{$obsnum_wd[$year][$i]}}
                        </td>
                        <td>
                            {{$simnum_t2[$year][$i]}},
                            {{$simnum_ws[$year][$i]}},
                            {{$simnum_wd[$year][$i]}}
                        </td>
                        <td>
                            <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$i,'datatype'=>'Obs','var'=>'T2'])}}" class="waves-effect waves-light btn-small">OBS</a>
                            <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$i,'datatype'=>'Sim','var'=>'T2'])}}" class="waves-effect waves-light btn-small">SIM</a>
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
</div>

@endfor
</div>


<script>
    $(document).ready(function() {
        $('.tabs').tabs();
    })
</script>
@endsection