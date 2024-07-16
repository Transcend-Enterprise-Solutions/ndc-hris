<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BioTimeService;
use App\Models\Transaction;

class FetchBioTimeTransactions extends Command
{
    protected $signature = 'fetch:biotime-transactions';
    protected $description = 'Fetch transactions from BioTime API and save to database';
    protected $bioTimeService;

    public function __construct(BioTimeService $bioTimeService)
    {
        parent::__construct();
        $this->bioTimeService = $bioTimeService;
    }

    public function handle()
    {
        $params = [
            'page' => 1,
            'page_size' => 100,
            // Add additional query parameters if needed
        ];

        do {
            $response = $this->bioTimeService->getTransactions($params);

            if ($response['code'] !== 0) {
                $this->error('Failed to fetch transactions: ' . $response['msg']);
                return;
            }

            foreach ($response['data'] as $transactionData) {
                Transaction::updateOrCreate(
                    ['id' => $transactionData['id']], // Assuming 'id' is unique
                    [
                        'emp_code' => $transactionData['emp_code'],
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

            $params['page']++;
        } while ($response['next'] !== null);

        $this->info('Transactions fetched and saved successfully.');
    }
}
