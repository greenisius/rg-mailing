<?php


namespace RG;


class Task
{
    public $destination;
    public $theme;
    public $content;
    public $attachment;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}