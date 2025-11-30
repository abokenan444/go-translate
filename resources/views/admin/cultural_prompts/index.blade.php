@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-4">Cultural Prompt Engine – Preview</h1>

    <p class="text-sm text-gray-500 mb-6">
        هذه الصفحة تسمح لك باختبار الـ Unified Prompt Generator قبل دمجه في خطوط الترجمة الفعلية.
    </p>

    <form id="preview-form" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">النص الأصلي</label>
            <textarea name="source_text" rows="4" class="w-full border rounded p-2" required></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">لغة المصدر</label>
                <input type="text" name="source_locale" class="w-full border rounded p-2" value="en" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الثقافة الهدف (code)</label>
                <input type="text" name="target_culture" class="w-full border rounded p-2" value="nl_nl" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Task Key</label>
                <input type="text" name="task_key" class="w-full border rounded p-2" value="product_description" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Industry Key (اختياري)</label>
                <input type="text" name="industry_key" class="w-full border rounded p-2" placeholder="ecommerce_general">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Emotional Tone Key (اختياري)</label>
                <input type="text" name="emotional_tone_key" class="w-full border rounded p-2" placeholder="trust_safety">
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            معاينة الـ Prompt
        </button>
    </form>

    <div id="result" class="mt-8 hidden">
        <h2 class="text-xl font-semibold mb-2">النتيجة (JSON)</h2>
        <pre class="bg-gray-900 text-green-200 text-xs p-4 rounded overflow-x-auto" id="result-json"></pre>
    </div>
</div>

<script>
document.getElementById('preview-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    const token = form.querySelector('input[name=_token]').value;

    const res = await fetch('{{ route('admin.cultural-prompts.preview') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
        body: data,
    });

    const json = await res.json();
    document.getElementById('result').classList.remove('hidden');
    document.getElementById('result-json').textContent = JSON.stringify(json, null, 2);
});
</script>
@endsection
