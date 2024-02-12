@extends('layout.default')

@section('page', 'page__home')

@section('main')
    @include('blocks.news')
    @include('blocks.chat')
    @include('blocks.featured')
    @include('blocks.poll')
    @include('blocks.top_torrents')
    @include('blocks.top_uploaders')
    @include('blocks.latest_topics')
    @include('blocks.online')
@endsection
