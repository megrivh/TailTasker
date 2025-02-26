<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $petName;
    public $petType;
    public $petBreed;
    public $petAge;
    public $petAgeUnit;
    public $petPhoto;

    public function submit() {
        $validated = $this->validate([
            'petName' => ['required', 'string', 'min:3', 'max:50'],
            'petType' => ['required', 'string'],
            'petBreed' => ['required', 'string', 'min:3', 'max:100'],
            'petAge' => ['required', 'numeric'],
            'petAgeUnit' => ['required', 'string'],
            'petPhoto' => ['nullable', 'image', 'max:1024'],
        ]);

        $petPhotoPath = null;
        if ($this->petPhoto) {
            $petPhotoPath = $this->petPhoto->store('pet_photos', 'public');
        }

        auth()->user()->pets()->create([
            'name' => $this->petName,
            'pet_type' => $this->petType,
            'breed' => $this->petBreed,
            'age' => $this->petAge,
            'age_unit' => $this->petAgeUnit,
            'pet_photo' => $petPhotoPath,
        ]);

        return redirect()->route('dashboard');
    }
}; ?>

<div>
    <form wire:submit='submit' class="space-y-4">
        <!-- Pet Name -->
        <x-input wire:model="petName" label="Name" placeholder="Enter your pet's name"/>

        <!-- Pet Type Dropdown -->
        <div class="mb-4">
            <label for="petType" class="block text-sm font-medium text-gray-700">Type</label>
            <select wire:model="petType" id="petType" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm">
                <option value="">Select a Pet Type</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="bird">Bird</option>
                <option value="fish">Fish</option>
                <option value="reptile">Reptile</option>
                <option value="other">Other</option>
            </select>
        </div>

        <!-- Pet Breed -->
        <x-input wire:model="petBreed" label="Breed" placeholder="Enter your pet's breed"/>

        <!-- Pet Age and Unit-->
        <div class="flex mb-4 space-x-4">
            <div class="flex-1">
                <x-input wire:model="petAge" label="Age" placeholder="Enter your pet's age"/>
            </div>
            <div class="flex-1">
                <label for="petAgeUnit" class="block text-sm font-medium text-gray-700">Unit</label>
                <select wire:model="petAgeUnit" id="petAgeUnit" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm">
                    <option value="">Select Age Unit</option>
                    <option value="years">Years</option>
                    <option value="months">Months</option>
                </select>
            </div>
        </div>

        <!-- Pet Photo (Optional) -->
        <div class="mb-4">
            <label for="petPhoto" class="block text-sm font-medium text-gray-700">Pet Photo (Optional)</label>
            <input type="file" wire:model="petPhoto" id="petPhoto" class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-yellow-100 file:text-gray-600 hover:file:bg-yellow-300"/>
        </div>

        <!-- Submit Button -->
        <x-button type="submit" class="mt-5 transition duration-200 transform rounded-full text-3xlg hover:bg-green-600 hover:scale-105" spinner>
            Add Pet
        </x-button>
        <x-errors />
    </form>
</div>
