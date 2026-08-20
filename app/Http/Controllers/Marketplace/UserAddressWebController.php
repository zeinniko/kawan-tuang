<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAddressWebController extends Controller
{
    /**
     * Halaman List Alamat Pengiriman
     */
    public function index(): View
    {
        $response = InternalApiService::get('addresses');
        $rawAddresses = $response['data'] ?? [];

        $addresses = collect($rawAddresses)->map(fn ($item) => (object) $item);

        return view('marketplace.addresses.index', compact('addresses'));
    }

    /**
     * Halaman Form Tambah Alamat
     */
    public function create(): View
    {
        $address = (object) [
            'id'              => null,
            'label'           => '',
            'recipient_name'  => '',
            'recipient_phone' => '',
            'full_address'    => '',
            'notes'           => '',
            'postal_code'     => '',
            'latitude'        => -6.2088,
            'longitude'       => 106.8456,
            'is_primary'      => false,
            'is_edit'         => false,
        ];

        return view('marketplace.addresses.form', compact('address'));
    }

    /**
     * Simpan Alamat Baru via InternalApiService
     */
    public function store(Request $request): RedirectResponse
    {
        $payload = [
            'label'           => (string) $request->input('label'),
            'recipient_name'  => (string) $request->input('recipient_name'),
            'recipient_phone' => (string) $request->input('recipient_phone'),
            'full_address'    => (string) $request->input('full_address'),
            'notes'           => (string) $request->input('notes', ''),
            'postal_code'     => (string) $request->input('postal_code', '10110'),
            'latitude'        => (float) $request->input('latitude', -6.2088),
            'longitude'       => (float) $request->input('longitude', 106.8456),
            'is_primary'      => $request->has('is_primary') || $request->boolean('is_primary'),
        ];

        $response = InternalApiService::post('addresses', $payload);

        if (isset($response['errors'])) {
            return back()->withErrors($response['errors'])->withInput()->with('error', 'Gagal menambahkan alamat. Periksa kembali form Anda.');
        }

        if (empty($response['data'])) {
            return back()->with('error', $response['message'] ?? 'Gagal menambahkan alamat pengiriman.')->withInput();
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('success', $response['message'] ?? 'Alamat pengiriman berhasil ditambahkan.');
    }

    /**
     * Halaman Form Edit Alamat
     */
    public function edit($id): View
    {
        $response = InternalApiService::get("addresses/{$id}");
        $data = $response['data'] ?? null;

        if (!$data) {
            abort(404, 'Alamat tidak ditemukan.');
        }

        $address = (object) [
            'id'              => $data['id'] ?? $id,
            'label'           => $data['label'] ?? '',
            'recipient_name'  => $data['recipient_name'] ?? $data['receiver_name'] ?? '',
            'recipient_phone' => $data['recipient_phone'] ?? $data['receiver_phone'] ?? '',
            'full_address'    => $data['full_address'] ?? '',
            'notes'           => $data['notes'] ?? '',
            'postal_code'     => $data['postal_code'] ?? '10110',
            'latitude'        => $data['latitude'] ?? -6.2088,
            'longitude'       => $data['longitude'] ?? 106.8456,
            'is_primary'      => $data['is_primary'] ?? false,
            'is_edit'         => true,
        ];

        return view('marketplace.addresses.form', compact('address'));
    }

    /**
     * Update Alamat via InternalApiService
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $payload = [
            'label'           => (string) $request->input('label'),
            'recipient_name'  => (string) $request->input('recipient_name'),
            'recipient_phone' => (string) $request->input('recipient_phone'),
            'full_address'    => (string) $request->input('full_address'),
            'notes'           => (string) $request->input('notes', ''),
            'postal_code'     => (string) $request->input('postal_code', '10110'),
            'latitude'        => (float) $request->input('latitude', -6.2088),
            'longitude'       => (float) $request->input('longitude', 106.8456),
            'is_primary'      => $request->has('is_primary') || $request->boolean('is_primary'),
        ];

        $response = InternalApiService::put("addresses/{$id}", $payload);

        if (isset($response['errors'])) {
            return back()->withErrors($response['errors'])->withInput()->with('error', 'Gagal memperbarui alamat. Periksa kembali inputan Anda.');
        }

        if (empty($response['data'])) {
            return back()->with('error', $response['message'] ?? 'Gagal memperbarui alamat pengiriman.')->withInput();
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('success', $response['message'] ?? 'Alamat pengiriman berhasil diperbarui.');
    }

    /**
     * Hapus Alamat via InternalApiService
     */
    public function destroy($id): RedirectResponse
    {
        $response = InternalApiService::delete("addresses/{$id}");

        if (isset($response['errors']) || (isset($response['message']) && $response['message'] === 'Akses ditolak.')) {
            return back()->with('error', $response['message'] ?? 'Gagal menghapus alamat.');
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('success', $response['message'] ?? 'Alamat berhasil dihapus.');
    }

    /**
     * Set Alamat Utama via InternalApiService
     */
    public function setPrimary($id): RedirectResponse
    {
        $response = InternalApiService::patch("addresses/{$id}/set-primary");

        if (isset($response['errors']) || (isset($response['message']) && $response['message'] === 'Akses ditolak.')) {
            return back()->with('error', $response['message'] ?? 'Gagal memperbarui alamat utama.');
        }

        return redirect()
            ->route('profile.addresses.index')
            ->with('success', $response['message'] ?? 'Alamat utama berhasil diperbarui.');
    }
}