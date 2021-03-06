<?php

namespace App\Enum;

use ReflectionClass;

final class Role
{
    public const ADMIN = 'Admin';

    public const OWNER = 'Owner';

    public const DEPARTMENT_MANAGER = 'Department Manager';

    public const REGION_MANAGER = 'Region Manager';

    public const OFFICE_MANAGER = 'Office Manager';

    public const SALES_REP = 'Sales Rep';

    public const SETTER = 'Setter';

    public static function getValues(): array
    {
        $class = new ReflectionClass(new self());

        return array_values($class->getConstants());
    }
}
