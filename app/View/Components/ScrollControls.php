<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route; // Bunu eklemeyi unutma

class ScrollControls extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        // Eğer o anki rota adı 'login' ise (veya dosya adı 'login' ise) null dönüyoruz
        if (request()->routeIs('login')) {
            return ''; 
        }

        return view('components.scroll-controls');
    }
}