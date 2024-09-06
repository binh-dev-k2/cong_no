<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\AddCustomerRequest;
use App\Http\Requests\Customer\DeleteCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Bank;
use App\Services\CardService;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public $customerService;
    public $cardService;

    public function __construct(CustomerService $customerService, CardService $cardService)
    {
        $this->customerService = $customerService;
        $this->cardService = $cardService;
    }

    public function index(): View
    {
        $banks = Bank::get();

        return view('customer.index', compact('banks'));
    }

    public function datatable(Request $request): JsonResponse
    {
        $result = $this->cardService->filterDatatableCustomer($request->all());
        return response()->json($result);
    }

    public function store(AddCustomerRequest $request)
    {
        $data = $request->validated();

        $result = $this->customerService->create($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function update(UpdateCustomerRequest $request)
    {
        $data = $request->validated();
        $result = $this->customerService->update($data);
        return jsonResponse($result ? 0 : 1);
    }

    public function destroy(DeleteCustomerRequest $request)
    {
        $customer_ids = $request->validated();
        $customer_ids = $customer_ids['list_selected'];
        $result = $this->customerService->delete($customer_ids);
        return jsonResponse($result ? 0 : 1);
    }
}
