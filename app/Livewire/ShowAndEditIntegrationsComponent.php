<?php

namespace App\Livewire;

use App\Models\CartroverIntegration;
use App\Services\Digistore24Service;
use Livewire\Component;


class ShowAndEditIntegrationsComponent extends Component
{
    public $integrations;
    protected $digistoreService;

    public $products;

    public function mount(Digistore24Service $digistoreService)
    {
        $this->integrations = CartroverIntegration::all();
    }
    public function render()
    {
        return view('livewire.show-and-edit-integrations-component');
    }
}
