<?php
    class Book {
        public $title;
        public $author;
        public $pubYear;

        public function __construct($title, $author, $pubYear) {
            $this->title = $title;
            $this->author = $author;
            $this->pubYear = $pubYear;
        }

        function setTitle($title) {
            $this->title = $title;
        }
        function getTitle() {
            return $this->title;
        }
        function setAuthor($author) {
            $this->author = $author;
        }
        function getAuthor() {
            return $this->author;
        }
        function setYear($pubYear) {
            $this->pubYear = $pubYear;
        }
        function getYear() {
            return $this->pubYear;
        }
    }
?>