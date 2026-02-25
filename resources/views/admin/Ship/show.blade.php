@extends('admin.dashboard')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                            Detail Pesanan</h1>
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('admin.ship.shipment') }}" class="text-muted text-hover-primary">List
                                    Shipping</a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                            </li>
                            <li class="breadcrumb-item text-muted">Detail Pesanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="d-flex flex-column flex-lg-row">

                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 mb-lg-0">
                        <!--begin::Card-->
                        <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary"
                            data-kt-sticky-offset="{default: false, lg: '200px'}"
                            data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto"
                            data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Order Summary</h2>
                                </div>

                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0 fs-6">
                                <!--begin::Section-->
                                <div class="mb-7">
                                    <!--begin::Details-->
                                    <div class="d-flex flex-wrap flex-stack mb-2">
                                        <span class="text-gray-800 fw-semibold me-2">Order ID:</span>
                                        <span class="text-gray-800 fw-bold">{{ $invoice->kode_inv }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-stack mb-2">
                                        <span class="text-gray-800 fw-semibold me-2">Order Date:</span>
                                        <span
                                            class="text-gray-800 fw-bold">{{ $invoice->created_at->format('d M Y') }}</span>
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Seperator-->
                                <div class="separator separator-dashed mb-7"></div>
                                <!--end::Seperator-->
                                <!--begin::Section-->
                                <div class="mb-7">
                                    <h5 class="mb-4">Customer Details</h5>
                                    <!--begin::Details-->
                                    <div class="d-flex flex-wrap flex-stack mb-2">
                                        <span class="text-gray-800 fw-semibold me-2">Name:</span>
                                        <span class="text-gray-800 fw-bold">{{ $invoice->transaksi->address?->nama }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap flex-stack mb-2">
                                        <span class="text-gray-800 fw-semibold me-2">Email:</span>
                                        <a href="mailto:{{ $invoice->transaksi->user->email }}"
                                            class="fw-bold">{{ $invoice->transaksi->user->email }}</a>
                                    </div>
                                    <div class="d-flex flex-wrap flex-stack mb-2">
                                        <span class="text-gray-800 fw-semibold me-2">Phone:</span>
                                        <span
                                            class="text-gray-800 fw-bold">{{ $invoice->transaksi->user->userDetail->no_hp ?? '-' }}</span>
                                    </div>
                                    <!--end::Details-->
                                </div>
                                <!--end::Section-->
                                <!--begin::Seperator-->
                                <div class="separator separator-dashed mb-7"></div>
                                <!--end::Seperator-->
                                <!--begin::Section-->
                                <div class="mb-10">
                                    <h5 class="mb-4">Shipping Address</h5>
                                    <address class="mb-0 fs-6">
                                        {{ $invoice->transaksi->address?->alamat ?? '-' }}<br>
                                        {{ $invoice->transaksi->address?->city ?? '-' }},
                                        {{ $invoice->transaksi->address?->zip_code ?? '-' }}<br>
                                        {{ $invoice->transaksi->address?->country?->country_name ?? '-' }}
                                    </address>
                                </div>
                                <!--end::Section-->
                                <!--begin::Actions-->

                                <!--end::Actions-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid ms-lg-15">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <div class="card-header">

                                <div class="card-title">
                                    <h2>Order #{{ $invoice->kode_inv }}
                                        @if ($invoice->transaksi && $invoice->transaksi->is_request == 1)
                                            <span class="badge badge-warning">Request</span>
                                        @endif
                                    </h2>
                                </div>

                                @if ($invoice->transaksi->status == 8)
                                    <div class="card-toolbar">

                                        @switch($invoice->transaksi->expedisi)
                                            {{-- ================= FEDEx ================= --}}
                                            @case('FedEx')
                                                @if (isset($fedexShipment) && $fedexShipment->label_url)
                                                    <a href="{{ route('admin.fedex.print', $invoice->id) }}" target="_blank"
                                                        class="btn btn-sm btn-success">
                                                        Print AWB
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#fedexShippingModal">
                                                        (+) Atur Pengiriman
                                                    </button>
                                                @endif
                                            @break

                                            {{-- ================= LION PARCEL ================= --}}
                                            @case('LionParcel')
                                                @if (isset($lionparcelShipment) && !empty($lionShipment->tracking_number))
                                                    <button id="printAwbButton" data-invoice-id="{{ $invoice->id }}"
                                                        class="btn btn-sm btn-success">
                                                        Print AWB
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#lionParcelShippingModal">
                                                        (+) Atur Pengiriman
                                                    </button>
                                                @endif
                                            @break
                                        @endswitch

                                    </div>
                                @endif
                            </div>
                            <div class="card-body pt-0">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($invoice->no_resi && $invoice->jasa_ekspedisi)
                                    <div class="mb-5">
                                        <h4>Shipping Information</h4>
                                        <p>Tracking Number: {{ $invoice->no_resi }}</p>
                                        <p>Shipping Service: {{ $invoice->jasa_ekspedisi }}</p>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <thead>
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-175px">Product</th>
                                                <th class="min-w-100px">SKU</th>
                                                <th class="min-w-70px text-end">Qty</th>
                                                <th class="min-w-100px text-end">Unit Price</th>
                                                <th class="min-w-100px text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-600">
                                            @foreach ($invoice->transaksi->details as $detail)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <a href="#" class="symbol symbol-50px">
                                                                <span class="symbol-label"
                                                                    style="background-image:url('{{ asset('backend/assets/media/produk/' . $detail->produk->gambar) }}');"></span>
                                                            </a>
                                                            <div class="ms-5">
                                                                <a href="#"
                                                                    class="fw-bold text-gray-600 text-hover-primary">{{ $detail->produk->nama_produk }}</a>
                                                                <div class="fs-7 text-muted">
                                                                    @if ($detail->jenis)
                                                                        <span>Shape: {{ $detail->jenis->jenis }}</span>
                                                                    @endif
                                                                    @if ($detail->ukuran)
                                                                        <br><span>Size:
                                                                            {{ $detail->ukuran->nama_ukuran }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $detail->produk->kode_produk }}</td>
                                                    <td class="text-end">{{ $detail->qty }}</td>
                                                    <td class="text-end">
                                                        {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                        {{ number_format($detail->harga, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                        {{ number_format($detail->harga * $detail->qty, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" class="text-end">Subtotal</td>
                                                <td class="text-end">
                                                    {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                    {{ number_format($invoice->total, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end">Shipping Cost</td>
                                                <td class="text-end">
                                                    {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                    {{ number_format($invoice->transaksi->shipping_cost, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="fs-3 text-gray-900 text-end">Grand Total</td>
                                                <td class="text-gray-900 fs-3 fw-bolder text-end">
                                                    {{ $invoice->transaksi->shipping_currency == 'IDR' ? 'Rp' : '$' }}
                                                    {{ number_format($invoice->total + $invoice->transaksi->shipping_cost, $invoice->transaksi->shipping_currency == 'IDR' ? 0 : 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Content-->
                </div>
            </div>
        </div>
    </div>

    <!--begin::Modal - Success-->
    <div class="modal fade" id="kt_modal_success" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ session('success') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal - Success-->
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.getElementById('printAwbButton');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    const invoiceId = this.getAttribute('data-invoice-id');
                    const url = `/admin/shipping/lion/update-status-after-print/${invoiceId}`;

                    // Disable button to prevent multiple clicks
                    this.disabled = true;
                    this.innerText = 'Processing...';

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const newTab = window.open(data.print_url, '_blank');

                                // Check if the new tab was blocked
                                if (!newTab || newTab.closed || typeof newTab.closed == 'undefined') {
                                    alert(
                                        'Status has been updated, but the print tab was blocked by your browser. Please allow pop-ups for this site.'
                                    );
                                }

                                // Reload the page after a short delay to ensure the new tab has opened
                                setTimeout(() => {
                                    location.reload();
                                }, 500);

                            } else {
                                // Show error message
                                const errorAlert =
                                    `<div class="alert alert-danger">${data.error || 'An unknown error occurred.'}</div>`;
                                document.querySelector('.card-body.pt-0').insertAdjacentHTML(
                                    'afterbegin', errorAlert);
                                // Re-enable the button if there was an error
                                printButton.disabled = false;
                                printButton.innerText = 'Print AWB';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            const errorAlert =
                                `<div class="alert alert-danger">A network error occurred. Please try again.</div>`;
                            document.querySelector('.card-body.pt-0').insertAdjacentHTML('afterbegin',
                                errorAlert);
                            // Re-enable the button on network error
                            printButton.disabled = false;
                            printButton.innerText = 'Print AWB';
                        });
                });
            }
        });
    </script>
    @if (session('success'))
        <script>
            var successModal = new bootstrap.Modal(document.getElementById('kt_modal_success'));
            successModal.show();
        </script>
    @endif
    @if (session('print_url'))
        <script>
            window.open("{{ session('print_url') }}", '_blank');
        </script>
    @endif
@endsection

@include('admin.Ship.partials._fedex_shipping_modal')
@include('admin.Ship.partials._lionparcel_shipping_modal')
