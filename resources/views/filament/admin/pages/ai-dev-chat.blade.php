<x-filament::page>
    <div class="space-y-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-100">
                AI Developer Console (متكامل مع النظام الخارجي)
            </h2>
            <p class="text-sm text-gray-400">
                هذه الواجهة تعرض نظام AI Developer Pro الخارجي داخل لوحة التحكم.
            </p>
        </div>

        <div class="rounded-xl overflow-hidden border border-gray-700 bg-gray-900">
            <iframe
                src="{{ url('/ai-developer') }}"
                class="w-full"
                style="min-height: 80vh; border: none;"
            ></iframe>
        </div>
    </div>
</x-filament::page>
