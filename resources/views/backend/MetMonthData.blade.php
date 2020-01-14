@php
$datatype_china = ['Obs'=>'觀測資料','Sim'=>'模擬資料'];
$var_china = ['T2'=>'溫度','WS'=>'風速','WD'=>'風向'];
@endphp


@extends('backend.Layouts.master')
@section('title','資料庫管理系統')

@section('content')
<style>
    .collection .collection-item.active {
        background-color: gainsboro;
    }
</style>
<div class="row">
    <div class="collection " style="margin: 0">
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'T2'])}}" class='black-text collection-item {{ $var == "T2" ? "active" : "" }}'>溫度</a>
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'WS'])}}" class='black-text collection-item {{ $var == "WS" ? "active" : "" }}'>風速</a>
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'WD'])}}" class='black-text collection-item {{ $var == "WD" ? "active" : "" }}'>風向</a>
    </div>
    <div class="row" style="margin-top: 3%">
        <div class="col s12 center">
            <h3>{{$year}}年{{$month}}月{{$datatype_china[$datatype]}}管理</h3>
            <h3>{{$var_china[$var]}}</h3>
        </div>
    </div>
    <div class="row container">
        <p>請上傳{{$year}}年{{$month}}月的資料</p>
        <div class="row">
            <form action="{{route('admin.UploatMet',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>$var])}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input required name='files[]' type="file" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <button class="btn waves-effect waves-light" type="submit" name="action">
                    Upload
                    <i class="material-icons right">send</i>
                </button>
                @error('files')
                <p class="red-text">{{ $message }}</p>
                @enderror
            </form>
        </div>

        @if($month == '12' && $datatype=='Obs')
        <p class='green'>為配合模擬資料為UTC時間，12月份資料應多一筆隔年1月1日的資料</p>
        <p class='green'>以方便系統處理</p>
        <p>{{$year}}年{{$month}}月應有{{$num+1}}筆資料</p>
        @if(count($datas)<($num+1)) <p id='dataInfo' class='red'>目前僅有{{count($datas)}}筆資料</p>
            @else
            <p id='dataInfo'>目前共有{{count($datas)}}筆資料</p>
            @endif
            @else
            <p>{{$year}}年{{$month}}月應有{{$num}}筆資料</p>
            @if(count($datas)<$num) <p id='dataInfo' class='red'>目前僅有{{count($datas)}}筆資料</p>
                @else
                <p id='dataInfo'>目前共有{{count($datas)}}筆資料</p>
                @endif
                @endif

                <div class="row ">
                    @if (count($datas) !== 0)
                    <div class="col s12 card">
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>資料時間</th>
                                    <th>資料下載</th>
                                    <th>刪除資料</th>
                                    <th>選取</th>
                                </tr>
                            </thead>

                            <tbody>
                                <form id='allForm' action="{{route('admin.Multiple',['method'=>'download','datatype'=>$datatype,'var'=>$var])}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @foreach ($datas as $data)
                                    <tr date="{{$data->date}}data">
                                        <td>{{ $loop->index +1 }}</td>
                                        <td>{{$data->date}}</td>
                                        <td>
                                            <a href="{{route('admin.download_MetMonth',['DataID'=>$data->id,'datatype'=>$datatype,'var'=>$var])}}" class="green-text">下載</a>
                                        </td>
                                        <td>
                                            <a href="#" class="delData red-text" url="{{route('admin.DeleteMet',['DataID'=>$data->id,'datatype'=>$datatype,'var'=>$var])}}" datatime="{{$data->date}}" datatype='{{ $datatype == "Obs" ? "觀測資料" : "模擬資料" }}'>刪除</a>
                                        </td>
                                        <td>
                                            <p>
                                                <label>
                                                    <input name="DataID[]" id="{{$data->id}}" value="{{$data->id}}" type="checkbox" />
                                                    <span></span>
                                                </label>
                                            </p>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <button method='download' class="btn waves-effect waves-light disabled checkbtn" url="{{route('admin.Multiple',['method'=>'download','datatype'=>$datatype,'var'=>$var])}}" type="submit" name="action">
                                                一鍵下載
                                            </button>
                                        </td>
                                        <td>
                                            <button method='delete' class="btn waves-effect waves-light disabled checkbtn" url="{{route('admin.Multiple',['method'=>'delete','datatype'=>$datatype,'var'=>$var])}}" type="submit" name="action">
                                                一鍵刪除
                                            </button>
                                        </td>
                                        <td>
                                            <label>
                                                <input id="checkAll" type="checkbox" />
                                                <span>全選</span>
                                            </label>
                                        </td>
                                    </tr>

                                </form>

                            </tbody>
                        </table>
                    </div>
                    @else
                    目前暫無資料請上傳資料
                    @endif
                </div>
    </div>
    <!-- </div> -->
</div>



<script src="{{ asset('js/MetMonthData.js') }}"></script>


<script>
    $(document).ready(function() {
        $('.tabs').tabs();
        $('#sidenav-1').sidenav({
            edge: 'right'
        });
    })
</script>
@endsection