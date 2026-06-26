<?php

use App\Models\Task;
use Livewire\Volt\Component;

new class extends Component {
    public string $title = '';

    public function addTask(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create(['title' => $this->title, 'completed' => false]);

        $this->reset('title');
    }

    public function toggle(int $id): void
    {
        $task = Task::findOrFail($id);
        $task->update(['completed' => ! $task->completed]);
    }

    public function with(): array
    {
        return [
            'tasks' => Task::latest()->get(),
        ];
    }
}; ?>

<div class="space-y-6">

    {{-- Add Task Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-xl font-semibold text-slate-800 mb-4">My Tasks</h1>

        <form wire:submit.prevent="addTask" class="flex gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    wire:model="title"
                    placeholder="What needs to be done?"
                    class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
                @error('title')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add
            </button>
        </form>
    </div>

    {{-- Task List Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @if ($tasks->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <svg class="w-10 h-10 mb-3 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm font-medium">No tasks yet</p>
                <p class="text-xs mt-1">Add one above to get started.</p>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($tasks as $task)
                    <li class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50 transition group">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Status dot --}}
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $task->completed ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                            <span class="text-sm text-slate-800 truncate {{ $task->completed ? 'line-through text-slate-400' : '' }}">
                                {{ $task->title }}
                            </span>
                        </div>
                        <button
                            wire:click="toggle({{ $task->id }})"
                            class="ml-4 flex-shrink-0 text-xs font-medium px-3 py-1.5 rounded-full transition focus:outline-none focus:ring-2 focus:ring-offset-1
                                {{ $task->completed
                                    ? 'bg-slate-100 text-slate-500 hover:bg-slate-200 focus:ring-slate-400'
                                    : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 focus:ring-emerald-400' }}"
                        >
                            {{ $task->completed ? 'Undo' : 'Complete' }}
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Footer count --}}
            <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex justify-between text-xs text-slate-400">
                <span>{{ $tasks->where('completed', false)->count() }} remaining</span>
                <span>{{ $tasks->where('completed', true)->count() }} completed</span>
            </div>
        @endif
    </div>

</div>
