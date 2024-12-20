@extends('layouts.layout')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-200 py-6 sm:py-12">
    <div class="max-w-5xl mx-auto overflow-hidden rounded-md bg-white shadow">
        <div class="grid grid-cols-2">
            <div class="col-span-2 md:col-span-1">
                <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                      <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/_next/static/media/tailwindcss-mark.3c5441fc7a190fb1800d4a5c7f07ba4b1345a9c8.svg" alt="Your Company">
                      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Daftar Akun UMKMku</h2>
                    </div>
                  
                    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                        @livewire('register')
                  
                      {{-- <p class="mt-10 text-center text-sm text-gray-500">
                        Not a member?
                        <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Start a 14 day free trial</a>
                      </p> --}}
                    </div>
                </div>
            </div>
            <div class="relative col-span-1 hidden md:block">
                <img class="h-full w-full object-cover" src="https://images.pexels.com/photos/10308137/pexels-photo-10308137.jpeg?auto=compress&cs=tinysrgb&w=600" alt="registrasi">
            </div>
        </div>
    </div>
</div>
@endsection