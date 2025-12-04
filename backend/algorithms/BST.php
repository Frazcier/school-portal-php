<?php
class Node {
    public $data;
    public $key;
    public $left;
    public $right;

    public function __construct($data, $keyVal) {
        $this->data = $data;
        $this->key = $keyVal;
        $this->left = null;
        $this->right = null;
    }
}

class BST {
    public $root = null;

    public function insert($subject) {
        $keyVal = $subject['subject_code'];
        $this->root = $this->insertHelper($this->root, $subject, $keyVal);
    }

    private function insertHelper($node, $subject, $keyVal) {
        if ($node === null) {
            return new Node($subject, $keyVal);
        }

        if (strcasecmp($keyVal, $node->key) < 0) {
            $node->left = $this->insertHelper($node->left, $subject, $keyVal);
        } else {
            $node->right = $this->insertHelper($node->right, $subject, $keyVal);
        }

        return $node;
    }

    public function search($keyVal) {
        return $this->searchHelper($this->root, $keyVal);
    }

    private function searchHelper($node, $keyVal) {
        if ($node === null || strcasecmp($node->key, $keyVal) === 0) {
            return $node ? $node->data : null;
        }

        if (strcasecmp($keyVal, $node->key) < 0) {
            return $this->searchHelper($node->left, $keyVal);
        }

        return $this->searchHelper($node->right, $keyVal);
    }
}
?>