<?php
class BinaryNode {
	
	protected $left = null;
	protected $data;
	protected $right = null;
	
	function __construct($obj = null)
	{
		$this->data = $obj;
	}
	
	function _setLeft($bNode = null)
	{
		$this->left = $bNode;
		return $this;
	}
	
	function setLeft($obj)
	{
		$this->left = new BinaryNode($obj);
		$this->left->right = $this;
		return $this->left;
	}
	
	function left()
	{
		return $this->left;
	}
	
	function setData($obj)
	{
		return ($this->data = $obj);
	}
	
	function data()
	{
		return $this->data;
	}
	
	function _setRight($bNode = null)
	{
		$this->right = $bNode;
		return $this;
	}
	
	function setRight($obj)
	{
		$this->right = new BinaryNode($obj);
		$this->right->left = $this;
		return $this->right;
	}
	
	function right()
	{
		return $this->right;
	}
	
	function remove()
	{
		if ($this->left !== null)
		{
			$this->left->right = $this->right;
		}
		if ($this->right !== null)
		{
			$this->right->left = $this->left;
		}
		$this->left = null;
		$this->right = null;
		return $this->data;
	}
	
	function insertLeft($obj)
	{
		$rval = new BinaryNode($obj);
		$rval->right = $this;
		$rval->left = $this->left;
		if ($rval->left !== null)
		{
			$rval->left->right = $rval;
		}
		$rval->right->left = $rval;
		return $rval;
	}
	
	function insertRight($obj)
	{
		$rval = new BinaryNode($obj);
		$rval->left = $this;
		$rval->right = $this->right;
		$rval->left->right = $rval;
		if ($rval->right !== null)
		{
			$rval->right->left = $rval;
		}
		return $rval;
	}
	
}
?>