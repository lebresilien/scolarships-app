<?php

namespace App\Filament\Traits;

use App\Models\Academic;

trait ActiveYear
{
	
	protected function active()
	{
		return Academic::where('status', true)->first();
	}

}