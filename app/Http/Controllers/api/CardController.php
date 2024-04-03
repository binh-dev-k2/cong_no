<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public  $card_service;
    public function __construct(CardService $card_service) {
        $this->card_service = $card_service;
    }
    function find(Request $request)  {
        $card_number = $request->card_number;
        $data = $this->card_service->findByNumberUnassigned($card_number);
        if($data['success']) {
            return $this->successJsonResponse(200, $data);
        }
        return $this->failJsonResponse(400, $data);
    }
    function store(AddCardRequest $request) {
        $data = $this->card_service->save($request);
        return $this->successJsonResponse(200, $data);
    }


}
