<?php

namespace App\Http\Controllers;

use App\Services\DebtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DebitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $debtService;

    /**
     * @param $debtService
     */
    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */

    function index() {
        // $list_bank = Bank::all();

        return view('admin.debit.index');
    }

    /**
     * @SWG\Get(
     *     path="/api/customer",
     *     summary="Get all customer",
     *     tags={"Customer"},
     *     description="Get all customer",
     *     @SWG\Response(response=200, description="successful operation")
     *     @SWG\Response(response=404, description="Not Found")
     * @return \Illuminate\Http\Response
     */

    public function showAllDebits(Request $request): JsonResponse
    {
        $result = $this->debtService->datatable($request->all());
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /** @SWG\Post(

     *     path="/api/customer/store",
     *     summary="Add new customer",
     *     tags={"Customer"},
     *     description="Add new customer",
     *     @SWG\Header(header="Authorization", type="string", description="Authorization)
     *    @SWG\Parameter(
     * ) */
    public function store(AddCustomerRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $phone
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $phone, CardService $cardService)
    {
       //
    }
}
