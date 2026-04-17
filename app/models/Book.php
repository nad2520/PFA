<?php

class Book
{
    public $id;
    public $title;
    public $author;
    public $genre;
    public $cover;
    public $coinCost;
    public $xpReward;
    public $coinReward;
    public $audience;
    public $trending;

    public function __construct($title, $author, $genre)
    {
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
    }
}

