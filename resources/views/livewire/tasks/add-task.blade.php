<?php

use Livewire\Volt\Component;

new class extends Component {
    public $taskName;
    public $assignedTo = [];
    public $taskDescription;
    public $taskDueDate;
    public $isRecurring = false;
    public $recurringFrequency;
    public $isReminderRequested = false;
    public $pets = [];

    public function mount() {
        $this->pets = Auth::user()
            ->pets()
            ->get();
    }

    public function submit() {
        $validated = $this->validate([
            'taskName' => ['required', 'string', 'min:3', 'max:100'],
            'assignedTo' => ['required', 'array', 'min:1'],
            'taskDescription' => ['required', 'string', 'min:3', 'max:255'],
            'taskDueDate' => ['required', 'date'],
            'isRecurring' => ['boolean'],
            'recurringFrequency' => ['nullable', 'string'],
            'isReminderRequested' => ['boolean'],
        ]);

        foreach ($this->assignedTo as $petID) {
            $pet = auth()->user()->pets()->find($petID);
            $task = $pet->tasks()->create([
                'task_name' => $this->taskName,
                'task_description' => $this->taskDescription,
                'task_due_date' => $this->taskDueDate,
                'is_completed' => false,
                'is_recurring' => $this->isRecurring,
                'recurring_frequency' => $this->recurringFrequency,
                'is_reminder_needed' => $this->isReminderRequested,
                'is_reminder_sent' => false,
                'pet_id' => $petID,
            ]);
        }

        return redirect()->route('tasks.index');
    }
}; ?>

<div>
    <form wire:submit='submit' class="space-y-4">
        <!-- Task Name -->
        <x-input wire:model="taskName" label="Task Name" placeholder="Go for a ride"/>

        <!-- Task Assigned To (Multiple Pets) -->
        <div class="mb-4">
            <label for="assignedTo" class="block text-sm font-medium text-gray-700">Assign Task To</label>
            <select wire:model="assignedTo" id="assignedTo" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm" multiple>
                @foreach($pets as $pet)
                    <option value="{{ $pet->id }}">{{ $pet->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Task Description -->
        <x-textarea wire:model="taskDescription" label="Task Description" placeholder="Take your pet for a ride around the neighborhood"/>

        <!-- Due Date -->
        <x-input icon="calendar" wire:model="taskDueDate" label="Due Date" type="date" />

        <!-- Recurring Task Checkbox -->
        <div class="flex items-center mb-4 space-x-4">
            <input wire:model="isRecurring" type="checkbox" class="w-5 h-5 text-green-500 form-checkbox">
            <label for="isRecurring" class="text-sm font-medium text-gray-700">Repeat task</label>
        </div>

        <!-- Recurring Frequency Dropdown-->
        <div class="mb-4" x-show="isRecurring" x-data="{ isRecurring: @entangle('isRecurring') }">
            <label for="recurringFrequency" class="block text-sm font-medium text-gray-700">How often should this task repeat?</label>
            <select wire:model="recurringFrequency" id="recurringFrequency" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-600 focus:border-green-600 sm:text-sm" :disabled="!isRecurring">
                <option value="">Select Frequency</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="annually">Annually</option>
            </select>
        </div>

        <!-- Reminder Request Checkbox -->
        <div class="flex items-center mb-4 space-x-4">
            <input wire:model="isReminderRequested" type="checkbox" class="w-5 h-5 text-green-500 form-checkbox">
            <label for="isReminderRequested" class="text-sm font-medium text-gray-700">Send me a reminder</label>
        </div>

        <!-- Submit Button -->
        <x-button wire:click="submit" class="mt-5 transition duration-200 transform rounded-full text-3xlg hover:bg-green-600 hover:scale-105" spinner>
            Add Task
        </x-button>
        <x-errors />
    </form>
</div>
