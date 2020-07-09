@props(['text'])

<header {{ $attributes->merge(['class'=> 'py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8']) }}>
    <h1 class="text-xl sm:text-2xl lg:text-3xl leading-9 font-bold text-white">
        {{ $text }}
    </h1>

    {{ $slot }}
</header>
