<?php

namespace App\Exceptions;

use Exception;

class ExceptionRender extends Exception
{
    public function render()
    {
        return response()->json(['error' => true, 'message' => $this->getMessage()]);
    }
}
