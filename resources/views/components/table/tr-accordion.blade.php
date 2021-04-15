@props(['loop', 'index'])

<table class="w-6 h-6 bg-green-600" x-data="{
                open: true,
                colapseRaw () {
                    console.log('teste')
                }
            }" >

        <tr {{ $attributes->merge(['class' => 'hover:bg-gray-50']) }} @click="colapseRaw">
            {{ $raw }}
        </tr>
        <tr {{ $attributes->merge(['class' => 'hover:bg-gray-50']) }} id="">
            {{ $rawContent }}
        </tr>
</table>
