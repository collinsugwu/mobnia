<?php


namespace Tests\Traits;


use App\Models\File;
use Illuminate\Support\Facades\Storage;

trait Helper
{

    private function createFiles($filesCount = 3, $persist = true, $completed = false)
    {
        $name = uniqid('file');
        $path = "/uploads/{$name}.txt";
        Storage::put($path, 'test content');

        return $persist
            ? factory(File::class, $filesCount)->create(['url' => $path, 'completed' => $completed])
            : factory(File::class, $filesCount)->make(['url' => $path, 'completed' => $completed]);
    }

}
