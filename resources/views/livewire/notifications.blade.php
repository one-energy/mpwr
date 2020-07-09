<div>
    @if($notifications->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @foreach($notifications as $notification)
                <x-notification :notification="$notification"/>
            @endforeach
        </div>
    @endif
</div>
