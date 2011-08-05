<?php
class LinkedList {
	
	private $first = null;
	private $last = null;
	
	function __construct($obj = null)
	{
		if ($obj !== null)
		{
			$this->last = $this->first = new BinaryNode($obj);
		}
	}
	
	function first()
	{
		if ($this->first === null)
		{
			if ($this->last !== null)
			{
				$this->first = $this->last;
				while (($next = $this->first->left()) !== null)
				{
					$this->first = $next;
				}
			}
		}
		else
		{
			while (($next = $this->first->left()) !== null)
			{
				$this->first = $next;
			}
		}
		return $this->first;
	}
	
	function last()
	{
		if ($this->last === null)
		{
			if ($this->first !== null)
			{
				$this->last = $this->first;
				while (($next = $this->last->right()) !== null)
				{
					$this->last = $next;
				}
			}
		}
		else
		{
			while (($next = $this->last->right()) !== null)
			{
				$this->last = $next;
			}
		}
		return $this->last;
	}
	
	function isEmpty()
	{
		return ($this->first === null && $this->last === null);
	}
	
	function peek()
	{
		$first = $this->first();
		if ($first === null)
		{
			throw new Exception("Linked list is empty");
		}
		return $first->data();
	}
	
	function peekEnd()
	{
		$last = $this->last();
		if ($last === null)
		{
			throw new Exception("Linked list is empty");
		}
		return $last->data();
	}
	
	function push($obj)
	{
		$last = $this->last();
		if ($last === null)
		{
			$this->first = $this->last = new BinaryNode($obj);
		}
		else
		{
			$this->last = $last->setRight($obj);
		}
		return $this;
	}
	
	function pop()
	{
		$last = $this->last();
		if ($last === null)
		{
			throw new Exception("Linked list is empty");
		}
		$this->last = $last->left();
		$last->_setLeft();
		if ($this->last === null)
		{
			$this->first = null;
		}
		else
		{
			$this->last->_setRight();
		}
		return $last->data();
	}
	
	function unshift($obj)
	{
		$first = $this->first();
		if ($first === null)
		{
			$this->last = $this->first = new BinaryNode($obj);
		}
		else
		{
			$this->first = $first->setLeft($obj);
		}
		return $this;
	}
	
	function shift()
	{
		$first = $this->first();
		if ($first === null)
		{
			return null;
		}
		$this->first = $first->right();
		$first->_setRight();
		if ($this->first === null)
		{
			$this->last = null;
		}
		else
		{
			$this->first->_setLeft();
		}
		return $first->data();
	}
	
	function removeIndex($ridx)
	{
		if ($ridx < 0)
		{
			return $this->removeIndexEnd(abs($ridx + 1));
		}
		else if ($ridx === 0)
		{
			return $this->shift();
		}
		$cidx = 0;
		for ($idx = $this->first(); $idx !== null; $idx = $idx->right())
		{
			if ($ridx === $cidx)
			{
				$next = $idx->right();
				if ($next === null)
				{
					$idx->left()->_setRight();
					$idx->_setLeft();
					return $idx->data();
				}
				else
				{
					return $idx->remove();
				}
			}
			$cidx++;
		}
		throw new Exception("Index out of bounds!");
	}
	
	function removeIndexEnd($ridx)
	{
		if ($ridx < 0)
		{
			return $this->removeIndex(abs($ridx + 1));
		}
		else if ($ridx === 0)
		{
			return $this->pop();
		}
		$cidx = 0;
		for ($idx = $this->last(); $idx !== null; $idx = $idx->left())
		{
			if ($ridx === $cidx)
			{
				$next = $idx->left();
				if ($next === null)
				{
					$idx->right()->_setLeft();
					$idx->_setRight();
					return $idx->data();
				}
				else
				{
					return $idx->remove();
				}
			}
			$cidx++;
		}
		throw new Exception("Index out of bounds!");
	}
	
	function insert($ridx, $obj)
	{
		if ($ridx < 0)
		{
			return $this->insertEnd(abs($ridx + 1), $obj);
		}
		else if ($ridx === 0)
		{
			return $this->unshift($obj);
		}
		$cidx = 0;
		$idx = null;
		for ($idx = $this->first(); $idx !== null; $idx = $idx->right())
		{
			if ($ridx === $cidx)
			{
				$idx->insertLeft($obj);
				return $this;
			}
			$cidx++;
		}
		if ($ridx === $cidx)
		{
			$idx->insertRight($obj);
			return $this;
		}
		throw new Exception("Index out of bounds!");
	}
	
	function insertEnd($ridx, $obj)
	{
		if ($ridx < 0)
		{
			return $this->insert(abs($ridx + 1), $obj);
		}
		else if ($ridx === 0)
		{
			return $this->push($obj);
		}
		$cidx = 0;
		$idx = null;
		for ($idx = $this->last(); $idx !== null; $idx = $idx->left())
		{
			if ($ridx === $cidx)
			{
				$idx->insertRight($obj);
				return $this;
			}
			$cidx++;
		}
		if ($ridx === $cidx)
		{
			$idx->insertLeft($obj);
			return $this;
		}
		throw new Exception("Index out of bounds!");
	}
	
	function size()
	{
		$size = 0;
		$idx = $this->first();
		while ($idx !== null)
		{
			$idx = $idx->right();
			$size++;
		}
		return $size;
	}
	
	function toArray($startIndex = 0, $size = null)
	{
		if ($size === null)
		{
			$size = $this->size();
		}
		$rval = array();
		if ($size === 0)
		{
			return $rval;
		}
		else if ($size < 0)
		{
			throw new Exception("Invalid size!");
		}
		$csize = 0;
		$cstartIndex = 0;
		for ($idx = $this->first(); $idx !== null; $idx = $idx->right())
		{
			if ($startIndex > $cstartIndex)
			{
				$cstartIndex++;
				continue;
			}
			$rval[$csize] = $idx->data();
			$csize++;
			if ($csize == $size)
			{
				break;
			}
		}
		return $rval;
	}
	
}
?>