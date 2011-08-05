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
	
}
?>