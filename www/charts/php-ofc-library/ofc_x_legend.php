<?php

class x_legend
{
	function x_legend( $text='' )
	{
		$this->text = $text;
	}
	
	function set_style( $css )
	{
		$this->style = $css;
		//"{font-size: 20px; color:#0000ff; font-family: Arial, Helvetica; text-align: center;}";		
	}
}