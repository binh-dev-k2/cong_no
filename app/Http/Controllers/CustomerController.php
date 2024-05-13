<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\AddCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Bank;
use App\Models\Customer;
use App\Services\CardService;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $customerService;
    public $cardService;

    public function __construct(CustomerService $customerService, CardService $cardService)
    {
        $this->customerService = $customerService;
        $this->cardService = $cardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(): View
    {
        $banks = Bank::get();

        return view('customer.index', compact('banks'));
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

    public function datatable(Request $request): JsonResponse
    {
        $result = $this->cardService->filterDatatableCustomer($request->all());
        return response()->json($result);
    }

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

    public function destroy(int $phone, CardService $cardService): JsonResponse
    {
        $customer = Customer::where('phone', $phone)->first();
        if ($customer) {
            $id = $customer->id;
            $cardService->unassignCustomer($id);
        }
        $data = $this->customerService->delete($phone);
        return $this->successJsonResponse(200, $data);
    }
}
