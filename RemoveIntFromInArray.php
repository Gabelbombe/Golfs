<?php
/**
 * Question: Given an array and a value, how to implement a function to
 * remove all instances of that value in place and return the new length?
 * The order of elements can be changed. It doesn't matter what you leave
 * beyond the new length.
 *
 * For example, if the input array is {4, 3, 2, 1, 2, 3, 6}, the result array
 * after removing value 2 contains numbers {4, 3, 1, 3, 6}, and the new length
 * of the remaining array is 5.
 */
Namespace Math
{
  Class RemoveIntFromInArray
  {
    protected $num  = [],
              $len  = 0,
              $n    = false;

    public function __construct(array $num, $n)
    {
      $this->num = $num;
      $this->len = count($num);
      $this->n   = $n;
    }

    public function removeInstances()
    {
      $a = $this->num;
      if (empty($this->num) || $this->len < 1 || ! $this->n) return;

      $i = 0;
      for ($j=0;$j<$this->len;$j++) if ($a[$j] != $this->n) $a[$i++] = $a[$j];

      return $i; //new dim of arr.
    }
  }
}

Namespace //empty so no scoping conflicts
{
  USE \Math\RemoveIntFromInArray AS RFA;

  $RFA = New RFA([4, 3, 2, 1, 2, 3, 6], 2);
  echo "\n--> {$RFA->removeInstances()} \n";
}
