<?php
/**
 * Book Model Class  
 * ==============================================================
 * Simplified model - Contains only properties and constructor
 * Database operations are in controller/books_traitement.php
 */

class Book {
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
    public $description;
    public $created_at;

    /**
     * Constructor - Initialize book properties
     */
    public function __construct($title = '', $author = '', $genre = '', $cover = '📖', $coinCost = 100, $xpReward = 150, $coinReward = 40, $audience = 'All', $trending = 0, $description = '') {
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
        $this->cover = $cover;
        $this->coinCost = $coinCost;
        $this->xpReward = $xpReward;
        $this->coinReward = $coinReward;
        $this->audience = $audience;
        $this->trending = $trending;
        $this->description = $description;
    }
}
?>
