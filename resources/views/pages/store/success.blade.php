<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UMKMku - UMKM Digital Semakin Maju!</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-full">
    <div class="min-h-full">
      <main>
        <div class="h-full">
            <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div class="mx-auto max-w-2xl px-4 2xl:px-0">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-2">Thanks for your order!</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">Your order <a href="#" class="font-medium text-gray-900 dark:text-white hover:underline">{{ $transaction->code }}</a> will be processed within 24 hours during working days. We will notify you by email once your order has been shipped.</p>
                    <div class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800 mb-6 md:mb-8">
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Date</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ \Carbon\Carbon::parse($transaction->created_at)->isoFormat('D MMMM YYYY') }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Payment Status</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $transaction->payment_status }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $transaction->user->name }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">{{ $transaction->user->email }}</dd>
                        </dl>
                        <dl class="sm:flex items-center justify-between gap-4">
                            <dt class="font-normal mb-1 sm:mb-0 text-gray-500 dark:text-gray-400">Phone</dt>
                            <dd class="font-medium text-gray-900 dark:text-white sm:text-end">+{{ $transaction->user->phone_number }}</dd>
                        </dl>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('filament.admin.resources.transactions.index') }}" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Track your order</a>
                        <a href="{{ route('store.show', $transaction->store->slug) }}" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Return to shopping</a>
                    </div>
                </div>
            </section>
        </div>
      </main>
      <footer class="bg-gray-800 grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 m-4 mt-auto">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <a href="https://flowbite.com/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">UMKMku</span>
                </a>
                <span class="block text-sm sm:text-center text-gray-100">© 2024 <a href="https://flowbite.com/" class="hover:underline">UMKMku™</a>. All Rights Reserved.</span>
            </div>
        </div>
      </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>