@php($alert = session('alert'))

<x-alert
    :color="optional($alert)->color"
    :title="optional($alert)->title"
    :description="optional($alert)->description"
/>
