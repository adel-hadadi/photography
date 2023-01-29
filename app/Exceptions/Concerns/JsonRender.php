<?php
namespace App\Exceptions\Concerns;

use Illuminate\Http\Request;

trait JsonRender {
	public function render(Request $request)
	{
        return response()->json(array(
            'code' => str_replace("Exception", "", class_basename(get_class($this))),
            'message' => $this->getMessage()), 400);

	}
}
