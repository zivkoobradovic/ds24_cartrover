<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vendor;

class CreateVendorComponent extends Component
{
    public $vendor_name;

    public function render()
    {
        return view('livewire.create-vendor-component');
    }

    public function createVendor()
    {
        $this->validate([
            'vendor_name' => 'required',
        ], [
            'vendor_name.required' => 'Vendor Name required.',
        ]);

        $vendor = Vendor::create([
            'name' => $this->vendor_name
        ]);
        $this->reset('vendor_name');
        session()->flash('success', 'Vendor ' . $vendor->name . ' Created successfully!');
    }
}
