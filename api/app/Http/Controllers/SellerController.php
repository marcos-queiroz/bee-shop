<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResendReportEmailRequest;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\updateSellerRequest;
use App\Mail\SellerDailyReportMail;
use App\Models\Seller;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class SellerController extends Controller
{
    public function __construct(
        protected SellerService $sellerService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response($this->sellerService->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerRequest $request): Response
    {
        $seller = $this->sellerService->create($request->validated());
        return response($seller, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller): Response
    {
        return response($seller);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateSellerRequest $request, Seller $seller): Response
    {
        $seller = $this->sellerService->update($seller, $request->validated());
        return response($seller);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller): Response
    {
        $this->sellerService->delete($seller);
        return response()->noContent();
    }

    public function salesBySeller(Seller $seller)
    {
        $sales = $this->sellerService->getBySellerPaginated($seller);
        return response($sales);
    }

    public function resendDailyEmail(ResendReportEmailRequest $request, Seller $seller): Response
    {
        $date = \Carbon\Carbon::parse($request->input('date'));

        $seller->load(['sales' => fn($q) => $q->whereDate('sale_date', $date)]);

        $sales = $seller->sales;
        $total = $this->sellerService->getSalesTotal($sales);
        $commission = $this->sellerService->getSalesCommission($sales);

        if ($sales->isEmpty()) {
            return response(['message' => 'Nenhuma venda encontrada para essa data.'], 404);
        }

        Mail::to($seller->email)->queue(
            new SellerDailyReportMail($sales, $total, $commission, $date)
        );

        return response(['message' => 'E-mail reenviado com sucesso.']);
    }
}
