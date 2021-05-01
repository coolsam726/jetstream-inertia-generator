<?php namespace Savannabits\JetstreamInertiaGenerator\Generators\Traits;

use Illuminate\Support\Facades\File;

trait FileManipulations {

    protected function strReplaceInFile($fileName, $ifExistsRegex, $find, $replaceWith) {
        $content = File::get($fileName);
        if (preg_match($ifExistsRegex, $content)) {
            return null;
        }

        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }
    protected function strReplaceInFileAnyway($fileName, $find, $replaceWith) {
        $content = File::get($fileName);
        return File::put($fileName, str_replace($find, $replaceWith, $content));
    }
}
