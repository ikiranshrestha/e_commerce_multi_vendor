<?php

// database/seeders/MerchantSeeder.php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        Merchant::create([
            'name'  => 'Merchant One',
            'email' => 'merchant1@test.com',
        ]);

        Merchant::create([
            'name'  => 'Merchant Two',
            'email' => 'merchant2@test.com',
        ]);
    }
}
