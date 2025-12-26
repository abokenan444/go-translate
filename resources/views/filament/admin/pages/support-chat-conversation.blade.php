<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- معلومات الجلسة --}}
        <div class="lg:col-span-1 space-y-4">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-information-circle class="w-5 h-5" />
                        معلومات الجلسة
                    </div>
                </x-slot>
                
                <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رقم الجلسة</dt>
                        <dd class="text-sm text-gray-900 dark:text-white font-mono">{{ $record->session_id }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الحالة</dt>
                        <dd>
                            @if($record->status === 'waiting')
                                <x-filament::badge color="warning">
                                    <x-heroicon-s-clock class="w-3 h-3 mr-1" />
                                    في الانتظار
                                </x-filament::badge>
                            @elseif($record->status === 'active')
                                <x-filament::badge color="success">
                                    <x-heroicon-s-chat-bubble-left-right class="w-3 h-3 mr-1" />
                                    نشطة
                                </x-filament::badge>
                            @else
                                <x-filament::badge color="gray">
                                    <x-heroicon-s-lock-closed class="w-3 h-3 mr-1" />
                                    مغلقة
                                </x-filament::badge>
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">القسم</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">
                            @switch($record->department)
                                @case('general') عام @break
                                @case('technical') تقني @break
                                @case('billing') الفواتير @break
                                @case('translation') الترجمة @break
                                @default {{ $record->department }}
                            @endswitch
                        </dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">تاريخ البدء</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $record->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    @if($record->agent)
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">موظف الدعم</dt>
                            <dd class="text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-primary-500 text-white flex items-center justify-center text-xs">
                                    {{ substr($record->agent->name, 0, 1) }}
                                </div>
                                {{ $record->agent->name }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-user class="w-5 h-5" />
                        معلومات العميل
                    </div>
                </x-slot>
                
                <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                    @if($record->user)
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الاسم</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $record->user->name }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">البريد</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $record->user->email }}</dd>
                        </div>
                    @else
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الاسم</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $record->visitor_name ?? 'زائر' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">البريد</dt>
                            <dd class="text-sm text-gray-900 dark:text-white">{{ $record->visitor_email ?? '-' }}</dd>
                        </div>
                    @endif
                </dl>
            </x-filament::section>

            @if($record->rating)
                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-star class="w-5 h-5" />
                            التقييم
                        </div>
                    </x-slot>
                    
                    <div class="text-center">
                        <div class="text-3xl text-yellow-500">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $record->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $record->rating }}/5</p>
                        @if($record->feedback)
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">{{ $record->feedback }}</p>
                        @endif
                    </div>
                </x-filament::section>
            @endif
        </div>

        {{-- نافذة المحادثة --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                            المحادثة
                        </div>
                        <div class="text-xs text-gray-500" wire:poll.5s="loadMessages">
                            آخر تحديث: {{ now()->format('H:i:s') }}
                        </div>
                    </div>
                </x-slot>
                
                <div class="h-[500px] overflow-y-auto bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-4" id="chat-messages">
                    @forelse($messages as $message)
                        <div class="mb-4 {{ $message['sender_type'] === 'agent' ? 'text-right' : ($message['sender_type'] === 'system' ? 'text-center' : 'text-left') }}">
                            @if($message['sender_type'] === 'system')
                                <div class="inline-block px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm">
                                    <x-heroicon-s-information-circle class="w-4 h-4 inline mr-1" />
                                    {{ $message['message'] }}
                                </div>
                            @else
                                <div class="inline-block max-w-[80%] px-4 py-3 rounded-2xl {{ $message['sender_type'] === 'agent' ? 'bg-primary-500 text-white rounded-br-md' : 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-bl-md' }}">
                                    <p class="whitespace-pre-wrap">{{ $message['message'] }}</p>
                                    <p class="text-xs {{ $message['sender_type'] === 'agent' ? 'text-primary-100' : 'text-gray-500 dark:text-gray-400' }} mt-2 flex items-center gap-1 {{ $message['sender_type'] === 'agent' ? 'justify-end' : 'justify-start' }}">
                                        <x-heroicon-s-clock class="w-3 h-3" />
                                        {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                                        @if($message['sender_type'] === 'agent' && isset($message['sender']))
                                            - {{ $message['sender']['name'] ?? '' }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <x-heroicon-o-chat-bubble-oval-left-ellipsis class="w-12 h-12 mx-auto mb-3 opacity-50" />
                            <p>لا توجد رسائل بعد</p>
                        </div>
                    @endforelse
                </div>

                @if($record->status !== 'closed')
                    <form wire:submit="sendReply" class="flex gap-2">
                        <x-filament::input.wrapper class="flex-1">
                            <x-filament::input
                                type="text"
                                wire:model="replyMessage"
                                placeholder="اكتب ردك هنا..."
                                autofocus
                            />
                        </x-filament::input.wrapper>
                        <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                            إرسال
                        </x-filament::button>
                    </form>
                @else
                    <div class="text-center py-4 text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <x-heroicon-o-lock-closed class="w-6 h-6 mx-auto mb-2" />
                        تم إغلاق هذه المحادثة
                    </div>
                @endif
            </x-filament::section>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom
        function scrollToBottom() {
            const chatMessages = document.getElementById('chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
        
        document.addEventListener('DOMContentLoaded', scrollToBottom);
        document.addEventListener('livewire:navigated', scrollToBottom);
        
        // Scroll after Livewire updates
        Livewire.hook('morph.updated', () => {
            scrollToBottom();
        });
    </script>
</x-filament-panels::page>
