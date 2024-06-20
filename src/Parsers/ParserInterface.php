<?php

namespace Mo_sweed\DropBlockEditor\Parsers;
interface ParserInterface
{
    public function base($base);

    public function context($context);

    public function parse();

    public function output();
}