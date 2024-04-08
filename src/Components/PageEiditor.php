<?php

namespace App\Livewire\DropBlockEditor;

use Livewire\Component;
use App\Models\pages;
use Illuminate\Http\Request;
class PageEiditor extends Component
{
    public $page;

    public function mount(Request $request)
    {
        if($request->page){
            $this->page = pages::where('slug', $request->page)->first();


            if (! $this->page) {
                abort(404);
            }


        }
    }
    public function render()
    {
        return view('livewire.dropblockeditor.page-eiditor');
    }
}
