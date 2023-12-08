<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'purchase_request_status_id',
        'material_id',
        'price',
        'description',
        'quantity',
        'total',
        'vendor_id',
        'branch_id',
        'expected_at',
        'file',
        'is_approve',
        'is_approve_rfq',
        'remarks',
        'incoterms',
        'winning_vendor_id',
        'winning_vendor_price',
        'winning_vendor_stock',
        'winning_vendor_incoterms',
        'code_rfq',
        'code_po',
        'po_created_at'
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function winningVendor()
    {
        return $this->belongsTo(Vendor::class, 'winning_vendor_id');
    }

    public function requestQuotation()
    {
        return $this->hasMany(RequestQuotation::class);
    }

    public function purchaseRequestStatus()
    {
        return $this->belongsTo(PurchaseRequestStatus::class, 'purchase_request_status_id');
    }

    /***********************************************
     *  2. Getter & Setter
    ***********************************************/

    /***********************************************
     *  3. Scope
    ***********************************************/

    /***********************************************
     *  4. Function
    ***********************************************/

    public static function generateRFQNumber()
    {
        $company_id = auth()->user()->company->id;
        $month = date('m');
        $year = date('Y');

        $count = self::whereHas('purchaseRequest', function($q) use ($company_id) {
                $q->where('company_id', $company_id);
            })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('code_rfq')
            ->count();

        // Mendapatkan nomor urut dokumen
        $documentNumber = $count ? ($count + 1) : 1;

        // Format nomor dokumen dengan menambahkan bulan dan tahun
        $documentNumber = 'RFQ-' . substr($year, 2) . $month . '-' . str_pad($documentNumber, 4, '0', STR_PAD_LEFT);

        return $documentNumber;
    }

    public static function generatePONumber()
    {
        $company_id = auth()->user()->company->id;
        $month = date('m');
        $year = date('Y');

        $count = self::whereHas('purchaseRequest', function($q) use ($company_id) {
                $q->where('company_id', $company_id);
            })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('code_po')
            ->count();

        // Mendapatkan nomor urut dokumen
        $documentNumber = $count ? ($count + 1) : 1;

        // Format nomor dokumen dengan menambahkan bulan dan tahun
        $documentNumber = 'PO-' . substr($year, 2) . $month . '-' . str_pad($documentNumber, 4, '0', STR_PAD_LEFT);

        return $documentNumber;
    }
}
