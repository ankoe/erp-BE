<?php

namespace Database\Seeders;

use App\Models\PurchaseRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = array(
            [
                'title'         => 'draft',
                'description'   => 'draft'
            ],
            [
                'title'         => 'waiting office approval',
                'description'   => 'Waiting for PR approval'
            ],
            [
                'title'         => 'reject office approval',
                'description'   => 'PR rejected by User'
            ],
            [
                'title'         => 'waiting procurement approval',
                'description'   => 'Waiting Procurement confirmation'
            ],
            [
                'title'         => 'reject procurement approval',
                'description'   => 'PR rejected by Procurement'
            ],

            [
                'title'         => 'waiting rfq response',
                'description'   => 'Waiting RFQ Response'
            ],
            [
                'title'         => 'waiting rfq approval',
                'description'   => 'Waiting RFQ Approval'
            ],
            [
                'title'         => 'waiting po confirmation',
                'description'   => 'Waiting PO Confirmation'
            ],
            [
                'title'         => 'po released',
                'description'   => 'PO Released'
            ],
        );

        if (PurchaseRequestStatus::count()) PurchaseRequestStatus::truncate();

        foreach ($statuses as $status) {

            PurchaseRequestStatus::create([
                'title'         => $status['title'],
                'description'   => $status['description'],
            ]);
        }
    }
}