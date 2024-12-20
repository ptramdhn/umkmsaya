@props(['storeSlug'])
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>UMKMku - UMKM Digital Semakin Maju!</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-full">
    <div class="min-h-full">
      <nav class="bg-gray-800 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0 py-4">
          <div class="flex items-center justify-between">
      
            <div class="flex items-center space-x-8">
              <div class="shrink-0">
                <a href="#" title="" class="">
                  <img class="w-auto h-8 block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/logo-full-dark.svg" alt="">
                </a>
              </div>
      
              <ul class="hidden lg:flex items-center justify-start gap-6 md:gap-8 py-3 sm:justify-center">
                <li>
                  <a href="{{ route('store.show', $storeSlug) }}" title="" class="flex text-sm font-medium   text-white hover:text-primary-500">
                    Home
                  </a>
                </li>
                <li class="shrink-0">
                  <a href="{{ route('produkTerbaik') }}" title="" class="flex text-sm font-medium   text-white hover:text-primary-500">
                    Produk Terbaik
                  </a>
                </li>
              </ul>
            </div>
      
            <div class="flex items-center lg:space-x-2">

              @auth
                <a href="{{ route('keranjang.show', $storeSlug) }}" class="inline-flex items-center rounded-lg justify-center p-2  hover:bg-gray-700 text-sm font-medium leading-none text-white">
                  <span class="sr-only">
                    Cart
                  </span>
                  <svg class="w-5 h-5 lg:me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
                  </svg> 
                  <span class="hidden sm:flex">Keranjang Saya</span>             
                </a>

                <button id="userDropdownButton1" data-dropdown-toggle="userDropdown1" type="button" class="inline-flex items-center rounded-lg justify-center p-2  hover:bg-gray-700 text-sm font-medium leading-none text-white">
                  <svg class="w-5 h-5 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                  </svg>              
                  {{ Auth::user()->name }}
                  <svg class="w-4 h-4 text-white ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                  </svg> 
                </button>
              @endauth

              @guest
                <a href="{{ route('masukStore', $storeSlug) }}" type="button" class="inline-flex items-center rounded-lg justify-center p-2  hover:bg-gray-700 text-sm font-medium leading-none text-white">            
                  Login
                  <svg class="w-4 h-4 text-white ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                  </svg>                  
                </a>
              @endguest    
      
              <div id="userDropdown1" class="hidden z-10 w-56 divide-y divide-gray-100 overflow-hidden overflow-y-auto rounded-lg bg-white antialiased shadow dark:divide-gray-600 dark:bg-gray-700">
                <ul class="p-2 text-start text-sm font-medium text-gray-900 dark:text-white">
                  <li><a href="{{ route('filament.admin.pages.dashboard') }}" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> Dashboard </a></li>
                  <li><a href="{{ route('daftar.toko') }}" title="" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> Buka Toko </a></li>
                </ul>
                
                <form action="{{ route('logout.custom') }}" method="post">
                  @csrf
                  <div class="p-2 text-sm font-medium text-gray-900 dark:text-white">
                    <button type="submit" class="inline-flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600"> Keluar </button>
                  </div>
                </form>

              </div>
      
              <button type="button" data-collapse-toggle="ecommerce-navbar-menu-1" aria-controls="ecommerce-navbar-menu-1" aria-expanded="false" class="inline-flex lg:hidden items-center justify-center rounded-md hover:bg-gray-700 p-2 text-white">
                <span class="sr-only">
                  Open Menu
                </span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
                </svg>                
              </button>
            </div>
          </div>
      
          <div id="ecommerce-navbar-menu-1" class="bg-gray-700 border-gray-600 border  rounded-lg py-3 hidden px-4 mt-4">
            <ul class=" text-sm font-medium text-white space-y-3">
              <li>
                <a href="{{ route('store.show', $storeSlug) }}" class="hover:text-primary-500">Home</a>
              </li>
              <li>
                <a href="{{ route('produkTerbaik') }}" class="hover:text-primary-500">Produk Terbaik</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <main>
        <div class="h-full">
          {{ $slot }}
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
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('myCartDropdownButton1').click();
      });

      document.addEventListener("DOMContentLoaded", () => {
        // Function untuk memperbarui harga produk
        function updateProductPrice(productRow) {
            const quantityInput = productRow.querySelector(".quantity-input");
            const priceElement = productRow.querySelector(".price");
            const defaultPrice = parseFloat(priceElement.dataset.defaultPrice);

            const quantity = parseInt(quantityInput.value, 10) || 1;
            const totalPrice = defaultPrice * quantity;

            // Update harga produk
            priceElement.textContent = `Rp ${totalPrice.toLocaleString("id-ID")}`;
        }

        // Function untuk memperbarui Order Summary
        function updateOrderSummary() {
            const productRows = document.querySelectorAll(".product-row");
            let originalPrice = 0;

            // Hitung total harga semua produk
            productRows.forEach((row) => {
                const quantityInput = row.querySelector(".quantity-input");
                const priceElement = row.querySelector(".price");
                const defaultPrice = parseFloat(priceElement.dataset.defaultPrice);

                const quantity = parseInt(quantityInput.value, 10) || 1;
                originalPrice += defaultPrice * quantity;
            });

            const pickupCost = 5000; // Biaya pengambilan
            const tax = originalPrice * 0.11; // Pajak tetap
            const total = originalPrice + pickupCost + tax;

            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            });

            // Update bagian order summary di DOM
            document.getElementById("original-price").textContent = formatter.format(originalPrice);
            document.getElementById("pickup-cost").textContent = formatter.format(pickupCost);
            document.getElementById("tax").textContent = formatter.format(tax);
            document.getElementById("total-cost").textContent = formatter.format(total);
        }

        // Event listener untuk tombol dan input quantity
        document.addEventListener("click", (event) => {
            const minusButton = event.target.closest(".minus-button");
            const plusButton = event.target.closest(".plus-button");

            if (minusButton || plusButton) {
                const productRow = event.target.closest(".product-row");
                const quantityInput = productRow.querySelector(".quantity-input");
                const maxStock = parseInt(productRow.dataset.stock, 10); // Ambil stok maksimum dari atribut data

                let quantity = parseInt(quantityInput.value, 10) || 1;

                if (minusButton) {
                    quantity = Math.max(1, quantity - 1); // Minimal 1
                } else if (plusButton) {
                    quantity = Math.min(maxStock, quantity + 1); // Maksimal stok
                }

                quantityInput.value = quantity;
                updateProductPrice(productRow);
                updateOrderSummary();
            }
        });

        document.addEventListener("input", (event) => {
            if (event.target.classList.contains("quantity-input")) {
                const productRow = event.target.closest(".product-row");
                const maxStock = parseInt(productRow.dataset.stock, 10); // Ambil stok maksimum dari atribut data

                // Validasi input hanya angka dan sesuai stok
                let value = event.target.value.replace(/\D/g, "").slice(0, 3);
                if (value === "") value = "1"; // Default ke 1 jika kosong

                // Batasi nilai input ke stok maksimum
                value = Math.min(maxStock, Math.max(1, parseInt(value, 10) || 1));

                event.target.value = value;

                updateProductPrice(productRow);
                updateOrderSummary();
            }
        });

        // Initial calculation saat halaman dimuat
        updateOrderSummary();
      });



    </script>
</body>
</html>