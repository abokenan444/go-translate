<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold tracking-tight text-gray-950 dark:text-white">
                Getting Started with Cultural Translate
            </h2>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $progress }}% Complete
            </span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-6">
            <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
        </div>

        <div class="space-y-4">
            @foreach ($steps as $step)
                <div class="flex items-start p-4 rounded-lg border {{ $step['completed'] ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-900' : 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700' }}">
                    <div class="flex-shrink-0 mr-4">
                        @if ($step['completed'])
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center dark:bg-green-900 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center dark:bg-gray-700 dark:text-gray-400">
                                <x-icon name="{{ $step['icon'] }}" class="w-5 h-5" />
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $step['label'] }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $step['description'] }}
                        </p>
                        @if (!$step['completed'])
                            <div class="mt-3">
                                <x-filament::button
                                    tag="a"
                                    href="{{ $step['action'] }}"
                                    size="sm"
                                    color="primary"
                                >
                                    {{ $step['action_label'] }}
                                </x-filament::button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
