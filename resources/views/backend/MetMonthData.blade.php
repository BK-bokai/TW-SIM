@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('backend.Layouts.master')
@section('title','資料庫管理系統')

@section('content')
<style>
    .collection .collection-item.active {
        background-color: gainsboro;
    }
</style>
<div class="row">
    <!-- <div class="col s3 white card"  id='sidebar' style="margin: 0;">
        <li>
            123
        </li>
    </div> -->
    <!-- <div class="col s8 right"> -->
    <div class="collection " style="margin: 0">
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'T2'])}}" class='black-text collection-item {{ $var == "T2" ? "active" : "" }}'>溫度</a>
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'WS'])}}" class='black-text collection-item {{ $var == "WS" ? "active" : "" }}'>風速</a>
        <a href="{{route('admin.MetMonthData',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>'WD'])}}" class='black-text collection-item {{ $var == "WD" ? "active" : "" }}'>風向</a>
    </div>
    <div class="row container evabox">
        請上傳{{$year}}年{{$month}}月的資料
        <div class="row">
            <form action="{{route('admin.UploatMet',['year'=>$year,'month'=>$month,'datatype'=>$datatype,'var'=>$var])}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input name='files[]' type="file" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload one or more files">
                    </div>
                </div>
                <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                    <i class="material-icons right">send</i>
                </button>
                @error('files')
                <p class="red-text">{{ $message }}</p>
                @enderror
            </form>
        </div>


        <div class="row ">
            @if (count($datas) !== 0)
            <div class="col s12 card">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>資料時間</th>
                            <th>資料下載</th>
                            <th>刪除資料</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($datas as $data) 
                        <tr date="{{$data->date}}data">
                            <td>{{$data->date}}</td>
                            <td>
                                <a href="http://" class="green-text">下載</a>
                            </td>
                            <td>
                                <a href="http://" class="delData red-text" url="{{route('admin.DeleteMet',['MetData'=>$data->id,'datatype'=>$datatype,'var'=>$var])}}" datatime="{{$data->date}}" datatype='{{ $datatype == "Obs" ? "觀測資料" : "模擬資料" }}'>刪除</a>
                            </td>
                        </tr>
                        @endforeach
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
        // let main = document.getElementById('main')
        // let sidebar = document.getElementById('sidebar')
        // // let main = $('#main')
        // alert(main.clientHeight)

        // sidebar.style.height = main.offsetHeight+'px'
        // // myElement.style.backgroundColor = "#D93600";
    })
</script>
@endsection