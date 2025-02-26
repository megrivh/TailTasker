<?php

use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public $tasksCompleted = [];
    public $currentDate;
    public $pets = [];

    protected $listeners = ['taskAdded' => 'refreshTasks'];

    public function mount() {
        $this->currentDate = Carbon::today();
        $this->refreshTasks();
    }

    public function with(): array
    {
        return [
            'pets' => $this->pets,
        ];
    }

    public function refreshTasks() {
        $this->pets = Auth::user()
            ->pets()
            ->with(['tasks' => function ($query) {
                $query->where(function ($query) {
                    $query->whereDate('task_due_date', $this->currentDate)
                          ->orWhere(function ($query) {
                              $query->where('is_recurring', true)
                                    ->where(function ($query) {
                                        $query->where('recurring_frequency', 'daily')
                                              ->orWhere(function ($query) {
                                                  $query->where('recurring_frequency', 'weekly')
                                                        ->whereRaw('strftime("%w", task_due_date) = ?', [Carbon::parse($this->currentDate)->dayOfWeek]);
                                              })
                                              ->orWhere(function ($query) {
                                                  $query->where('recurring_frequency', 'monthly')
                                                        ->whereRaw('strftime("%d", task_due_date) = ?', [Carbon::parse($this->currentDate)->day]);
                                              })
                                              ->orWhere(function ($query) {
                                                  $query->where('recurring_frequency', 'annually')
                                                        ->whereRaw('strftime("%m-%d", task_due_date) = ?', [Carbon::parse($this->currentDate)->format('m-d')]);
                                              });
                                    });
                          })
                          ->orderBy("completed");
                });
            }])
            ->get();
    }

    public function previousDay() {
        $this->currentDate = Carbon::parse($this->currentDate)->subDay();
        $this->refreshTasks();
    }

    public function nextDay() {
        $this->currentDate = Carbon::parse($this->currentDate)->addDay();
        $this->refreshTasks();
    }

    public function updateTasksCompleted($value, $taskID) {
        $task = Task::find($taskID);

        if($task) {
            $task->is_completed = $value ? 1 : 0;
            $task->save();
        }

        $this->refreshTasks();

    }

    public function goToDate($date) {
        $this->currentDate = Carbon::parse($date);
        $this->refreshTasks();
    }
};

?>

<div>
    <div class="flex items-center justify-between mb-4">
        <button wire:click="previousDay" class="px-4 py-2 transition duration-200 transform bg-gray-200 rounded-full text-3xlg hover:bg-gray-400 hover:scale-105">Yesterday</button>
        <h2 class="text-xl font-bold">{{ $currentDate->format('F j, Y') }}</h2>
        <button wire:click="nextDay" class="px-4 py-2 transition duration-200 transform bg-gray-200 rounded-full text-3xlg hover:bg-gray-400 hover:scale-105">Tomorrow</button>
    </div>
    <div class="space-y-2">
        @if($pets->isEmpty() || $pets->every(fn($pet) => $pet->tasks->isEmpty()))
            <div class="text-center">
                <p class="text-xl font-bold">You haven't added any tasks for today!</p>
                <p class="text-sm">Start managing your pet's day by adding a task.</p>
            </div>
        @else
            @foreach($pets as $pet)
                @foreach($pet->tasks as $task)
                    <div class="flex items-center mb-2 space-x-4">
                        <input type="checkbox" wire:click="updateTasksCompleted({{ $task->is_completed ? 0 : 1 }}, {{$task->id}})" class="w-5 h-5 text-green-500 form-checkbox" {{$task->is_completed ? 'checked' : ''}}>
                        <p class="text-lg {{ $task->completed ? 'text-gray-500 line-through' : 'text-gray-800' }}">
                            <a href='#' class="text-xl font-bold hover:underline hover:text-green-600">{{ $task->task_name }}</a><span class="text-sm text-gray-500"> ({{ $pet->name }})</span>
                        </p>
                    </div>
                @endforeach
            @endforeach
        @endif
    </div>
</div>
