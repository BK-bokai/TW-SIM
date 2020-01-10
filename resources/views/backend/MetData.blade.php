@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('backend.Layouts.master')
@section('title','資料庫管理系統')

@section('content')
<div class="row container evabox ">

    <div class="col s12 center">
        <ul class="tabs">
            <li class="tab col s6"><a href="#2016">2016</a></li>
            <li class="tab col s6"><a href="#2020">2020</a></li>
        </ul>
    </div>

    <div id='2016' class="row ">
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
                        <td>2016年{{$i}}月</td>
                        <td>
                            {{$obsnum_t2[2016][$i]}},
                            {{$obsnum_ws[2016][$i]}},
                            {{$obsnum_wd[2016][$i]}}
                        </td>
                        <td>
                            {{$simnum_t2[2016][$i]}},
                            {{$simnum_ws[2016][$i]}},
                            {{$simnum_wd[2016][$i]}}
                        </td>
                        <td>
                            <a href="{{route('admin.MetMonthData',['year'=>2016,'month'=>$i,'datatype'=>'Obs','var'=>'T2'])}}" class="waves-effect waves-light btn-small">OBS</a>
                            <a href="{{route('admin.MetMonthData',['year'=>2016,'month'=>$i,'datatype'=>'Sim','var'=>'T2'])}}" class="waves-effect waves-light btn-small">SIM</a>
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
    <div id='2020' class="row ">
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
                        <td>2020年{{$i}}月</td>
                        <td>
                            {{$obsnum_t2[2020][$i]}},
                            {{$obsnum_ws[2020][$i]}},
                            {{$obsnum_wd[2020][$i]}}
                        </td>
                        <td>
                            {{$simnum_t2[2020][$i]}},
                            {{$simnum_ws[2020][$i]}},
                            {{$simnum_wd[2020][$i]}}
                        </td>
                        <td>
                            <a href="{{route('admin.MetMonthData',['year'=>2020,'month'=>$i,'datatype'=>'Obs','var'=>'T2'])}}" class="waves-effect waves-light btn-small">OBS</a>
                            <a href="{{route('admin.MetMonthData',['year'=>2020,'month'=>$i,'datatype'=>'Sim','var'=>'T2'])}}" class="waves-effect waves-light btn-small">SIM</a>
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.tabs').tabs();
    })
</script>
@endsection