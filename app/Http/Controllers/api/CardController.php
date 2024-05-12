<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\UpdateNoteRequest;
use App\Services\CardService;
use Illuminate\Http\Request;

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

    public function getBlankCards(Request $request)
    {
        $blankCards = $this->cardService->getBlankCards($request->all());
        return jsonResponse(0, $blankCards);
    }

    public function updateNote(UpdateNoteRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->updateNote($data);
        return jsonResponse($result ? 0 : 1);
    }
}
