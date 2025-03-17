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

        if($code) {

            return [
                'code'  => $code->code,
                'price' => $code->price
            ];

        } else {

            throw new Exception('An error occurred. Please try again.');

        }

    }

}
