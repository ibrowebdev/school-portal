<?php

namespace App\Policies;

use App\Models\InvoiceCustomerName;
use App\Models\User;

class InvoiceCustomerNamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InvoiceCustomerName $invoiceCustomerName): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InvoiceCustomerName $invoiceCustomerName): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InvoiceCustomerName $invoiceCustomerName): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InvoiceCustomerName $invoiceCustomerName): bool
    {
        return $user->can('manage-invoices');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InvoiceCustomerName $invoiceCustomerName): bool
    {
        return $user->can('manage-invoices');
    }
}
