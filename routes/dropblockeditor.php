<?php

use App\Livewire\DropBlockEditor\PageEiditor;
use Illuminate\Support\Facades\Route;

Route::get('update_page/{page:slug}', PageEiditor::class)->name('pages.edit');
Route::get('create_page', PageEiditor::class)->name('pages.create');