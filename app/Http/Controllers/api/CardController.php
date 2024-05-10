<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\FindCardRequest;
use App\Models\Card;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    function find(FindCardRequest $request)
    {
        $data = $request->validated();
        $card_number = $request->card_number;
        $data = $this->cardService->findByNumberUnassigned($card_number);
        if ($data['success']) {
            return $this->successJsonResponse(200, $data);
        }
        return $this->failJsonResponse(400, $data);
    }

    public function findByCardNumber(FindCardRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->getByCardNumber($data['card_number']);

        return jsonResponse(0, $result);
    }

    function store(AddCardRequest $request)
    {
        $data = $this->cardService->save($request);
        return $this->successJsonResponse(200, $data);
    }
}
