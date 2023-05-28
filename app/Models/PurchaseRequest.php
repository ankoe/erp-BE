<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'code',
        'purchase_request_status_id',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseRequestStatus()
    {
        return $this->belongsTo(PurchaseRequestStatus::class);
    }

    public function purchaseRequestItem()
    {
        return $this->hasMany(PurchaseRequestItem::class);
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

    public static function generateDocumentNumber()
    {
        $company_id = auth()->user()->company->id;
        $month = date('m');
        $year = date('Y');

        $count = self::where('company_id', $company_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        // Mendapatkan nomor urut dokumen
        $documentNumber = $count ? ($count + 1) : 1;

        // Format nomor dokumen dengan menambahkan bulan dan tahun
        $documentNumber = 'PR' . $year . '-' . $month . '-' . str_pad($documentNumber, 4, '0', STR_PAD_LEFT);

        return $documentNumber;
    }
}
