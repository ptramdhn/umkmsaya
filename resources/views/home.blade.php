@extends('layouts.layout')

@section('content')
@if(session('status') && session('message'))
  <script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({
        icon: "{{ session('status') }}",
        title: "{{ session('message') }}"
    });
  </script>
@endif

@if (session('message') == 'Pendafataran Toko Berhasil')
    <script>
        Swal.fire({
        title: "Verifikasi Diperlukan!",
        text: "Toko Anda berhasil didaftarkan. Namun, Anda perlu memverifikasi nomor WhatsApp yang Anda masukkan saat pendaftaran untuk mulai menerima notifikasi pesanan.",
        icon: "warning",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Verifikasi Sekarang",
        allowOutsideClick: false, // Cegah klik di luar dialog
        allowEscapeKey: false,   // Cegah penutupan dengan tombol ESC
        allowEnterKey: false     // Cegah penutupan dengan tombol Enter
        }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan pesan loading sebelum membuka window baru
            Swal.fire({
            title: "Memproses...",
            text: "Silakan tunggu sebentar.",
            icon: "info",
            showConfirmButton: false, // Hilangkan tombol konfirmasi
            allowOutsideClick: false, // Cegah klik di luar
            timer: 2000 // Durasi loading sebelum menutup, dalam milidetik
            }).then(() => {
            // Coba membuka tab baru
            const newWindow = window.open("https://wa.me/14155238886?text=join%20city-rather", "_blank");

            // Cek apakah window berhasil dibuka
            if (newWindow) {
                newWindow.focus();
            } else {
                // Jika gagal membuka tab baru, tampilkan alert dengan tombol alternatif
                Swal.fire({
                title: "Gagal Membuka Halaman",
                text: "Browser Anda mencegah untuk membuka tab baru. Silakan klik tombol di bawah untuk melanjutkan verifikasi.",
                icon: "error",
                showCancelButton: false,
                allowOutsideClick: false, // Cegah klik di luar dialog
                allowEscapeKey: false,   // Cegah penutupan dengan tombol ESC
                allowEnterKey: false,     // Cegah penutupan dengan tombol Enter
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Verifikasi Sekarang",
                }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik "Verifikasi Sekarang", arahkan ke halaman verifikasi
                    window.location.href = "https://wa.me/14155238886?text=join%20city-rather";
                }
                });
            }
            });
        }
        });
    </script>
@endif

<section class="bg-white">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl text-black">Jadikan Bisnis Anda Go Digital!</h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl">Mudah Buat Website, Praktis Kelola Usaha, Sukseskan UMKM Anda Sekarang!</p>
            
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                        Dashboard Admin
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @elseif (auth()->user()->store)
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                        Dashboard Toko
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @else
                    <a href="{{ route('daftar.toko') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900" type="button">
                        Buka Toko!
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @endif
            @endauth

            @guest
                <a href="{{ route('daftar') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                    Mulai Sekarang!
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            @endguest   
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex justify-center items-center">
            <img 
                src="{{ asset('storage/images/DALL·E 2024-11-24 01.42.45 - A realistic and modern illustration showing a person using a smartphone to manage their business from different locations. The smartphone screen displ.webp') }}" 
                alt="mockup"
                class="rounded-xl shadow-lg transform hover:scale-105 transition duration-500 ease-in-out max-w-md w-full"
            >
        </div>                             
    </div>
</section>

<section class="bg-white dark:bg-gray-900">
    <div class="gap-8 items-center py-8 px-4 mx-auto max-w-screen-xl xl:gap-16 md:grid md:grid-cols-2 sm:py-16 lg:px-6">
        <div class="flex justify-center items-center">
            <img 
                class="rounded-lg shadow-md transform hover:scale-105 transition duration-300 ease-in-out max-w-md w-full" 
                src="{{ asset('storage/images/DALL·E 2024-11-24 01.56.33 - A simple and modern digital illustration showing a smartphone with a clean business management interface. The screen displays essential features such .webp') }}" 
                alt="dashboard image"
            >
        </div>        
        <div class="mt-4 md:mt-0">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Kelola Penjualan dengan Lebih Mudah dan Efisien</h2>
            <p class="mb-6 font-light text-gray-500 md:text-lg">Maksimalkan Bisnis Anda dengan Fitur Digital Terintegrasi: Atur Produk, Pantau Transaksi, dan Tingkatkan Penjualan dalam Satu Platform!
            </p>
            
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                        Dashboard Admin
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @elseif (auth()->user()->store)
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                        Dashboard Toko
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @else
                    <a href="{{ route('daftar.toko') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900" type="button">
                        Buka Toko!
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                @endif
            @endauth

            @guest
                <a href="{{ route('daftar') }}" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-900">
                    Mulai Sekarang!
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            @endguest

        </div>
    </div>
