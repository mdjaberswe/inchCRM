<?php

namespace App\Http\Composers;

class GeneralComposer
{
	public function compose($view)
	{
		$layout_class = get_layout_status();
		$view->withClass($layout_class);
	}
}