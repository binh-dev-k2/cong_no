<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\addCustomerRequest;
use App\Models\Bank;
use App\Services\CardService;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $customer_service;
    public $card_service;

    public function __construct(CustomerService $customer_service, CardService $card_service) {
        $this->customer_service = $customer_service;
        $this->card_service = $card_service;
    }

    public function index()
    {
        $list_bank = Bank::all();

        return view('admin.customer.view.index', compact('list_bank'));
    }

    public function showAllCustomer() : JsonResponse
    {
        $data = $this->customer_service->showAll();
        return $this->successJsonResponse('200',$data);
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
    public function store(addCustomerRequest $request, CardService $card_service)
    {
        $data = $this->customer_service->save($request);
        $card_number_list = $request->card_added_number;
        if ($data['success']) {
            $card_service->assignCustomer($card_number_list, $data['data']->id);
        }
        return $this->successJsonResponse(200, $data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
