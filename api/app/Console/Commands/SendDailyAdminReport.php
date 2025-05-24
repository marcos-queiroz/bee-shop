<?php

namespace App\Console\Commands;

use App\Mail\AdminDailyReportMail;
use App\Models\Sale;
use App\Models\User;
use App\Services\SaleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyAdminReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia resumo diário para o administrador com todas as vendas';

    public function __construct(protected SaleService $saleService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $date = now();

        $sales = $this->saleService->getSalesByDate($date);
        $total = $this->saleService->getTotalBySales($sales);

        User::all()->each(function ($user) use ($sales, $total, $date) {
            Mail::to($user->email)->queue(new AdminDailyReportMail($sales, $total, $date));
        });

        $this->info("Resumo diário enviado para o administrador.");
    }
}
