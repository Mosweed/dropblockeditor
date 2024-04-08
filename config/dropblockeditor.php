<?php

use App\Classes\Blocks\Example;

return [



    'include_js' => true,

    'include_css' => true,


    'brand' => [
        'logo' => '

',
'colors' => [
'topbar_bg' => 'bg-gray-800 text-gray-800',
],
],


'blocks' => [
Example::class,
],
'parsers' => [
Shazzoo\DropBlockEditor\Parsers\Html::class,
Shazzoo\DropBlockEditor\Parsers\Editor::class,
],


];