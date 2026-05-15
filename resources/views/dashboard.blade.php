@extends('layouts.app')

@section('content')

<livewire:chat-box :channel="$channel" />

@endsection