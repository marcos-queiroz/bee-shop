<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response($this->saleService->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $sale = $this->saleService->create($request->validated());
        return response($sale, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return response($sale->load('seller'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $sale = $this->saleService->update($sale, $request->validated());
        return response($sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $this->saleService->delete($sale);
        return response()->noContent();
    }
}
