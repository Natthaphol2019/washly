<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            // 🌟 เปลี่ยนจาก name กับ email มาใช้ฟิลด์ที่เราสร้างขึ้นมาใหม่
            'fullname' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'phone' => fake()->numerify('08########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'customer',
            'remember_token' => Str::random(10),
        ];
    }
}