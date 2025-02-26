<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Add a Pet') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-button icon="arrow-left" href="{{ route('dashboard') }}" wire:navigate class="mb-4 bg-gray-400 rounded-full text-3xlg hover:bg-gray-500 hover:scale-105">
                Back to Dashboard
            </x-button>
            <livewire:pets.add-pet />
        </div>
    </div>
</x-app-layout>

