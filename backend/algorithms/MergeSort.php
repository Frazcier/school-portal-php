<?php
class MergeSort {
    public function sort($array, $key) {
        if (count($array) <= 1) {
            return $array;
        }

        $middle = floor(count($array) / 2);
        $left = array_slice($array, 0, $middle);
        $right = array_slice($array, $middle);

        $left = $this->sort($left, $key);
        $right = $this->sort($right, $key);

        return $this->merge($left, $right, $key);
    }

    private function merge($left, $right, $key) {
        $result = [];
        $i = 0;
        $j = 0;

        while ($i < count($left) && $j < count($right)) {
            $valA = $left[$i][$key] ?? '';
            $valB = $right[$j][$key] ?? '';

            if (strcasecmp($valA, $valB) <= 0) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        while ($i < count($left)) {
            $result[] = $left[$i];
            $i++;
        }
        while ($j < count($right)) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }
}
?>