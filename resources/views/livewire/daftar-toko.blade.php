<div>
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
                          <form wire:submit.prevent="save">
                              {{ $this->form }}
                              <button type="submit" class="mt-8 flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Daftar</button>
                          </form>
                      </div>
                    </div>
              </div>
              <div class="relative col-span-1 hidden md:block">
                  <img class="h-full w-full object-cover" src="{{ asset('storage/images/bukaToko.jpg') }}" alt="registrasi">
              </div>
          </div>
      </div>
    </div>
  </div>
  