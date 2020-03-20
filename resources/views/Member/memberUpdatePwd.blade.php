@extends('Member.Layouts.master')
@section('title','重設密碼')
@section('content')
<div class="row container" style="margin-top: 10%;">
   <form method="post" action="{{route('Member.UpdatePwd',['member'=>$member->id])}}" class="col s12" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <h3>您將修改{{$member->name}}的密碼</h3>
      @if (session('status'))
      <p class="teal-text">
         {{ session('status') }}
      </p>
      @endif
      <div class="input-field col s12">
         <input id="password" type="password" name="password" class="validate">
         <label for="password"><span>密碼(8~10碼含英文)</span></label>
         @error('password')
         <p class="red-text">{{ $message }}</p>
         @enderror
      </div>



      <div class="input-field col s12">
         <input id="repassword" type="password" name="password_confirmation" class="validate">
         <label for="repassword"><span>確認密碼</span></label>
         @error('password_confirmation')
         <p class="red-text">{{ $message }}</p>
         @enderror
      </div>

      @error('token')
      <p class="red-text">{{ $message }}</p>
      @enderror

      @error('email')
      <p class="red-text">{{ $message }}</p>
      @enderror


      <div class="row">
         <div class="col s6">
            <button class="btn waves-effect waves-light test" type="submit">傳送
               <i class="material-icons right">send</i>
            </button>
         </div>
      </div>


   </form>
</div>
@endsection