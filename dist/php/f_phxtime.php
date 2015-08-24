<?php 

function phx_time($type)
{
	date_default_timezone_set('America/Phoenix'); 
	if ($type == "date")
	{
		return date('m/d/y');
	}

	if ($type == "dateYear")
	{
		return date('m/d/Y');
	}

	if ($type == "time")
	{
		return date('H:i:s');
	}
}
?>