</section>

<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
        <div class="max-w-screen-md mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Diciptakan untuk mempermudah dan membantu UMKM</h2>
            <p class="text-gray-500 sm:text-xl dark:text-gray-400">Memiliki beberapa fitur untuk mempermudah data bisnis online kalian.</p>
        </div>
        <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
            <div>
                <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                    <svg class="w-5 h-5 text-primary-600 lg:w-6 lg:h-6 dark:text-primary-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold dark:text-white">Dashboard</h3>
                <p class="text-gray-500 dark:text-gray-400">Bisa langsung melihat data penjualan bisnis kamu secara real time tanpa ribet dan cepat.</p>
            </div>
            <div>
                <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                    <svg class="w-5 h-5 text-primary-600 lg:w-6 lg:h-6 dark:text-primary-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold dark:text-white">Tampilan yang menarik</h3>
                <p class="text-gray-500 dark:text-gray-400">Tampilan website bisnis online kamu akan dibuat dengan sederhana namun tetap menarik.</p>
            </div>
            <div>
                <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                    <svg class="w-5 h-5 text-primary-600 lg:w-6 lg:h-6 dark:text-primary-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="mb-2 text-xl font-bold dark:text-white">Biaya yang murah</h3>
                <p class="text-gray-500 dark:text-gray-400">Membuat website bisnis digital kamu disini tidak akan dikenakan biaya dalam pembuatan, hanya saja setiap transaksi yang terjadi dalam bisnis kamu akan dikenakan biaya operasional yang murah.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="py-8 lg:py-16 mx-auto max-w-screen-xl px-4">
        <h2 class="mb-16 lg:mb-16 text-3xl font-extrabold tracking-tight leading-tight text-center text-gray-900 md:text-4xl">
            Dibuat dengan bahasa pemrograman
        </h2>
        <div class="grid grid-cols-2 gap-8 text-gray-500 sm:gap-12 md:grid-cols-3 lg:grid-cols-6 dark:text-gray-400">
            <a href="#" class="flex justify-center items-center">
                <img class="max-h-12 object-contain" src="https://laravel.com/img/logotype.min.svg" alt="Laravel">                     
            </a>
            <a href="#" class="flex justify-center items-center">
                <img class="max-h-12 object-contain" src="https://tailwindcss.com/_next/static/media/tailwindcss-logotype.0e3166482a69f6e0f869a048cf5c06bb695e2577.svg" alt="Tailwindcss">                                           
            </a>
            <a href="#" class="flex justify-center items-center">
                <img class="max-h-12 object-contain" src="https://alpinejs.dev/alpine_long.svg" alt="Alpinejs">
            </a>
            <a href="#" class="flex justify-center items-center">
                <img class="max-h-12 object-contain" src="https://sweetalert2.github.io/images/SweetAlert2.png" alt="SweetAlert">
            </a>
            {{-- <a href="#" class="flex justify-center items-center">
                <img class="max-h-12 object-contain" src="https://testrigor.com/wp-content/uploads/2023/03/vuejs-logo.png" alt="VueJS">
            </a> --}}
        </div>
    </div>
</section>

<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center items-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Tim Developer</h2>
            <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Mari berkenalan dengan developer platform yang keren ini dan developernya yang tak kalah keren</p>
        </div> 
        <div class="grid gap-8 lg:gap-16 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <div class="text-center text-gray-500 dark:text-gray-400">
                <img class="mx-auto mb-4 w-36 h-36 rounded-full" src="{{ asset('storage/images/aditya.jpg') }}" alt="Bonnie Avatar">
                <h3 class="mb-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <a href="#">Aditya Putra Ramadhan</a>
                </h3>
                <p>Developer</p>
            </div>
            <div class="text-center text-gray-500 dark:text-gray-400">
                <img class="mx-auto mb-4 w-36 h-36 rounded-full" src="{{ asset('storage/images/latif.jpg') }}" alt="Helene Avatar">
                <h3 class="mb-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <a href="#">Muhammad Abdul Latif Samsudin</a>
                </h3>
                <p>Developer</p>
        </div>  
    </div>
</section>

@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById('defaultModalButton').click();
    });
</script>
@endsection