@props([
    'label' => 'Déconnexion',
])

<div
    {{ $attributes->merge(['class' => 'px-[14px] py-3 border-t border-black/[.07] dark:border-white/[.06] shrink-0']) }}>
    <form method="POST" action="{{ route('logout') }}" data-turbo="false">
        @csrf

        <button type="submit"
            class="w-full flex items-center gap-2 text-[12.5px] text-gray-400 dark:text-[#5e6e8a]
            no-underline rounded-[7px] px-2 py-1.5 transition-colors
            hover:text-cdp-red-500 hover:bg-cdp-red-500/5">
            <svg class="w-[15px] h-[15px] shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>

            <span class="truncate">
                {{ $label }}
            </span>
        </button>
    </form>
</div>
