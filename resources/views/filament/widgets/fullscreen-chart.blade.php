<x-filament::widget>
    <x-filament::card>
        <div class="h-full w-full">
            {!! $this->chart->container() !!}
        </div>
    </x-filament::card>

    @push('scripts')
        {!! $this->chart->script() !!}
    @endpush
</x-filament::widget>
