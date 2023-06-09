<?php

namespace App\Mail\Approval;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorRFQAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $vendor;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor     = $vendor->fresh();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('mail.approval.vendor_rfq_access')
            ->subject('Access RFQ '.$this->vendor->company->name.' in p2p.haricar.com')
            ->with([
                'name'      => $this->vendor->name,
                'company'   => $this->vendor->company->name,
                'url'       => 'https://p2p.haricar.com/vendor-access/' . $this->vendor->slug,
            ]);
    }
}
