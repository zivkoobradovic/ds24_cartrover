<?php

namespace App\Livewire;

use App\Models\Vendor;
use Livewire\Component;
use App\Services\Digistore24Service;

class CreateCartroverIntegrationComponent extends Component
{

    public $ds24_user;
    public $ds24_api_key;
    public $name;
    public $ipn_pass;
    public $http_header;
    public $cr_api_user;
    public $cr_api_pass;
    public $products;
    public $selected_product_ids = [];
    public $saved_integration;
    // public $categories = [];
    // public $transactions = [];
    // public $timing = 'delayed';

    // public $display_ds24_api_input_field = true;
    // public $selected_products_display = false;
    public $connect_button = true;
    public $displayModal = false;
    public $allFieldsDisabled = false;

    protected $digistore24Service;

    public function render()
    {
        return view('livewire.create-cartrover-integration-component');
    }


    public function connectToDigistore24(Digistore24Service $digistore24Service)
    {
        $this->validate([
            'ds24_api_key' => 'required|unique:cartrover_integrations,ds24_api_key'
        ], [
            'ds24_api_key.required' => 'Digistore24 API Key required.'
        ]);

        $this->digistore24Service = $digistore24Service->connectToAPI($this->ds24_api_key);

        try {
            $this->ds24_user = $this->digistore24Service->getUserInfo();
            $this->products = $this->digistore24Service->listProducts();
            $this->connect_button = false;

            session()->flash('success', 'Connected to Digistore24 API successfully.');
        } catch (\App\Libraries\DigistoreApiException  $e) {
            session()->flash('connection-error', $e->getMessage());
        }
    }

    public function removeProduct($productId)
    {
        $this->selected_product_ids = array_values(
            array_diff($this->selected_product_ids, [$productId])
        );
    }

    public function saveIntegration()
    {
        // Save the integration details to the database or perform any other necessary actions
        $this->validate([
            'name' => 'required',
            'ipn_pass' => 'required',
            'http_header' => 'required',
            'cr_api_user' => 'required',
            'cr_api_pass' => 'required',
            'selected_product_ids' => 'required|array|min:1'
        ], [
            'name.required' => 'Name is required.',
            'ipn_pass.required' => 'IPN Password is required.',
            'http_header.required' => 'HTTP Header is required.',
            'cr_api_user.required' => 'CartRover API User is required.',
            'cr_api_pass.required' => 'CartRover API Password is required.',
            'selected_product_ids.required' => 'At least one product must be selected.'
        ]);

        // dd($this->ds24_user);
        $vendor = Vendor::where('name', $this->ds24_user->user_name)->first();

        if ($vendor == null) {
            $vendor = Vendor::create([
                'name' => $this->ds24_user->user_name,
            ]);
        }

        $auth = $this->http_header . ':' . $this->ipn_pass;
        $ipn_url = route('ipn_url', ['vendor' => $this->ds24_user->user_name, 'auth' => rtrim(strtr(base64_encode($auth), '+/', '-_'), '=')]);

        $this->saved_integration = $vendor->cartroverIntegration()->create([
            'name' => $this->name,
            'vendor_id' => $this->ds24_user->user_name,
            'ipn_pass' => $this->ipn_pass,
            'auth' => $auth,
            'products' => $this->selected_product_ids,
            'http_header' => $this->http_header,
            'ds24_api_key' => $this->ds24_api_key,
            'cr_api_user' => $this->cr_api_user,
            'cr_api_pass' => $this->cr_api_pass,
            'ipn_url' => $ipn_url,
        ]);

        $this->displayModal = true;
        session()->flash('success', 'The ' . $this->saved_integration->name .  ' Integration Setup Created successfully!');
    }

    public function closeModal()
    {
        $this->allFieldsDisabled = true;
        $this->connect_button = false;
        $this->displayModal = false;
    }
}
