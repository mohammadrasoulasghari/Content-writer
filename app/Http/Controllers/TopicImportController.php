<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TopicsImport;
class TopicImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new TopicsImport, $request->file('file'));

        return back()->with('success', 'مقالات با موفقیت ایمپورت شدند.');
    }
}
