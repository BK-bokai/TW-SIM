@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('Meteorology.Layouts.master')
@section('title','任務清單')

@section('content')

<div class=" card row addeva">
    <form method="post" action="{{route('Met.do_Evaluate')}}" class="col s12 loginform" enctype="multipart/form-data">
        {{ csrf_field() }}
        <table class="highlight">
            <thead>
                <tr>
                    <th>起始日期</th>
                    <th>結束日期</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="col s8">
                            <input name='start' id='start' type="text" class="datepicker" required>
                            <label for="start">請選擇起始時間</label>
                        </div>

                    </td>
                    <td>
                        <div class="col s8">
                            <input name='end' id='end' type="text" class="datepicker" required>
                            <label for="end">請選擇結束時間</label>
                        </div>
                    </td>
                    <td>
                        <button class="btn waves-effect waves-light" type="submit" name="action">進行性能評估
                            <i class="material-icons right"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

<div class="row container evabox">
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
                    <a href="{{route('Met.download_Evaluate',['Time_Period'=>$Eva->Time_Period])}}">Download</a>
                    @else
                    <p>請稍後</p>
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
    <script>
        $(document).ready(function() {
            let currYear = (new Date()).getFullYear();

            $('.datepicker').datepicker({
                // maxDate:new Date(2016,06,30),
                yearRange: [2015, 2016],
                defaultDate: new Date(2016, 05, 01),
                maxDate: new Date(2016, 05, 30),
                minDate: new Date(2016, 05, 01),
                // 這有差一隔月，所以如果要20160601~20160630就要這定20160501~20160530
                format: 'yyyy-mm-dd',
                i18n: {
                    months: [
                        '一月',
                        '二月',
                        '三月',
                        '四月',
                        '五月',
                        '六月',
                        '七月',
                        '八月',
                        '九月',
                        '十月',
                        '十一月',
                        '十二月'
                    ],
                    monthsShort: [
                        '1月',
                        '2月',
                        '3月',
                        '4月',
                        '5月',
                        '6月',
                        '7月',
                        '8月',
                        '9月',
                        '10月',
                        '11月',
                        '12月'
                    ],
                    weekdays: [
                        '星期天',
                        '星期一',
                        '星期二',
                        '星期三',
                        '星期四',
                        '星期五',
                        '星期六',
                    ],
                    weekdaysAbbrev: [
                        '日', '一', '二', '三', '四', '五', '六'
                    ],
                    weekdaysShort: [
                        '星期天',
                        '星期一',
                        '星期二',
                        '星期三',
                        '星期四',
                        '星期五',
                        '星期六',
                    ]

                }
            });

        });
    </script>
    @endsection