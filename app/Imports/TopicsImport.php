<?php

namespace App\Imports;

use App\Models\Topic;
use Maatwebsite\Excel\Concerns\ToModel;

class TopicsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Topic([
            'title' => $row['title'],
            'description' => $row['description'],
            'english_name' => $row['english_name'],
            'slug' => $row['slug'],
            'is_approved' => $row['is_approved'],
            'approved_at' => $row['approved_at'],
            'priority' => $row['priority'],
            'tags' => $row['tags'],
            'author_id' => $row['author_id'],
        ]);
    }
}
