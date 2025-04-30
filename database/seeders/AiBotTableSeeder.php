<?php

namespace Database\Seeders;

use App\Models\AiBot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AiBotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bot=AiBot::create([
            'bot_name'=>'Levant Ai Bot'
        ]);
        $bot->image_profile()->create([
            'url'=>'levant_bot.png'
        ]);
    }
}
