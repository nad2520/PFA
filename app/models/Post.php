<?php

class Post
{
    public $id;
    public $title;
    public $content;
    public $tag;
    public $status;
    public $user_id;
    public $created_at;

    public function __construct($title, $content, $tag)
    {
        $this->title = $title;
        $this->content = $content;
        $this->tag = $tag;
    }
}

