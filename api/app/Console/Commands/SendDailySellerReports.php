<?php

namespace App\Console\Commands;

use App\Mail\SellerDailyReportMail;
use App\Models\Seller;
use App\Services\SellerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySellerReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:sellers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia resumo diário de vendas para todos os vendedores';

    public function __construct(protected SellerService $sellerService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $date = now()->subDay();
        $sellers = $this->sellerService->getSellersWithSalesByDate($date);

        foreach ($sellers as $seller) {
            $sales = $seller->sales;
            $total = $this->sellerService->getSalesTotal($sales);
            $commission = $this->sellerService->getSalesCommission($sales);

            Mail::to($seller->email)->queue(
                new SellerDailyReportMail($sales, $total, $commission, $date)
            );
        }

        $this->info("Relatórios enviados para os vendedores.");
    }

}
