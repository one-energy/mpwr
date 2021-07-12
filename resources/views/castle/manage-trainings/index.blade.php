<x-app.auth :title="__('Manage Training')">
  <div>
    <livewire:castle.manage-trainings.trainings :section="$section" :department="$department" />
  </div>
</x-app.auth>