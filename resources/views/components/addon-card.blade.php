@php
    $addonKey = $addon['code'] ?? uniqid('addon_', true);
    $oldQty = old('addons.' . $addonKey . '.qty', 1);
    $checked = old('addons.' . $addonKey . '.code') === ($addon['code'] ?? null);
@endphp

<div
    class="addon-card p-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 hover:border-pink-300 dark:hover:border-pink-500 transition-all cursor-pointer select-none"
    data-category="{{ $addon['category'] ?? 'service' }}"
    data-addon-price="{{ (float) ($addon['price'] ?? 0) }}">
    <div class="grid grid-cols-[20px_minmax(0,1fr)] sm:grid-cols-[20px_minmax(0,1fr)_96px] items-center gap-3">
        <input
            type="checkbox"
            name="addons[{{ $addonKey }}][code]"
            value="{{ $addon['code'] ?? '' }}"
            class="addon-check"
            {{ $checked ? 'checked' : '' }}>

        <div class="min-w-0 flex items-start gap-3">
            @if(!empty($addon['image_path']))
                <img src="{{ asset('storage/' . $addon['image_path']) }}" alt="{{ $addon['name'] ?? 'addon' }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover border border-gray-200 dark:border-slate-700 shrink-0">
            @else
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg border border-gray-200 dark:border-slate-700 bg-white/70 dark:bg-slate-700/60 shrink-0"></div>
            @endif
            <div class="min-w-0 flex-1 lg:max-w-[14rem]">
                <p class="font-semibold text-gray-700 dark:text-gray-200 leading-6 whitespace-normal break-normal">{{ $addon['name'] ?? '-' }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">+ ฿{{ number_format((float) ($addon['price'] ?? 0)) }} ต่อหน่วย</p>
            </div>
        </div>

        <div class="w-24 shrink-0 sm:justify-self-end col-start-2 sm:col-start-auto">
            <div class="flex items-center border border-gray-300 dark:border-slate-600 rounded-lg overflow-hidden bg-white dark:bg-slate-700">
                <button type="button" class="qty-step w-7 h-8 text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600" data-step="-1">-</button>
                <input
                    type="number"
                    min="1"
                    max="10"
                    name="addons[{{ $addonKey }}][qty]"
                    value="{{ $oldQty }}"
                    class="addon-qty w-full h-8 px-1 py-1 text-sm text-center border-0 bg-transparent"
                    {{ $checked ? '' : 'disabled' }}>
                <button type="button" class="qty-step w-7 h-8 text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600" data-step="1">+</button>
            </div>
        </div>
    </div>
</div>
