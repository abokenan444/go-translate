<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Developer System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950">
<div class="min-h-screen bg-slate-950 text-slate-50 py-8">
    <div class="max-w-6xl mx-auto px-4 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold">AI Developer System</h1>
                <p class="text-sm text-slate-400">
                    مساعد تطوير ذكي متكامل مع المشروع – مع معاينة قبل التنفيذ، وإصلاحات، ومراقبة صحة، وأدوات نشر.
                </p>
            </div>
            <div class="text-xs text-slate-400">
                وضع العمل:
                <span class="inline-flex items-center rounded-full bg-slate-800 px-2 py-1">
                    {{ strtoupper(config('ai_developer.mode')) }}
                </span>
            </div>
        </div>

        {{-- الرسائل --}}
        @if (session('success'))
            <div class="rounded-lg border border-emerald-500/40 bg-emerald-950/40 px-4 py-3 text-sm text-emerald-100">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg border border-red-500/40 bg-red-950/40 px-4 py-3 text-sm text-red-100">
                {{ session('error') }}
            </div>
        @endif

        {{-- صف علوي: الشات + أدوات النشر --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- الشات --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur p-4 space-y-4">
                    <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 text-xs">
                            AI
                        </span>
                        محادثة التطوير الذكية
                    </h2>

                    @if (session('ai_analysis'))
                        <div class="rounded-xl border border-slate-700 bg-slate-900/80 p-3 text-sm text-slate-200 max-h-40 overflow-y-auto">
                            <div class="text-xs text-slate-400 mb-1">تحليل آخر طلب:</div>
                            <pre class="whitespace-pre-wrap font-mono text-[11px] leading-relaxed">{{ session('ai_analysis') }}</pre>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('ai-developer.chat') }}" class="space-y-3">
                        @csrf
                        <label class="block text-xs text-slate-400">
                            اكتب ما تريد من المساعد (مثال: أضف API لتحديث خطة الاشتراك، أو أصلح خطأ 500 في /api/translate)
                        </label>
                        <textarea
                            name="message"
                            class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-3 py-2 text-sm text-slate-50 focus:border-emerald-500 focus:ring-emerald-500/40 min-h-[120px]"
                            placeholder="مثال: حلل لي مشكلة صفحة /billing في Next.js مع Laravel API للخطط..."
                        >{{ old('message') }}</textarea>

                        <div class="flex items-center justify-between">
                            <p class="text-[11px] text-slate-500">
                                لن يتم تنفيذ أي شيء مباشرة – كل شيء يذهب إلى قائمة تغييرات للمراجعة اليدوية.
                            </p>
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-xs font-medium text-slate-950 hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/60"
                            >
                                إرسال إلى المساعد
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- أدوات النشر + الصحة --}}
            <div class="space-y-4">
                {{-- Health --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur p-4 space-y-3">
                    <h2 class="text-sm font-semibold text-slate-200">مراقبة الصحة (Health)</h2>
                    <p class="text-[11px] text-slate-400">
                        نقاط فحص بسيطة لمراقبة جاهزية النظام.
                    </p>
                    <ul class="space-y-1 text-xs text-slate-300">
                        <li>
                            <span class="font-mono text-[11px] text-emerald-400">GET /health</span>
                            <span class="text-slate-500"> – حالة عامة (200 = OK)</span>
                        </li>
                        <li>
                            <span class="font-mono text-[11px] text-emerald-400">GET /api/health</span>
                            <span class="text-slate-500"> – JSON مفصل (App + DB)</span>
                        </li>
                    </ul>
                </div>

                {{-- Deploy --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur p-4 space-y-3">
                    <h2 class="text-sm font-semibold text-slate-200">أدوات النشر السريعة (Deploy)</h2>
                    <p class="text-[11px] text-slate-400">
                        أوامر آمنة يتم تنفيذها من الخادم بضغطة زر.
                    </p>

                    <form method="POST" action="{{ route('ai-developer.deploy') }}" class="space-y-2">
                        @csrf
                        <select
                            name="action"
                            class="w-full rounded-lg border border-slate-700 bg-slate-950 px-2 py-1.5 text-xs text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/40"
                        >
                            <option value="clear_cache">مسح الكاش (optimize:clear)</option>
                            <option value="config_cache">تجميع الإعدادات (config:cache)</option>
                            <option value="migrate">تشغيل المايجريشن (migrate --force)</option>
                            <option value="queue_restart">إعادة تشغيل الـ Queue</option>
                        </select>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-sky-500 px-3 py-1.5 text-xs font-medium text-slate-950 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/60"
                        >
                            تنفيذ أمر النشر
                        </button>
                    </form>

                    @if (session('command_output') || session('command_error'))
                        <div class="mt-3 rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 max-h-40 overflow-y-auto">
                            <div class="text-[11px] text-slate-400 mb-1">نتيجة آخر أمر:</div>
                            @if (session('command_output'))
                                <pre class="text-[10px] text-emerald-300 whitespace-pre-wrap mb-1">{{ session('command_output') }}</pre>
                            @endif
                            @if (session('command_error'))
                                <pre class="text-[10px] text-red-300 whitespace-pre-wrap">{{ session('command_error') }}</pre>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- قائمة التغييرات المقترحة --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur p-4 space-y-4">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-200">التغييرات المقترحة من المساعد (Pending / Applied)</h2>
                <span class="text-[11px] text-slate-500">
                    يظهر آخر 50 تغييراً فقط
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-slate-900/90">
                        <tr class="text-slate-400 text-[11px]">
                            <th class="px-2 py-2 text-right">#</th>
                            <th class="px-2 py-2 text-right">النوع</th>
                            <th class="px-2 py-2 text-right">الوصف</th>
                            <th class="px-2 py-2 text-right">المسار / الأمر</th>
                            <th class="px-2 py-2 text-right">الحالة</th>
                            <th class="px-2 py-2 text-right">تحكم</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80">
                        @forelse ($changes as $change)
                            <tr>
                                <td class="px-2 py-2 align-top text-slate-400">
                                    {{ $change->id }}
                                </td>
                                <td class="px-2 py-2 align-top">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px]
                                        @class([
                                            'bg-sky-500/10 text-sky-300' => $change->type === 'file_edit',
                                            'bg-amber-500/10 text-amber-300' => $change->type === 'command',
                                            'bg-fuchsia-500/10 text-fuchsia-300' => $change->type === 'sql',
                                        ])
                                    ">
                                        {{ $change->type }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 align-top text-slate-200 max-w-xs">
                                    <div class="line-clamp-3">
                                        {{ $change->meta['description'] ?? 'بدون وصف' }}
                                    </div>
                                </td>
                                <td class="px-2 py-2 align-top text-[11px] text-slate-300 max-w-xs">
                                    @if ($change->type === 'file_edit')
                                        <code>{{ $change->target_path }}</code>
                                    @elseif ($change->type === 'command')
                                        <code>{{ $change->command }}</code>
                                    @else
                                        <code class="line-clamp-3">{{ $change->sql }}</code>
                                    @endif
                                </td>
                                <td class="px-2 py-2 align-top">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px]
                                        @class([
                                            'bg-yellow-500/10 text-yellow-300' => $change->status === 'pending',
                                            'bg-emerald-500/10 text-emerald-300' => $change->status === 'applied',
                                            'bg-slate-500/10 text-slate-300' => $change->status === 'cancelled',
                                        ])
                                    ">
                                        {{ $change->status }}
                                    </span>
                                </td>
                                <td class="px-2 py-2 align-top">
                                    <div class="flex flex-wrap gap-1 justify-end">
                                        @if ($change->type === 'file_edit')
                                            <details class="text-[10px]">
                                                <summary class="cursor-pointer text-sky-300 hover:text-sky-200">
                                                    عرض الـ Diff
                                                </summary>
                                                <pre class="mt-1 max-h-40 w-80 overflow-auto rounded border border-slate-700 bg-slate-950 p-2 text-[10px] text-slate-100 whitespace-pre-wrap">
{{ $change->diff }}
                                                </pre>
                                            </details>
                                        @endif

                                        @if ($change->status === 'pending')
                                            <form method="POST" action="{{ route('ai-developer.changes.apply', $change) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-emerald-500/90 px-2 py-0.5 text-[10px] font-medium text-slate-950 hover:bg-emerald-400"
                                                >
                                                    تطبيق
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('ai-developer.changes.cancel', $change) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-slate-700 px-2 py-0.5 text-[10px] font-medium text-slate-100 hover:bg-slate-600"
                                                >
                                                    إلغاء
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-2 py-6 text-center text-slate-500 text-[11px]">
                                    لا توجد تغييرات حتى الآن. اكتب طلباً للمساعد في الأعلى ليقترح تعديلات على المشروع.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body></html>
