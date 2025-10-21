<?php

namespace App\Services;

use Imagick;

class ImageFilterService
{
    public function applyFilters(string $imagePath): string
    {
        $imagick = new Imagick($imagePath);
        $imagick->negateImage(false);
        $imagick->modulateImage(100, 150, 100);
        $filteredImagePath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/filtered_' . pathinfo($imagePath, PATHINFO_BASENAME);
        $imagick->writeImage($filteredImagePath);
        $imagick->destroy();
        return $filteredImagePath;
    }
}
