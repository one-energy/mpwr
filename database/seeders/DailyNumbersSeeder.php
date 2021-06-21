<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\DailyNumber;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Database\Seeder;

class DailyNumbersSeeder extends Seeder
{
    public function run()
    {
        User::query()
            ->whereNotIn('role', [Role::ADMIN, Role::OWNER])
            ->each(function (User $user) {
                collect($this->periods())
                    ->each(fn(DateTimeImmutable $date) => $this->createDailyNumber($user, $date));
            });
    }

    private function periods(): array
    {
        return [
            DateTimeImmutable::createFromMutable(today()->subDay()),
            DateTimeImmutable::createFromMutable(today()->today()),
            DateTimeImmutable::createFromMutable(today()->addDay()),
        ];
    }

    private function createDailyNumber(User $user, mixed $day): void
    {
        DailyNumber::factory()->create([
            'user_id'   => $user->id,
            'office_id' => $user->office_id,
            'date'      => $day->format('Y-m-d')
        ]);
    }
}
