@extends('Member.Layouts.master')
@section('title','會員管理')
@section('content')
<script src="{{ asset('js/member.js') }}" charset="utf-8"></script>

<div class="row container containerBody">
    <div class="col s12 memberList">
        <table class="highlight centered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>帳號 </th>
                    <th>信箱</th>
                    <th>active </th>
                    <th>admin</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr user="{{ $member['id'] }}">
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->active }}</td>
                        <td>
                            @if($member->admin)
                            <i class="fas fa-check-circle"></i>
                            @else
                            <i class="fas fa-times-circle"></i></i>
                            @endif
                        </td>
                        <td>
                            <button style="margin-right: 9px" user-id=" {{ $member['id'] }} " url="" class=" del-btn btn waves-effect waves-light red del_mem ">刪除</button>
                            <button user-id=" {{ $member['id'] }} " url="{{route('Member.memberPage',['member'=>$member->id])}}" class=" del-btn btn waves-effect waves-light green memberPage">編輯</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="" class="btn tooltipped btn-floating btn-large waves-effect waves-light black pulse" data-position="bottom" data-tooltip="新增會員" style="margin-top: 5px;">
            <i class="material-icons">add</i>
        </a>
    </div>
</div>

@endsection