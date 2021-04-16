@props(['index'])
<div id="defaultRow-{{$index}}" class="table-row bg-green-600" x-on:click="collapseRow('firstRow-{{$index}}')">
    {{ $row }}
</div>
<div id="firstRow-{{$index}}" class="hidden" x-on:click="collapseRow('secondRow-{{$index}}')">
    {{ $firstChild }}
</div>
<div id="secondRow-{{$index}}" class="hidden">
    {{ $secondChild }}
</div>
