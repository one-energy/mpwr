<x-app.auth :title="__('Profile')">
    <x-profile.show-profile-information :userLevel="$userLevel" :userEniumPoints="$userEniumPoints"  :stockPoints="$stockPoints"/>
</x-app.auth>