@if ($alert = session('alert'))
    <x-alert
        :color="$alert->color"
        :title="$alert->title"
        :description="$alert->description"
     />
@endif
