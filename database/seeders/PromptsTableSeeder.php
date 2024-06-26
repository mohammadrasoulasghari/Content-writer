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
        $prompts = [
            [
                "title" => "main",
                "prompt" => $data['main'],
            ],
            [
                "title" => "system",
                "prompt" => $data['system']
            ],
            [
                "title" => "assistant",
                "prompt" => $data['assistant']
            ],
            [
                "title" => "keywords",
                "prompt" => $data['keywords']
            ],
            [
                "title" => "description",
                "prompt" => $data['description']
            ],
            [
                "title" => "english_sentences",
                "prompt" => $data['english_sentences']
            ],
        ];

        foreach ($prompts as $prompt){
            Prompt::updateOrCreate([
                "title" => $prompt['title'],
                "prompt" => $prompt['prompt']
            ]);
        }

    }
}
