<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BioTimeService;
use App\Models\Transaction;
use Carbon\Carbon;

class FetchBioTimeTransactionsMonthly extends Command
{
    protected $signature = 'fetch:biotime-transactions-monthly {--months=1 : Number of months to fetch}';
    protected $description = 'Fetch transactions from BioTime API for a specified number of months and save to database';
    protected $bioTimeService;

    public function __construct(BioTimeService $bioTimeService)
    {
        parent::__construct();
        $this->bioTimeService = $bioTimeService;
    }

    public function handle()
    {
        $months = $this->option('months');
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subMonths($months)->startOfMonth();

        $this->info("Fetching transactions from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");

        $params = [
            'page' => 1,
            'page_size' => 100,
            'start_time' => $startDate->format('Y-m-d H:i:s'),
            'end_time' => $endDate->format('Y-m-d H:i:s'),
        ];

        $totalFetched = 0;

        do {
            try {
                $response = $this->bioTimeService->getTransactions($params);

                // Process each transaction
                foreach ($response['data'] as $transactionData) {
                    Transaction::updateOrCreate(
                        ['id' => $transactionData['id']],
                        ['emp_code' => $transactionData['emp_code']],
                        [
                            'punch_time' => $transactionData['punch_time'],
                            'punch_state' => $transactionData['punch_state'],
                            'punch_state_display' => $transactionData['punch_state_display'],
                            'verify_type' => $transactionData['verify_type'],
                            'verify_type_display' => $transactionData['verify_type_display'],
                            'area_alias' => $transactionData['area_alias'],
                            'upload_time' => $transactionData['upload_time'],
                        ]
                    );
                }

                $totalFetched += count($response['data']);
                $this->info("Fetched {$totalFetched} transactions so far...");

                // Increment the page for the next iteration
                $params['page']++;
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
                return;
            }

        } while ($response['next'] !== null);

        $this->info("Total of {$totalFetched} transactions fetched and saved successfully.");
    }
}