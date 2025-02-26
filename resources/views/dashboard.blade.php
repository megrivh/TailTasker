<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <div class="flex justify-end mb-8">
                    <x-button href="{{ route('pets.add') }}" class="transition duration-200 transform rounded-full text-3xlg hover:bg-green-600 hover:scale-105">
                        Add a Pet
                    </x-button>
                </div>
                <livewire:pets.show-pets />
            </div>
        </div>
    </div>
</x-app-layout>
