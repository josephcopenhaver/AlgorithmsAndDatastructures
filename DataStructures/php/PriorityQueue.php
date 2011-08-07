<?php
/*
	By default, this is a maxheap priority queue with a time based default system for items that are at the same priority.
	The maxheap behavior can be inverted to a minheap behavior via the constructor boolean.
	
	Each element is required to have a compareTo() method defined and all elements must be compatible in this area.
	The return values of this function map as follows between inputs one(A) and two(B).
	A = B :  0 : leave alone, objects can be grouped together in some sorting function
	A < B : -1 : leave alone, but different
	A > B :  1 : swap, but different
	
	The entire queue list is always contiguous, but never really order enforced.
*/
class PriorityQueue {
	
	private $invert;
	private $dataQueue = array();
	private $timeQueue = array();
	private $size = 0;
	
	function __construct($invert = false) {
		$this->invert = $invert ? true : false;
	}
	
	private function compareTo($idx1, $idx2) {
		$obj1 = $this->dataQueue[$idx1];
		$obj2 = $this->dataQueue[$idx2];
		if (is_numeric($obj1) && is_numeric($obj2))
		{
			if ($obj1 == $obj2)
			{
				$rval = 0;
			}
			elseif (($obj1 < $obj2) !== $this->invert)
			{
				$rval = -1;
			}
			else
			{
				$rval = 1;
			}
		}
		else
		{
			$rval = $this->invert ? $obj2->compareTo($obj1) : $obj1->compareTo($obj2);
		}
		if ($rval === 0)
		{
			$obj1 = $this->timeQueue[$idx1];
			$obj2 = $this->timeQueue[$idx2];
			if ($obj1 !== $obj2)
			{
				if ($obj1 > $obj2)
				{
					$rval = -1;
				}
				else
				{
					$rval = 1;
				}
			}
		}
		return $rval;
	}
	
	function isEmpty() {
		return $this->size === 0;
	}
	
	function size() {
		return $this->size;
	}
	
	function peek() {
		return $this->dataQueue[0];
	}
	
	function peekTime() {
		return $this->timeQueue[0];
	}
	
	function add($obj) {
		$this->dataQueue[$this->size] = $obj;
		$this->timeQueue[$this->size] = microtime(true);
		$this->percolateUp($this->size);
		$this->size++;
		return $this;
	}
	
	private function percolateUp($idx) {
		$swap = null;
		$nextIdx = null;
		while ($idx !== 0)
		{
			$nextIdx = floor($idx/2);
			if ($this->compareTo($idx, $nextIdx) > 0)
			{
				$swap = $this->dataQueue[$idx];
				$this->dataQueue[$idx] = $this->dataQueue[$nextIdx];
				$this->dataQueue[$nextIdx] = $swap;
				$swap = $this->timeQueue[$idx];
				$this->timeQueue[$idx] = $this->timeQueue[$nextIdx];
				$this->timeQueue[$nextIdx] = $swap;
				unset($swap);
				$idx = $nextIdx;
			}
			else
			{
				break;
			}
		}
	}
	
	function remove($wantArray = false) {
		$idx = 0;
		$rval = $wantArray ? array('data' => $this->dataQueue[$idx], 'queued_at' => $this->timeQueue[$idx]) : $this->dataQueue[$idx];
		if ($this->size === 1)
		{
			//speedup: just clear everything out and return
			unset($this->dataQueue[0]);
			unset($this->timeQueue[0]);
			$this->size = 0;
			return $rval;
		}
		$maxIdx = $this->size - 1;
		$nextIdx = 1;
		$nextIdxR = 2;
		while ($nextIdx < $this->size)
		{
			if ($nextIdx === $maxIdx)
			{
				//speedup: end perfectly finishes off the tree
				$this->dataQueue[$idx] = $this->dataQueue[$nextIdx];
				$this->timeQueue[$idx] = $this->timeQueue[$nextIdx];
				$idx = $nextIdx;
				unset($this->dataQueue[$idx]);
				unset($this->timeQueue[$idx]);
				break;
			}
			else
			{
				if ($this->compareTo($nextIdx, $nextIdxR) <= 0)
				{
					$nextIdx = $nextIdxR;
				}
				$this->dataQueue[$idx] = $this->dataQueue[$nextIdx];
				$this->timeQueue[$idx] = $this->timeQueue[$nextIdx];
			}
			$idx = $nextIdx;
			$nextIdxR = $idx + 1;
			$nextIdxR += $nextIdxR;
			$nextIdx = $nextIdxR - 1;
		}
		$this->size--;
		if ($idx !== $maxIdx)
		{
			$this->fillLeafVoid($idx);
		}
		return $rval;
	}
	
	private function fillLeafVoid($idx) {
		$this->dataQueue[$idx] = $this->dataQueue[$this->size];
		$this->timeQueue[$idx] = $this->timeQueue[$this->size];
		unset($this->dataQueue[$this->size]);
		unset($this->timeQueue[$this->size]);
		$this->percolateUp($idx);
	}
	
	function sort() {
		if ($this->size < 3)
		{
			//speedup
			return $this->dataQueue;
		}
		$idx = 0;
		$tmpData = array();
		$tmpTime = array();
		$tmpSize = $this->size;
		while (!$this->isEmpty())
		{
			$tmpTime[$idx] = $this->peekTime();
			$tmpData[$idx] = $this->remove();
			$idx++;
		}
		$this->size = $tmpSize;
		$this->timeQueue = $tmpTime;
		$this->dataQueue = $tmpData;
		return $this->dataQueue;
	}
	
}
?>