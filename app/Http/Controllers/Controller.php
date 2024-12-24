<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $data = [];

    public function __construct() {
        $this->data['crumbs'] = [
            [
                'title' => 'Dashboard',
                'url' => route('home'),
                'icon'  => 'fa fa-home'
            ]
        ];
    }

    public function json_success($payload, $message = "Success, API is called.", $is_paginate = false){
        if ($is_paginate) {
            $payload = $payload->toArray();
            $result = [
                "status_code" =>"200",
                "status_message" => $message,
                "data" => $payload['data'],
                "meta" => [
                    "current_page" => $payload['current_page'],
                    "first_page_url" => $payload['first_page_url'],
                    "from" => $payload['from'],
                    "last_page" => $payload['last_page'],
                    "last_page_url" => $payload['last_page_url'],
                    "links" => $payload['links'],
                    "next_page_url" => $payload['next_page_url'],
                    "path" => $payload['path'],
                    "per_page" => $payload['per_page'],
                    "prev_page_url" => $payload['prev_page_url'],
                    "to" => $payload['to'],
                    "total" => $payload['total']
                ]
            ];
        } else {
            $result = [
                "status_code" =>"200",
                "status_message" => $message,
                "data" => $payload
            ];
        }

        return response()->json($result);
    }

    public function json_error($payload=null, $message="Failed to process request", $errorCode="412")
    {
        $result = [
            "data" => $payload,
            "status_code" => $errorCode,
            "status_message" => $message,
        ];
        return response()->json($result, $errorCode);
    }


}
