<?php

namespace Database\Seeders;

use App\Models\Prompt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('prompt_data.json'));
        $data = json_decode($json, true);

        Prompt::create([
            "title" => "main",
            "prompt" => $data['prompt']
        ]);
    }
}
