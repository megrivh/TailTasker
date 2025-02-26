<?php

use Livewire\Volt\Component;

new class extends Component {

    protected $listeners = ['petAdded' => 'refreshPets'];

    public function mount() {
        $this->refreshPets();
    }

    public function with(): array
    {
        return [
            'pets' => Auth::user()
                ->pets()
                ->get(),
        ];
    }

    public function refreshPets() {
        $this->pets = Auth::user()
            ->pets()
            ->get();
    }
}; ?>

<div>
    @if($pets->isEmpty())
        <div class="text-center text-gray-800">
            <p class="text-xl font-bold">No pets here!</p>
            <p class="text-sm">To get started, add a pet (or two...or six...)</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
            @foreach($pets as $pet)
                <x-card wire:key='{{ $pet->id }}' class="p-4 bg-yellow-200 border rounded-lg shadow-md">
                    <div class="flex flex-col items-center justify-center">

                        <!-- Display Pet Photo -->
                        <div class="mb-4">
                            @if($pet->pet_photo)
                                <img src="{{ asset('storage/' . $pet->pet_photo) }}" alt="Pet Photo" class="object-cover w-32 h-32 rounded-full">
                            @else
                                <img src="{{ asset('images/default-pet.png') }}" alt="Default Pet Photo" class="object-cover w-32 h-32 rounded-full">
                            @endif
                        </div>
                    </div>

                    <!-- Display Pet Name and Age -->
                    <div class="text-center">
                        <a href='#' class="text-xl font-bold text-black hover:underline hover:text-green-600">{{ $pet->name }}</a>
                    </div>
                    <div class="mt-2 text-center text-gray-800">
                        <p><em><strong>Age:</strong> {{ $pet->age }} {{ $pet->age_unit }}</em></p>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
</div>
