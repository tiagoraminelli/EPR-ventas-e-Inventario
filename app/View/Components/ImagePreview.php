<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImagePreview extends Component
{
    public string $src;
    public string $alt;

    public function __construct(string $src, string $alt = '')
    {
        $this->src = $src;
        $this->alt = $alt;
    }

    public function render()
    {
        return view('components.image-preview');
    }
}
