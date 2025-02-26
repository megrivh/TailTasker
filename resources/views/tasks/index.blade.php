<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Today\'s Tasks') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <div class="flex justify-end mb-8">
                    <x-button href="{{ route('tasks.add') }}" wire:navigate class="transition duration-200 transform rounded-full text-3xlg hover:bg-green-600 hover:scale-105">
                        Add a Task
                    </x-button>
                </div>
                <livewire:tasks.show-all-tasks />
            </div>
        </div>
    </div>
</x-app-layout>
