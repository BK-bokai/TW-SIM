@extends('auth.Layouts.master')
@section('title','會員登入')
@section('content')
<div class="row container login">
    <form method="post" action="{{route('do_login')}}" class="col s12 loginform" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
            <div class="input-field col s12">
                <input id="name" type="text" class="validate un" name='name'>
                <label for="name"><span class="member">帳號</span></label>
                <?php //print_r(Session::get('errors'))
                ?>
                @error('active')
                <p class="red-text"> {{ $message }} </p>
                @enderror

                @error('name')
                <p class="red-text">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input id="password" type="password" class="validate pw" name='password'>
                <label for="password"><span class="member">密碼</span></label>
                @error('password')
                <p class="red-text">{{ $message }}</p>
                @enderror
            </div>
        </div>




        <div class="row">
            <div class="col s6">
                @if (session('status'))
                <p class="teal-text">
                    {{ session('status') }}
                </p>
                @endif
                <button class="btn waves-effect waves-light test" type="submit">登入
                    <i class="material-icons right">send</i>
                </button>
                <h5><a href="{{ route('fbLogin',['facebook']) }}"><i class="fab fa-facebook-square"></i>使用Facebook登入</a></h5>

                <h6>忘記密碼了?</h6>
                <h5>點選<a href="{{ route('password.request') }}">這裡</a>重置密碼</h5>


            </div>
            <!-- Switch -->
            <div class="switch col s6">
                <p>記住我</p>
                <label>
                    Off
                    <input name='remember' type="checkbox">
                    <span class="lever"></span>
                    On
                </label>
            </div>
        </div>


    </form>
</div>
@endsection