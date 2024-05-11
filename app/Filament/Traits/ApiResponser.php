<?php

namespace App\Filament\Traits;

use App\Models\Academic;

trait AcedemicYear
{
	
	protected function activeAcedmic()
	{
		return Academic::where('status', true)->first();
	}

}