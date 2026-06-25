<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAdditionalCharges;
use App\Models\InvoiceCustomerName;
use App\Models\InvoiceDetails;
use App\Models\InvoiceDiscount;
use App\Models\InvoicePaymentDetails;
use App\Models\InvoiceTotalAmount;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /** index page */
    public function invoiceList()
    {
        $invoiceList = InvoiceCustomerName::with('totalAmount')->get();

        return view('invoices.list_invoices', compact('invoiceList'));
    }

    /** invoice paid page */
    public function invoicePaid()
    {
        return view('invoices.tab.paid_invoices');
    }

    /** invoice overdue page */
    public function invoiceOverdue()
    {
        return view('invoices.tab.overdue_invoices');
    }

    /** invoice draft */
    public function invoiceDraft()
    {
        return view('invoices.tab.draft_invoices');
    }

    /** recurring invoices */
    public function invoiceRecurring()
    {
        return view('invoices.tab.recurring_invoices');
    }

    /** invoice cancelled */
    public function invoiceCancelled()
    {
        return view('invoices.tab.cancelled_invoices');
    }

    /** invoice grid */
    public function invoiceGrid()
    {
        $invoiceList = InvoiceCustomerName::with('totalAmount')->get();

        return view('invoices.grid_invoice', compact('invoiceList'));
    }

    /** invoice add page */
    public function invoiceAdd()
    {
        $users = User::whereIn('type', [User::STUDENT, User::PARENT])->get();

        return view('invoices.invoice_add', compact('users'));
    }

    /** save record invoice */
    public function saveRecord(Request $request): JsonResponse
    {
        $request->validate([
            'customer_name'           => ['required', 'string'],
            'po_number'               => ['required', 'string'],
            'due_date'                => ['required', 'string'],
            'items.*'                 => ['required', 'string'],
            'category.*'              => ['required', 'string'],
            'quantity.*'              => ['required', 'string'],
            'price.*'                 => ['required', 'string'],
            'amount.*'                => ['required', 'string'],
            'discount.*'              => ['required', 'string'],
            'name_of_the_signatuaory' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $customerName = InvoiceCustomerName::create([
                'customer_name'     => $request->customer_name,
                'po_number'         => $request->po_number,
                'date'              => $request->date,
                'due_date'          => $request->due_date,
                'enable_tax'        => $request->enable_tax,
                'recurring_incoice' => $request->recurring_incoice,
                'by_month'          => $request->by_month,
                'month             ' => $request->month,
                'invoice_from'      => $request->invoice_from,
                'invoice_to'        => $request->invoice_to,
            ]);

            $invoiceId = $customerName->invoice_id;

            foreach ($request->items as $key => $values) {
                InvoiceDetails::create([
                    'invoice_id' => $invoiceId,
                    'items'      => $request->items[$key],
                    'category'   => $request->category[$key],
                    'quantity'   => $request->quantity[$key],
                    'price'      => $request->price[$key],
                    'amount'     => $request->amount[$key],
                    'discount'   => $request->discount[$key],
                ]);
            }

            $upload_sign = null;
            if ($request->hasFile('upload_sign')) {
                $file = $request->file('upload_sign');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload_sign'), $filename);
                $upload_sign = 'upload_sign/' . $filename;
            }

            InvoiceTotalAmount::create([
                'invoice_id'              => $invoiceId,
                'taxable_amount'          => $request->taxable_amount,
                'round_off'               => $request->round_off,
                'total_amount'            => $request->total_amount,
                'upload_sign'             => $upload_sign,
                'name_of_the_signatuaory' => $request->name_of_the_signatuaory,
            ]);

            if (!empty($request->service_charge)) {
                foreach ($request->service_charge as $key => $values) {
                    InvoiceAdditionalCharges::create([
                        'invoice_id'     => $invoiceId,
                        'service_charge' => $request->service_charge[$key],
                    ]);
                }
            }

            if (!empty($request->offer_new)) {
                foreach ($request->offer_new as $key => $values) {
                    InvoiceDiscount::create([
                        'invoice_id' => $invoiceId,
                        'offer_new'  => $request->offer_new[$key],
                    ]);
                }
            }

            InvoicePaymentDetails::create([
                'invoice_id'               => $invoiceId,
                'account_holder_name'      => $request->account_holder_name,
                'bank_name'                => $request->bank_name,
                'ifsc_code'                => $request->ifsc_code,
                'account_number'           => $request->account_number,
                'add_terms_and_Conditions' => $request->add_terms_and_Conditions,
                'add_notes'                => $request->add_notes,
            ]);

            DB::commit();

            return response()->json(['message' => 'Invoice created successfully!', 'redirect' => route('invoice/list/page')]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create invoice. Please try again.'], 500);
        }
    }

    /** invoice edit */
    public function invoiceEdit($invoice_id)
    {
        $invoiceView = InvoiceCustomerName::with(['totalAmount', 'paymentDetails'])
            ->where('invoice_id', $invoice_id)
            ->firstOrFail();

        $users = User::all();
        $invoiceDetails    = InvoiceDetails::where('invoice_id', $invoice_id)->get();
        $AdditionalCharges = InvoiceAdditionalCharges::where('invoice_id', $invoice_id)->get();
        $InvoiceDiscount   = InvoiceDiscount::where('invoice_id', $invoice_id)->get();

        return view('invoices.invoice_edit', compact('invoiceView', 'users', 'invoiceDetails', 'AdditionalCharges', 'InvoiceDiscount'));
    }

    /** Update Record */
    public function updateRecord(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $customerName = InvoiceCustomerName::where('invoice_id', $request->invoice_id)->firstOrFail();
            $customerName->update([
                'customer_name'     => $request->customer_name,
                'po_number'         => $request->po_number,
                'date'              => $request->date,
                'due_date'          => $request->due_date,
                'enable_tax'        => $request->enable_tax,
                'recurring_incoice' => $request->recurring_incoice,
                'by_month'          => $request->by_month,
                'month             ' => $request->month,
                'invoice_from'      => $request->invoice_from,
                'invoice_to'        => $request->invoice_to,
            ]);

            // Simplified: in a real app, you'd likely delete and recreate details, or update existing ones based on ID.
            // For now, mirroring existing logic which assumes 1:1 mapping of input array to existing DB rows via firstOrFail.
            // (Note: This is brittle if row count changes, but matches previous behavior).
            $invoiceDetailsList = InvoiceDetails::where('invoice_id', $request->invoice_id)->get();
            foreach ($request->items as $key => $values) {
                if (isset($invoiceDetailsList[$key])) {
                    $invoiceDetailsList[$key]->update([
                        'items'    => $request->items[$key],
                        'category' => $request->category[$key],
                        'quantity' => $request->quantity[$key],
                        'price'    => $request->price[$key],
                        'amount'   => $request->amount[$key],
                        'discount' => $request->discount[$key],
                    ]);
                }
            }

            if (!empty($request->service_charge)) {
                $charges = InvoiceAdditionalCharges::where('invoice_id', $request->invoice_id)->get();
                foreach ($request->service_charge as $key => $values) {
                     if(isset($charges[$key])) {
                        $charges[$key]->update(['service_charge' => $request->service_charge[$key]]);
                     }
                }
            }

            if (!empty($request->offer_new)) {
                 $discounts = InvoiceDiscount::where('invoice_id', $request->invoice_id)->get();
                foreach ($request->offer_new as $key => $values) {
                     if(isset($discounts[$key])) {
                         $discounts[$key]->update(['offer_new' => $request->offer_new[$key]]);
                     }
                }
            }

            $paymentDetails = InvoicePaymentDetails::where('invoice_id', $request->invoice_id)->firstOrFail();
            $paymentDetails->update([
                'account_holder_name'      => $request->account_holder_name,
                'bank_name'                => $request->bank_name,
                'ifsc_code'                => $request->ifsc_code,
                'account_number'           => $request->account_number,
                'add_terms_and_Conditions' => $request->add_terms_and_Conditions,
                'add_notes'                => $request->add_notes,
            ]);

            $upload_sign = $request->upload_sign_unlink;
            if ($request->hasFile('upload_sign')) {
                if (!empty($upload_sign) && File::exists(public_path($upload_sign))) {
                    File::delete(public_path($upload_sign));
                }
                $file = $request->file('upload_sign');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('upload_sign'), $filename);
                $upload_sign = 'upload_sign/' . $filename;
            }

            $totalAmount = InvoiceTotalAmount::where('invoice_id', $request->invoice_id)->firstOrFail();
            $totalAmount->update([
                'taxable_amount'          => $request->taxable_amount,
                'round_off'               => $request->round_off,
                'total_amount'            => $request->total_amount,
                'upload_sign'             => $upload_sign,
                'name_of_the_signatuaory' => $request->name_of_the_signatuaory,
            ]);

            DB::commit();

            return response()->json(['message' => 'Invoice updated successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Invoice update failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update invoice.'], 500);
        }
    }

    /** Delete Record */
    public function deleteRecord(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            InvoiceCustomerName::where('invoice_id', $request->invoice_id)->delete();
            InvoiceDetails::where('invoice_id', $request->invoice_id)->delete();
            InvoiceTotalAmount::where('invoice_id', $request->invoice_id)->delete();
            InvoiceAdditionalCharges::where('invoice_id', $request->invoice_id)->delete();
            InvoiceDiscount::where('invoice_id', $request->invoice_id)->delete();
            InvoicePaymentDetails::where('invoice_id', $request->invoice_id)->delete();

            if (!empty($request->upload_sign) && File::exists(public_path($request->upload_sign))) {
                File::delete(public_path($request->upload_sign));
            }
            DB::commit();

            return response()->json(['message' => 'Record deleted successfully!', 'redirect' => route('invoice/list/page')]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Failed to delete record.'], 500);
        }
    }

    /** invoice view */
    public function invoiceView($invoice_id)
    {
        $invoiceView = InvoiceCustomerName::with(['totalAmount', 'paymentDetails'])
            ->where('invoice_id', $invoice_id)
            ->firstOrFail();
        $invoiceDetails = InvoiceDetails::where('invoice_id', $invoice_id)->get();

        return view('invoices.invoice_view', compact('invoiceView', 'invoiceDetails'));
    }

    /** invoice settings */
    public function invoiceSettings()
    {
        return view('invoices.settings.settings_invoices');
    }

    /** invoice settings tax */
    public function invoiceSettingsTax()
    {
        return view('invoices.settings.settings_tax');
    }

    /** invoice settings bank */
    public function invoiceSettingsBank()
    {
        return view('invoices.settings.settings_bank');
    }
}
