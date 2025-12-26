<x-filament::page>
    @if($statusLine)
        <div class="p-3 rounded-lg bg-green-50 text-green-800 border border-green-200 mb-4">
            {{ $statusLine }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded-xl shadow lg:col-span-1 space-y-3">
            <div class="text-lg font-semibold">Audit Runs</div>

            <div class="space-y-2">
                <label class="text-sm text-gray-600">Select run</label>
                <select wire:model.live="selectedRunId" class="w-full border rounded-md p-2">
                    @foreach($runs as $r)
                        <option value="{{ $r['id'] }}">
                            {{ $r['created_at'] }} — {{ strtoupper($r['status']) }} @if($r['is_rc']) — RC @endif
                        </option>
                    @endforeach
                </select>
            </div>

            @php
                $selected = collect($runs)->firstWhere('id', (int)$selectedRunId);
            @endphp

            @if($selected)
                <div class="text-sm">
                    <div><b>Status:</b> {{ strtoupper($selected['status']) }}</div>
                    <div><b>Summary:</b> {{ $selected['summary'] }}</div>
                    <div><b>Dir:</b> <code>{{ $selected['dir'] }}</code></div>
                    <div><b>RC:</b> {{ $selected['is_rc'] ? 'Yes' : 'No' }}</div>
                </div>

                <div class="flex flex-wrap gap-2 text-sm">
                    @if($selected['json'])
                        <a class="underline" href="{{ route('internal.audit.download', ['path'=>$selected['json']]) }}">
                            Download JSON
                        </a>
                    @endif
                    @if($selected['html'])
                        <a class="underline" href="{{ route('internal.audit.download', ['path'=>$selected['html']]) }}">
                            Download HTML
                        </a>
                    @endif
                </div>
            @else
                <div class="text-sm text-gray-600">No runs found.</div>
            @endif
        </div>

        <div class="p-4 bg-white rounded-xl shadow lg:col-span-2">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="text-lg font-semibold">Report Preview</div>
                @if($iframeUrl)
                    <a class="underline text-sm" href="{{ $iframeUrl }}" target="_blank">Open in new tab</a>
                @endif
            </div>

            @if($iframeUrl)
                <iframe
                    src="{{ $iframeUrl }}"
                    style="width: 100%; height: 75vh; border: 1px solid #e5e7eb; border-radius: 12px;"
                ></iframe>
            @else
                <div class="text-sm text-gray-600">
                    No HTML report available for this run.
                </div>
            @endif
        </div>
    </div>
</x-filament::page>
