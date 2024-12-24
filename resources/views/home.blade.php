{{--
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
--}}

@extends('layouts.dashboard')

@section('css')
<style>
    .quick-shortcuts{
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
    }
    @media screen and (orientation:portrait) and (max-width: 575px) {
        .quick-shortcuts [class*="col"] {
            flex: 0 0 200px;
        }
    }
</style>
@endsection

@section('content')
<div class="container h-100">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="alert-container">
                @if (session('status'))
                    <div class="alert bg-success alert-dismissible" role="alert">
                        {{ session('status') }}
                    </div>
                @elseif(session('success'))
                    @if (is_array(session('success')))
                        @foreach (session('success')->all() as $success)
                        <div class="alert bg-success alert-dismissible">
                            {{ $success }}
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-success">
                            {{session('success')}}
                        </div>
                    @endif
                @elseif(count($errors)>0)
                    @foreach ($errors->all() as $error)
                        <div class="alert bg-danger opacity-50">
                            {{$error}}
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- <div class="card bg-white">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    {{ __('You are logged in!') }}
                    <p class="mb-0">
                        @feature('isAdmin')
                            Selamat datang {{$role}}
                        @else
                            Halo, {{auth()->user()->name}}
                        @endfeature
                    </p>
                </div>
            </div> --}}
        </div>
    </div>
    @feature('isAdmin')
        @include('components.dashboard.admin')
    @else
        @include('components.dashboard.user')
    @endfeature
</div>
@endsection
