@guest
{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))
@else
{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard',['title'=>"Ooops..",'crumbs'=>[]])

@section('content')
<section class="container h-100">
	<div class="d-flex justify-content-center align-items-center h-100">
		<div class="contents">
			<h1 class="text-center">404</h1>
			<h5 class="text-center">{{__('Not Found')}}</h5>
			<p class="text-center">{{__('The page you are looking for does not exist.')}}</p>
		</div>
	</div>
</section>
@endsection
@endguest

