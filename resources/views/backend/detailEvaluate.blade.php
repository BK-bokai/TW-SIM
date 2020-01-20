@php
$redis=new Redis ;
$redis->connect("127.0.0.1","6379");
@endphp



@extends('backend.Layouts.master')
@section('title','任務清單')

@section('content')

@php
print_r($data)
@endphp

@endsection