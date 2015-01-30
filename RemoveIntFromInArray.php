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

    public function remove1()
    {
      if (empty($this->num) || $this->len < 1 || ! $this->n) return;
      $a = $b = $c = $this->num;

      while ($a < $b)
      {
        while ($a != $this->n && count($a - $c) < $this->len) ++$a;
        while ($b == $this->n && count($b - $c) > 0)          --$a;
        if ($a < $b)
        {
          $a = $b;
          $b = $this->n;
        }
      }
      return ($a - $c);
    }

    public function remove2()
    {
      $a = $this->num;
      if (empty($this->num) || $this->len < 1 || ! $this->n) return;

      $i = 0;
      for ($j=0;$j<$len;$j++) if ($a[$j] != $n) $a[$i++] = $a[$j];

      return $i; //new dim of arr.
    }
  }
}

Namespace //empty so no scoping conflicts
{
  USE \Math\RemoveIntFromInArray AS RFA;

  $testbed = New RFA([4, 3, 2, 1, 2, 3, 6], 2);
  print_r($testbed->remove1());
  print_r($testbed->remove2());
}
