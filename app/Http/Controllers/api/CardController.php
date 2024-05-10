<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\UpdateNoteRequest;
use App\Services\CardService;

class CardController extends Controller
{
    public $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    function store(AddCardRequest $request)
    {
        $data = $this->cardService->save($request);
        return $this->successJsonResponse(200, $data);
    }

    public function getBlankCards()
    {
        $blankCards = $this->cardService->getBlankCards();
        return jsonResponse(0, $blankCards);
    }

    public function updateNote(UpdateNoteRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->updateNote($data);
        return jsonResponse($result ? 0 : 1);
    }
}
