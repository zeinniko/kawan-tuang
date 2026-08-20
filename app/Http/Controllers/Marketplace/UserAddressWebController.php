<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Services\AddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAddressWebController extends Controller
{
    public function __construct(protected AddressService $addressService) {}

    /**
     * Halaman List Alamat Pengiriman
     */
    public function index(Request $request): View
    {
        $addresses = $this->addressService->getUserAddresses($request->user());

        return view('marketplace.addresses.index', compact('addresses'));
    }

    /**
     * Halaman Form Tambah Alamat
     */
    public function create(): View
    {
        $address = new UserAddress();
        return view('marketplace.addresses.form', compact('address'));
    }

    /**
     * Simpan Alamat Baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'full_address'    => 'required|string|max:500',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'is_primary'      => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        $this->addressService->createAddress($request->user(), $validated);

        return redirect()->route('profile.addresses.index')->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }

    /**
     * Halaman Form Edit Alamat
     */
    public function edit(Request $request, UserAddress $address): View
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('marketplace.addresses.form', compact('address'));
    }

    /**
     * Update Alamat
     */
    public function update(Request $request, UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'label'           => 'required|string|max:50',
            'recipient_name'  => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'full_address'    => 'required|string|max:500',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'is_primary'      => 'nullable|boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary');

        $this->addressService->updateAddress($request->user(), $address, $validated);

        return redirect()->route('profile.addresses.index')->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    /**
     * Hapus Alamat
     */
    public function destroy(Request $request, UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $this->addressService->deleteAddress($address);

        return redirect()->route('profile.addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set Alamat Utama
     */
    public function setPrimary(Request $request, UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403, 'Akses ditolak.');
        }

        $this->addressService->setPrimaryAddress($request->user(), $address);

        return redirect()->route('profile.addresses.index')->with('success', 'Alamat utama berhasil diperbarui.');
    }
}