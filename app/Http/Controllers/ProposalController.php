<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Customer;
use App\CustomField;
use App\Invoice;
use App\InvoiceProduct;
use App\Mail\ProposalSend;
use App\Milestone;
use App\Products;
use App\ProductService;
use App\ProductServiceCategory;
use App\Proposal;
use App\ProposalProduct;
use App\Task;
use App\Utility;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage proposal'))
        {

            $customer = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('All', '');

            $status = Proposal::$statues;

            $query = Proposal::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->customer))
            {
                $query->where('id', '=', $request->customer);
            }
            if(!empty($request->issue_date))
            {
                $date_range = explode(' - ', $request->issue_date);
                $query->whereBetween('issue_date', $date_range);
            }

            if(!empty($request->status))
            {
                $query->where('status', '=', $request->status);
            }
            $proposals = $query->get();

            return view('proposal.index', compact('proposals', 'customer', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create($customerId)
    {
        if(\Auth::user()->can('create proposal'))
        {
            $customFields    = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'proposal')->get();
            $proposal_number = \Auth::user()->proposalNumberFormat($this->proposalNumber());
            $customers       = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');
            $category = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->where('type', 1)->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $product_services->prepend('--', '');

            return view('proposal.create', compact('customers', 'proposal_number', 'product_services', 'category', 'customFields', 'customerId'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function customer(Request $request)
    {
        $customer = Customer::where('id', '=', $request->id)->first();

        return view('proposal.customer_detail', compact('customer'));
    }

    public function product(Request $request)
    {

        $data['product'] = $product = ProductService::find($request->product_id);

        $data['unit']    = (!empty($product->unit())) ? $product->unit()->name : '';
        $data['taxRate'] = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;

        $data['taxes'] = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;

        $salePrice           = $product->sale_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create proposal'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'customer_id' => 'required',
                                   'issue_date' => 'required',
                                   'category_id' => 'required',
                                   'items' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $status = Proposal::$statues;

            $proposal                 = new Proposal();
            $proposal->proposal_id    = $this->proposalNumber();
            $proposal->customer_id    = $request->customer_id;
            $proposal->status         = 0;
            $proposal->issue_date     = $request->issue_date;
            $proposal->category_id    = $request->category_id;
            $proposal->discount_apply = isset($request->discount_apply) ? 1 : 0;
            $proposal->created_by     = \Auth::user()->creatorId();
            $proposal->save();
            CustomField::saveData($proposal, $request->customField);
            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
                $proposalProduct              = new ProposalProduct();
                $proposalProduct->proposal_id = $proposal->id;
                $proposalProduct->product_id  = $products[$i]['item'];
                $proposalProduct->quantity    = $products[$i]['quantity'];
                $proposalProduct->tax         = $products[$i]['tax'];
                $proposalProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $proposalProduct->price       = $products[$i]['price'];
                $proposalProduct->description = $products[$i]['description'];
                $proposalProduct->save();
            }

            return redirect()->route('proposal.index', $proposal->id)->with('success', __('Proposal successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($ids)
    {

        if(\Auth::user()->can('edit proposal'))
        {
            $id              = Crypt::decrypt($ids);
            $proposal        = Proposal::find($id);
            $proposal_number = \Auth::user()->proposalNumberFormat($proposal->proposal_id);
            $customers       = Customer::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $category        = ProductServiceCategory::where('created_by', \Auth::user()->creatorId())->where('type', 1)->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $proposal->customField = CustomField::getData($proposal, 'proposal');
            $customFields          = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'proposal')->get();

            $items = [];
            foreach($proposal->items as $proposalItem)
            {
                $itemAmount               = $proposalItem->quantity * $proposalItem->price;
                $proposalItem->itemAmount = $itemAmount;
                $proposalItem->taxes      = Utility::tax($proposalItem->tax);
                $items[]                  = $proposalItem;
            }

            return view('proposal.edit', compact('customers', 'product_services', 'proposal', 'proposal_number', 'category', 'customFields', 'items'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, Proposal $proposal)
    {
        if(\Auth::user()->can('edit proposal'))
        {
            if($proposal->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'customer_id' => 'required',
                                       'issue_date' => 'required',
                                       'category_id' => 'required',
                                       'items' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('proposal.index')->with('error', $messages->first());
                }
                $proposal->customer_id    = $request->customer_id;
                $proposal->issue_date     = $request->issue_date;
                $proposal->category_id    = $request->category_id;
                $proposal->discount_apply = isset($request->discount_apply) ? 1 : 0;
                $proposal->save();
                CustomField::saveData($proposal, $request->customField);
                $products = $request->items;

                for($i = 0; $i < count($products); $i++)
                {
                    $proposalProduct = ProposalProduct::find($products[$i]['id']);
                    if($proposalProduct == null)
                    {
                        $proposalProduct              = new ProposalProduct();
                        $proposalProduct->proposal_id = $proposal->id;

                    }

                    if(isset($products[$i]['item']))
                    {
                        $proposalProduct->product_id = $products[$i]['item'];
                    }

                    $proposalProduct->quantity    = $products[$i]['quantity'];
                    $proposalProduct->tax         = $products[$i]['tax'];
                    $proposalProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $proposalProduct->price       = $products[$i]['price'];
                    $proposalProduct->description = $products[$i]['description'];
                    $proposalProduct->save();
                }

                return redirect()->route('proposal.index', $proposal->id)->with('success', __('Proposal successfully updated.'));

            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function proposalNumber()
    {
        $latest = Proposal::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->proposal_id + 1;
    }

    public function show($ids)
    {
        if(\Auth::user()->can('show proposal'))
        {
            $id       = Crypt::decrypt($ids);
            $proposal = Proposal::find($id);

            if($proposal->created_by == \Auth::user()->creatorId())
            {
                $customer = $proposal->customer;
                $iteams   = $proposal->items;
                $status   = Proposal::$statues;

                $proposal->customField = CustomField::getData($proposal, 'proposal');
                $customFields          = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'proposal')->get();

                return view('proposal.view', compact('proposal', 'customer', 'iteams', 'status', 'customFields'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Proposal $proposal)
    {
        if(\Auth::user()->can('delete proposal'))
        {
            if($proposal->created_by == \Auth::user()->creatorId())
            {
                $proposal->delete();
                ProposalProduct::where('proposal_id', '=', $proposal->id)->delete();

                return redirect()->route('proposal.index')->with('success', __('Proposal successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function productDestroy(Request $request)
    {

        if(\Auth::user()->can('delete proposal product'))
        {
            ProposalProduct::where('id', '=', $request->id)->delete();

            return redirect()->back()->with('success', __('Proposal product successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerProposal(Request $request)
    {
        if(\Auth::user()->can('manage customer proposal'))
        {

            $status = Proposal::$statues;

            $query = Proposal::where('customer_id', '=', \Auth::user()->id)->where('status', '!=', '0')->where('created_by', \Auth::user()->creatorId());

            if(!empty($request->issue_date))
            {
                $date_range = explode(' - ', $request->issue_date);
                $query->whereBetween('issue_date', $date_range);
            }

            if(!empty($request->status))
            {
                $query->where('status', '=', $request->status);
            }
            $proposals = $query->get();

            return view('proposal.index', compact('proposals', 'status'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function customerProposalShow($ids)
    {
        if(\Auth::user()->can('show proposal'))
        {
            $proposal_id = \Crypt::decrypt($ids);
            $proposal    = Proposal::where('id', $proposal_id)->first();
            if($proposal->created_by == \Auth::user()->creatorId())
            {
                $customer = $proposal->customer;
                $iteams   = $proposal->items;

                return view('proposal.view', compact('proposal', 'customer', 'iteams'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function sent($id)
    {
        if(\Auth::user()->can('send proposal'))
        {
            $proposal            = Proposal::where('id', $id)->first();
            $proposal->send_date = date('Y-m-d');
            $proposal->status    = 1;
            $proposal->save();

            $customer           = Customer::where('id', $proposal->customer_id)->first();
            $proposal->name     = !empty($customer) ? $customer->name : '';
            $proposal->proposal = \Auth::user()->proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            try
            {
                Mail::to($customer->email)->send(new ProposalSend($proposal));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->back()->with('success', __('Proposal successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function resent($id)
    {
        if(\Auth::user()->can('send proposal'))
        {
            $proposal = Proposal::where('id', $id)->first();

            $customer           = Customer::where('id', $proposal->customer_id)->first();
            $proposal->name     = !empty($customer) ? $customer->name : '';
            $proposal->proposal = \Auth::user()->proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            try
            {
                Mail::to($customer->email)->send(new ProposalSend($proposal));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->back()->with('success', __('Proposal successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function shippingDisplay(Request $request, $id)
    {
        $proposal = Proposal::find($id);

        if($request->is_display == 'true')
        {
            $proposal->shipping_display = 1;
        }
        else
        {
            $proposal->shipping_display = 0;
        }
        $proposal->save();

        return redirect()->back()->with('success', __('Shipping address status successfully changed.'));
    }

    public function duplicate($proposal_id)
    {
        if(\Auth::user()->can('duplicate proposal'))
        {
            $proposal                       = Proposal::where('id', $proposal_id)->first();
            $duplicateProposal              = new Proposal();
            $duplicateProposal->proposal_id = $this->proposalNumber();
            $duplicateProposal->customer_id = $proposal['customer_id'];
            $duplicateProposal->issue_date  = date('Y-m-d');
            $duplicateProposal->send_date   = null;
            $duplicateProposal->category_id = $proposal['category_id'];
            $duplicateProposal->status      = 0;
            $duplicateProposal->created_by  = $proposal['created_by'];
            $duplicateProposal->save();

            if($duplicateProposal)
            {
                $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
                foreach($proposalProduct as $product)
                {
                    $duplicateProduct              = new ProposalProduct();
                    $duplicateProduct->proposal_id = $duplicateProposal->id;
                    $duplicateProduct->product_id  = $product->product_id;
                    $duplicateProduct->quantity    = $product->quantity;
                    $duplicateProduct->tax         = $product->tax;
                    $duplicateProduct->discount    = $product->discount;
                    $duplicateProduct->price       = $product->price;
                    $duplicateProduct->save();
                }
            }

            return redirect()->back()->with('success', __('Proposal duplicate successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function convert($proposal_id)
    {
        if(\Auth::user()->can('convert invoice'))
        {
            $proposal             = Proposal::where('id', $proposal_id)->first();
            $proposal->is_convert = 1;
            $proposal->save();

            $convertInvoice              = new Invoice();
            $convertInvoice->invoice_id  = $this->invoiceNumber();
            $convertInvoice->customer_id = $proposal['customer_id'];
            $convertInvoice->issue_date  = date('Y-m-d');
            $convertInvoice->due_date    = date('Y-m-d');
            $convertInvoice->send_date   = null;
            $convertInvoice->category_id = $proposal['category_id'];
            $convertInvoice->status      = 0;
            $convertInvoice->created_by  = $proposal['created_by'];
            $convertInvoice->save();

            $proposal->converted_invoice_id = $convertInvoice->id;
            $proposal->save();

            if($convertInvoice)
            {
                $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
                foreach($proposalProduct as $product)
                {
                    $duplicateProduct             = new InvoiceProduct();
                    $duplicateProduct->invoice_id = $convertInvoice->id;
                    $duplicateProduct->product_id = $product->product_id;
                    $duplicateProduct->quantity   = $product->quantity;
                    $duplicateProduct->tax        = $product->tax;
                    $duplicateProduct->discount   = $product->discount;
                    $duplicateProduct->price      = $product->price;
                    $duplicateProduct->save();
                }
            }

            return redirect()->back()->with('success', __('Proposal to invoice convert successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function statusChange(Request $request, $id)
    {
        $status           = $request->status;
        $proposal         = Proposal::find($id);
        $proposal->status = $status;
        $proposal->save();

        return redirect()->back()->with('success', __('Proposal status changed successfully.'));
    }

    public function previewProposal($template, $color)
    {
        $objUser  = \Auth::user();
        $settings = Utility::settings();
        $proposal = new Proposal();

        $customer                   = new \stdClass();
        $customer->email            = '<Email>';
        $customer->shipping_name    = '<Customer Name>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_state   = '<State>';
        $customer->shipping_city    = '<City>';
        $customer->shipping_phone   = '<Customer Phone Number>';
        $customer->shipping_zip     = '<Zip>';
        $customer->shipping_address = '<Address>';
        $customer->billing_name     = '<Customer Name>';
        $customer->billing_country  = '<Country>';
        $customer->billing_state    = '<State>';
        $customer->billing_city     = '<City>';
        $customer->billing_phone    = '<Customer Phone Number>';
        $customer->billing_zip      = '<Zip>';
        $customer->billing_address  = '<Address>';

        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for($i = 1; $i <= 3; $i++)
        {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach($taxes as $k => $tax)
            {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if(array_key_exists('Tax ' . $k, $taxesData))
                {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                }
                else
                {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[]       = $item;
        }

        $proposal->proposal_id = 1;
        $proposal->issue_date  = date('Y-m-d H:i:s');
        $proposal->due_date    = date('Y-m-d H:i:s');
        $proposal->itemData    = $items;

        $proposal->totalTaxPrice = 60;
        $proposal->totalQuantity = 3;
        $proposal->totalRate     = 300;
        $proposal->totalDiscount = 10;
        $proposal->taxesData     = $taxesData;

        $proposal->customField = [];
        $customFields          = [];

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = Utility::getFontColor($color);

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo');
        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png'));

        return view('proposal.templates.' . $template, compact('proposal', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'customFields'));
    }

    public function proposal($proposal_id)
    {

        $settings   = Utility::settings();
        $proposalId = Crypt::decrypt($proposal_id);
        $proposal   = Proposal::where('id', $proposalId)->first();

        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $proposal->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $customer = $proposal->customer;

        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        foreach($proposal->items as $product)
        {
            $item              = new \stdClass();
            $item->name        = !empty($product->product) ? $product->product->name : '';
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes = \Utility::tax($product->tax);

            $itemTaxes = [];
            if(!empty($item->tax))
            {
                foreach($taxes as $tax)
                {
                    $taxPrice      = \Utility::taxRate($tax->rate, $item->price, $item->quantity);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name']  = $tax->name;
                    $itemTax['rate']  = $tax->rate . '%';
                    $itemTax['price'] = \App\Utility::priceFormat($settings, $taxPrice);
                    $itemTaxes[]      = $itemTax;


                    if(array_key_exists($tax->name, $taxesData))
                    {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    }
                    else
                    {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }
                $item->itemTax = $itemTaxes;
            }
            else
            {
                $item->itemTax = [];
            }
            $items[] = $item;
        }

        $proposal->itemData      = $items;
        $proposal->totalTaxPrice = $totalTaxPrice;
        $proposal->totalQuantity = $totalQuantity;
        $proposal->totalRate     = $totalRate;
        $proposal->totalDiscount = $totalDiscount;
        $proposal->taxesData     = $taxesData;
        $proposal->customField   = CustomField::getData($proposal, 'proposal');
        $customFields            = [];
        if(!empty(\Auth::user()))
        {
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'proposal')->get();
        }

        //Set your logo
        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo');
        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo.png'));

        if($proposal)
        {
            $color      = '#' . $settings['proposal_color'];
            $font_color = Utility::getFontColor($color);

            return view('proposal.templates.' . $settings['proposal_template'], compact('proposal', 'color', 'settings', 'customer', 'img', 'font_color', 'customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function saveProposalTemplateSettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['proposal_template']) && (!isset($post['proposal_color']) || empty($post['proposal_color'])))
        {
            $post['proposal_color'] = "ffffff";
        }

        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                             $data,
                                                                                                                                             $key,
                                                                                                                                             \Auth::user()->creatorId(),
                                                                                                                                         ]
            );
        }

        return redirect()->back()->with('success', __('Proposal Setting updated successfully'));
    }

    function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function items(Request $request)
    {
        $items = ProposalProduct::where('proposal_id', $request->proposal_id)->where('product_id', $request->product_id)->first();

        return json_encode($items);
    }
}
