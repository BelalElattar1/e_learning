<?php

namespace App\Services\codes;

use Exception;
use App\Models\Code;

class CodeService
{

    public function store($request) {

        $code = Code::create([
            'code'       => uniqid(),
            'price'      => $request['price'],
            'teacher_id' => auth()->user()->teacher->id
        ]);

        abort_if(!$code, 404, 'An error occurred. Please try again.');
        return [
            'code'  => $code->code,
            'price' => $code->price
        ];

    }

}
