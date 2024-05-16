<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\DeleteCardRequest;
use App\Http\Requests\Card\EditCardRequest;
use App\Http\Requests\Card\RemindCardRequest;
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

    function edit(EditCardRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->update($data);
        return jsonResponse($result ? 0 : 1);
    }

    function destroy(DeleteCardRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->delete($data);
        return jsonResponse($result ? 0 : 1);
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

    public function remindCard(RemindCardRequest $request)
    {
        $data = $request->validated();
        $result = $this->cardService->remindCard($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function find(Request $request)
    {
        $data = $request->input();
        $result = $this->cardService->find($data['search']);
        return jsonResponse(0, $result);
    }
}
