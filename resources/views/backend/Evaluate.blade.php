@section('js')
<script src="{{ asset('js/index_image.js') }}" charset="utf-8"></script>
@endsection

@extends('backend.Layouts.master')
@section('title','任務清單')

@section('content')


<div class="row container">

    <div class="row">
        <form method="post" action="{{route('admin.do_Evaluate')}}" class="col s12 loginform" enctype="multipart/form-data">
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
                            <div class="col s4"><input name='start' type="text" class="datepicker"></div>
                        </td>
                        <td>
                            <div class="col s4"><input name='end' type="text" class="datepicker"></div>
                        </td>
                        <td>
                            <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                                <i class="material-icons right">send</i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
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