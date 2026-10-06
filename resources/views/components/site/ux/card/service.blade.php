@props([
    'title',
    'description',
    'icon',
    'link' => '#',
    'linkText' => 'Découvrir',
    'aos' => 'fade-up',
    'delay' => 100,
])

<div class="bg-gray-50 rounded-2xl p-8 shadow-sm card-hover border-l-4 border-[#CC1F1F]" data-aos="{{ $aos }}"
    data-aos-delay="{{ $delay }}">

    <div class="w-16 h-16 bg-[#0D3575]/10 rounded-xl flex items-center justify-center mb-5">
        {!! $icon !!}
    </div>

    <h3 class="text-2xl font-bold text-[#0D3575]">
        {{ $title }}
    </h3>

    <p class="text-gray-600 mt-3 leading-relaxed">
        {{ $description }}
    </p>

    <a href="{{ $link }}" class="inline-flex items-center text-[#CC1F1F] font-medium mt-5 hover:underline">
        {{ $linkText }} →
    </a>
</div>
