{{-- resources/views/pages/antrian_pengerjaan/detailManajemenAntrianPengerjaan.blade.php --}}
{{--
    TODO Backend:
    - Kirim $antrian (Eloquent model) dari Controller ke view ini
    - Endpoint ubah status: PUT/PATCH /api/antrian-pengerjaan/{id}/status
    - Endpoint hapus: DELETE /api/antrian-pengerjaan/{id}
    - $antrian->suku_cadang → relasi ke tabel antrian_suku_cadang
--}}
@extends('layouts.master')

@section('title', 'Detail Antrian Pengerjaan')
@section('title_header', 'Antrian Pengerjaan')

@section('content')
<div class="block w-full space-y-6">

    {{-- Card Judul --}}
    <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm w-full">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#EAF2FF] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-[#213F5C]">Detail Mobil Masuk</h1>
            </div>
            <a href="{{ route('antrian-pengerjaan.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-[#213F5C] font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke List
            </a>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="grid grid-cols-12 gap-6 pb-10 w-full">

        {{-- Kolom Kiri --}}
        <div class="col-span-9 space-y-6">

            {{-- Section 1: Informasi Pemilik Kendaraan --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Pemilik Kendaraan</h2>
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="w-36 text-[13px] text-gray-400 font-semibold shrink-0">Nama Lengkap</span>
                        <span id="detailName" class="text-[13px] font-bold text-[#213F5C]">-</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="w-36 text-[13px] text-gray-400 font-semibold shrink-0">Nomor Telepon</span>
                        <span id="detailPhone" class="text-[13px] font-bold text-[#213F5C]">-</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="w-36 text-[13px] text-gray-400 font-semibold shrink-0">Alamat</span>
                        <span id="detailAddress" class="text-[13px] font-bold text-[#213F5C]">-</span>
                    </div>
                </div>
            </div>

            {{-- Section 2: Informasi Mobil Pelanggan --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16H6l-2-6h15l-1 4M3 11l1-4h14" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Informasi Mobil Pelanggan</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2] p-5">
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-bold text-[#213F5C]">Model Mobil :</span>
                            <span id="detailCarModel" class="text-[13px] text-[#213F5C]">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-bold text-[#213F5C]">Km Masuk :</span>
                            <span id="detailKmMasuk" class="text-[13px] text-[#213F5C]">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-bold text-[#213F5C]">Kode Mesin :</span>
                            <span id="detailEngineCode" class="text-[13px] text-[#213F5C]">-</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[13px] font-bold text-[#213F5C]">Nomor Polisi :</span>
                            <span id="detailLicensePlate" class="text-[13px] text-[#213F5C]">-</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Penggunaan Suku Cadang --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-100">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h2 class="text-[16px] font-bold text-[#213F5C]">Penggunaan Suku Cadang</h2>
                </div>
                <div id="sukuCadangContainer" class="p-6 space-y-3">
                    <p id="sukuCadangEmpty" class="text-[13px] text-gray-400 text-center py-4">Belum ada suku cadang yang digunakan.</p>
                </div>
            </div>

        </div>{{-- end kolom kiri --}}

        {{-- Kolom Kanan --}}
        <div class="col-span-3 space-y-4">

            {{-- Quick Info --}}
            <div class="bg-white rounded-[20px] border border-[#E5E9F2] p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-gray-50">
                    <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="font-bold text-[#213F5C] text-[15px]">Quick Info</h3>
                </div>

                {{-- Created By --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Created By</p>
                    <div class="flex items-center gap-3 bg-[#F9FBFF] p-3 rounded-xl border border-[#E5E9F2]">
                        <div id="createdByInitial" class="user-initial-box w-9 h-9 rounded-full bg-[#1273EB] flex items-center justify-center text-white font-bold text-[12px]">?</div>
                        <p id="createdByName" class="text-[13px] font-bold text-[#213F5C] truncate">-</p>
                    </div>
                </div>

                {{-- Created Date --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Created Date</p>
                    <p id="createdAt" class="text-[13px] font-bold text-[#213F5C]">-</p>
                </div>

                {{-- Last Updated --}}
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-1">Last Updated</p>
                    <p id="updatedAt" class="text-[13px] font-bold text-[#213F5C]">-</p>
                </div>

                {{-- ── Ubah Status — Custom Dropdown sesuai desain Container.png ── --}}
                <div class="pt-1">
                    <p class="text-[11px] font-bold text-[#1273EB] mb-2 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#1273EB] inline-block"></span>
                        Ubah Status
                    </p>
                    <div class="relative" id="statusDropdownWrapper">
                        {{-- Trigger --}}
                        <button type="button" id="statusDropdownTrigger"
                            onclick="toggleStatusDropdown()"
                            class="w-full px-4 py-3 rounded-xl border-2 font-bold text-[14px] outline-none flex items-center justify-between cursor-pointer transition-all">
                            <span id="statusDropdownLabel">Pengecekan</span>
                            <svg id="statusDropdownChevron" class="w-4 h-4 transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        {{-- Dropdown list --}}
                        <div id="statusDropdownList"
                            class="hidden absolute z-50 left-0 right-0 mt-2 bg-white border border-[#E5E9F2] rounded-2xl shadow-xl overflow-hidden">
                            <div id="statusDropdownItems">
                                {{-- Diisi oleh JS --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                {{-- Proses Pembayaran --}}
                <button type="button" id="btnProsesPembayaran"
                    class="w-full flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-[15px] transition-all"
                    onclick="handleProsesPembayaran()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Proses Pembayaran
                </button>

                <a id="btnEditData" href="#"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#1273EB] text-white rounded-xl font-bold text-[15px] hover:bg-[#0E59B8] transition-all shadow-lg shadow-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit Data
                </a>

                <button type="button" id="btnHapus"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FF4D4D] text-white rounded-xl font-bold text-[15px] hover:bg-[#E53E3E] transition-all"
                    onclick="handleHapus()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Data
                </button>

                <a href="{{ route('antrian-pengerjaan.index') }}"
                    class="w-full flex items-center justify-center gap-2 py-4 bg-[#FFF5F5] text-[#FF4D4D] border border-[#FFE0E0] rounded-xl font-bold text-[15px] hover:bg-[#FFEBEB] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
            </div>

        </div>{{-- end kolom kanan --}}
    </div>
</div>

{{-- ── Modal Edit Suku Cadang ─────────────────────────────────────────────── --}}
<div id="modalEditSC"
    class="fixed inset-0 z-[999] hidden items-center justify-center"
    style="background: rgba(0,0,0,0.35);">
    <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-md mx-4 p-7 space-y-5">
        <div class="flex items-center gap-3 pb-4 border-b border-[#F0F4FA]">
            <div class="w-9 h-9 rounded-xl bg-[#EAF2FF] flex items-center justify-center">
                <svg class="w-5 h-5 text-[#1273EB]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
            </div>
            <h3 class="text-[16px] font-bold text-[#213F5C]">Edit Suku Cadang</h3>
        </div>
        <div class="space-y-4">
            <input type="hidden" id="editSCId">
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Nama Barang</label>
                <input type="text" id="editSCNama" placeholder="Nama barang"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Harga</label>
                <input type="text" id="editSCHarga" placeholder="Contoh: Rp 700.000"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Jumlah</label>
                <input type="text" id="editSCJumlah" placeholder="Contoh: 1 pcs"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
            <div>
                <label class="block text-[14px] font-bold text-[#213F5C] mb-2">Supplier</label>
                <input type="text" id="editSCSupplier" placeholder="Nama supplier"
                    class="w-full px-5 py-3.5 bg-[#F9FBFF] border border-[#E5E9F2] rounded-xl outline-none focus:border-[#1273EB] focus:ring-2 focus:ring-[#1273EB]/10 transition-all text-[13px] text-[#213F5C] placeholder-gray-300">
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="button" onclick="simpanEditSC()"
                class="flex-1 py-3.5 bg-[#1273EB] text-white rounded-xl font-bold text-[13px] hover:bg-[#0E59B8] transition-all">
                Simpan Perubahan
            </button>
            <button type="button" onclick="tutupModalEditSC()"
                class="px-6 py-3.5 bg-white border border-[#E5E9F2] text-[#213F5C] rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all">
                Batal
            </button>
        </div>
    </div>
</div>

<style>
    /* Custom dropdown status — sesuai desain Container.png */
    .status-option-item {
        padding: 12px 16px;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        border-radius: 12px;
        margin: 4px 6px;
        transition: opacity 0.15s;
    }
    .status-option-item:hover {
        opacity: 0.85;
    }
    .status-option-pengecekan {
        background-color: #FFF8EC;
        color: #F59E0B;
        border: 1.5px solid #FDE68A;
    }
    .status-option-dalamproses {
        background-color: #EAF2FF;
        color: #1273EB;
        border: 1.5px solid #B1D3FF;
    }
    .status-option-selesai {
        background-color: #EDFBF3;
        color: #16A34A;
        border: 1.5px solid #A7F3D0;
    }
    #statusDropdownList {
        padding: 6px 0;
    }
</style>

<script>
    // ── Config warna per status ───────────────────────────────────────────────
    const statusConfig = {
        'Pengecekan'   : { border: '#FDE68A', bg: '#FFF8EC', text: '#F59E0B', chevron: '#F59E0B', optClass: 'status-option-pengecekan' },
        'Dalam Proses' : { border: '#B1D3FF', bg: '#EAF2FF', text: '#1273EB', chevron: '#1273EB', optClass: 'status-option-dalamproses' },
        'Selesai'      : { border: '#A7F3D0', bg: '#EDFBF3', text: '#16A34A', chevron: '#16A34A', optClass: 'status-option-selesai' },
    };

    const statusList        = ['Pengecekan', 'Dalam Proses', 'Selesai'];
    const btnPembayaran     = document.getElementById('btnProsesPembayaran');
    let   currentStatus     = 'Pengecekan';
    let   isStatusDropOpen  = false;

    // ── Render opsi status ────────────────────────────────────────────────────
    function renderStatusOptions() {
        const container = document.getElementById('statusDropdownItems');
        container.innerHTML = '';
        statusList.forEach(status => {
            const cfg = statusConfig[status];
            const div = document.createElement('div');
            div.className = `status-option-item ${cfg.optClass}`;
            div.textContent = status;
            div.addEventListener('click', () => selectStatus(status));
            container.appendChild(div);
        });
    }

    // ── Pilih status ──────────────────────────────────────────────────────────
    function selectStatus(newStatus) {
        if (newStatus === currentStatus) {
            closeStatusDropdown();
            return;
        }

        const prevStatus = currentStatus;
        currentStatus    = newStatus;

        applyStatusStyle(newStatus);
        closeStatusDropdown();

        // Simpan ke localStorage
        const id   = getAntrianId();
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        const idx  = list.findIndex(item => item.id === id);

        if (idx !== -1) {
            list[idx].status = newStatus;
            const now = new Date();
            list[idx].updated_at = now.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric',
            }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            localStorage.setItem('antrianList', JSON.stringify(list));
            document.getElementById('updatedAt').textContent = list[idx].updated_at;
        }

        Swal.fire({
            icon : 'success',
            title: 'Status diperbarui!',
            text : `Status berhasil diubah ke "${newStatus}"`,
            timer: 1800,
            showConfirmButton: false,
        });
    }

    // ── Toggle / Open / Close dropdown status ─────────────────────────────────
    function toggleStatusDropdown() {
        if (isStatusDropOpen) closeStatusDropdown();
        else openStatusDropdown();
    }

    function openStatusDropdown() {
        renderStatusOptions();
        document.getElementById('statusDropdownList').classList.remove('hidden');
        document.getElementById('statusDropdownList').style.display = 'block';
        document.getElementById('statusDropdownChevron').style.transform = 'rotate(180deg)';
        isStatusDropOpen = true;
    }

    function closeStatusDropdown() {
        document.getElementById('statusDropdownList').classList.add('hidden');
        document.getElementById('statusDropdownList').style.display = '';
        document.getElementById('statusDropdownChevron').style.transform = 'rotate(0deg)';
        isStatusDropOpen = false;
    }

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById('statusDropdownWrapper');
        if (wrapper && !wrapper.contains(e.target)) closeStatusDropdown();
    });

    // ── Apply style trigger button sesuai status ─────────────────────────────
    function applyStatusStyle(status) {
        const cfg     = statusConfig[status] || statusConfig['Pengecekan'];
        const trigger = document.getElementById('statusDropdownTrigger');
        const label   = document.getElementById('statusDropdownLabel');
        const chevron = document.getElementById('statusDropdownChevron');

        trigger.style.borderColor     = cfg.border;
        trigger.style.backgroundColor = cfg.bg;
        trigger.style.color           = cfg.text;
        chevron.style.color           = cfg.chevron;
        label.textContent             = status;

        updatePembayaranBtn(status);
    }

    // ── Update tombol proses pembayaran ───────────────────────────────────────
    function updatePembayaranBtn(status) {
        if (status === 'Selesai') {
            btnPembayaran.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.add('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = false;
        } else {
            btnPembayaran.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btnPembayaran.classList.remove('bg-[#16A34A]', 'text-white', 'hover:bg-[#15803D]', 'shadow-lg', 'shadow-green-100');
            btnPembayaran.disabled = true;
        }
    }

    // ── Ambil ID antrian ──────────────────────────────────────────────────────
    function getAntrianId() {
        const fromSession = sessionStorage.getItem('currentAntrianId');
        if (fromSession) return parseInt(fromSession, 10);
        const segments = window.location.pathname.split('/').filter(Boolean);
        const lastSeg  = segments[segments.length - 1];
        const parsed   = parseInt(lastSeg, 10);
        return isNaN(parsed) ? null : parsed;
    }

    function getAntrianById(id) {
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        return list.find(item => item.id === id) || null;
    }

    function saveAntrianList(list) {
        localStorage.setItem('antrianList', JSON.stringify(list));
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    // ── Render detail ke halaman ──────────────────────────────────────────────
    function renderDetail(antrian) {
        document.getElementById('detailName').textContent     = antrian.name          || '-';
        document.getElementById('detailPhone').textContent    = antrian.phone         || '-';
        document.getElementById('detailAddress').textContent  = antrian.address       || '-';
        document.getElementById('detailCarModel').textContent    = antrian.car_model     || '-';
        document.getElementById('detailKmMasuk').textContent     = antrian.km_masuk      || '-';
        document.getElementById('detailEngineCode').textContent  = antrian.engine_code   || '-';
        document.getElementById('detailLicensePlate').textContent = antrian.license_plate || '-';

        const createdByName = antrian.created_by || 'Unknown';
        document.getElementById('createdByName').textContent    = createdByName;
        document.getElementById('createdByInitial').textContent = createdByName.charAt(0).toUpperCase();
        document.getElementById('createdAt').textContent        = antrian.created_at || '-';
        document.getElementById('updatedAt').textContent        = antrian.updated_at || '-';

        const editUrl = "{{ route('antrian-pengerjaan.edit', ':id') }}".replace(':id', antrian.id);
        document.getElementById('btnEditData').setAttribute('href', editUrl);
        document.getElementById('btnEditData').addEventListener('click', (e) => {
            e.preventDefault();
            sessionStorage.setItem('currentAntrianId', antrian.id);
            window.location.href = editUrl;
        });

        currentStatus = antrian.status || 'Pengecekan';
        applyStatusStyle(currentStatus);

        renderSukuCadang(antrian.suku_cadang || []);
    }

    // ── Render suku cadang ────────────────────────────────────────────────────
    function renderSukuCadang(list) {
        const container = document.getElementById('sukuCadangContainer');
        const emptyEl   = document.getElementById('sukuCadangEmpty');

        Array.from(container.querySelectorAll('.sc-item')).forEach(el => el.remove());

        if (!list || list.length === 0) {
            emptyEl.style.display = 'block';
            return;
        }
        emptyEl.style.display = 'none';

        list.forEach(sc => {
            const div = document.createElement('div');
            div.className = 'sc-item flex items-center justify-between p-4 bg-[#F9FBFF] rounded-[14px] border border-[#E5E9F2]';
            div.setAttribute('data-scid', sc.id);
            div.innerHTML = `
                <div>
                    <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(sc.nama)}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">${escHtml(sc.deskripsi || '-')}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-[#213F5C]">${escHtml(sc.harga || '-')}</p>
                        <p class="text-[11px] text-gray-400">${escHtml(sc.jumlah || '-')} • ${escHtml(sc.tanggal || '-')}</p>
                        <p class="text-[11px] text-gray-400">Supplier: ${escHtml(sc.supplier || '-')}</p>
                    </div>
                    <button type="button"
                        onclick="konfirmasiEditSC(${sc.id})"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#EAF2FF] border border-[#B1D3FF] text-[#1273EB] hover:bg-[#D4E8FF] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                    </button>
                    <button type="button"
                        onclick="konfirmasiHapusSC(${sc.id})"
                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#FFF5F5] border border-[#FFE0E0] text-[#FF4D4D] hover:bg-[#FFEBEB] transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(div);
        });
    }

    // ── Konfirmasi & Hapus suku cadang ────────────────────────────────────────
    function konfirmasiHapusSC(scId) {
        Swal.fire({
            title             : 'Hapus Suku Cadang?',
            text              : 'Apakah kamu yakin ingin menghapus suku cadang ini?',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#FF4D4D',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Hapus!',
        }).then((result) => {
            if (!result.isConfirmed) return;

            const id   = getAntrianId();
            const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
            const idx  = list.findIndex(item => item.id === id);

            if (idx !== -1) {
                list[idx].suku_cadang = (list[idx].suku_cadang || []).filter(sc => sc.id !== scId);
                saveAntrianList(list);
                renderSukuCadang(list[idx].suku_cadang);

                Swal.fire({
                    icon : 'success',
                    title: 'Terhapus!',
                    text : 'Suku cadang berhasil dihapus.',
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        });
    }

    // ── Konfirmasi & Edit suku cadang ─────────────────────────────────────────
    function konfirmasiEditSC(scId) {
        Swal.fire({
            title             : 'Edit Suku Cadang?',
            text              : 'Apakah kamu yakin ingin mengedit suku cadang ini?',
            icon              : 'question',
            showCancelButton  : true,
            confirmButtonColor: '#1273EB',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Edit!',
        }).then((result) => {
            if (!result.isConfirmed) return;
            bukaModalEditSC(scId);
        });
    }

    // ── Buka modal edit suku cadang ───────────────────────────────────────────
    function bukaModalEditSC(scId) {
        const id      = getAntrianId();
        const antrian = getAntrianById(id);
        if (!antrian) return;

        const sc = (antrian.suku_cadang || []).find(s => s.id === scId);
        if (!sc) return;

        document.getElementById('editSCId').value       = scId;
        document.getElementById('editSCNama').value     = sc.nama     || '';
        document.getElementById('editSCHarga').value    = sc.harga    || '';
        document.getElementById('editSCJumlah').value   = sc.jumlah   || '';
        document.getElementById('editSCSupplier').value = sc.supplier || '';

        const modal = document.getElementById('modalEditSC');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModalEditSC() {
        const modal = document.getElementById('modalEditSC');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // ── Simpan hasil edit suku cadang ke localStorage ─────────────────────────
    function simpanEditSC() {
        const scId    = parseInt(document.getElementById('editSCId').value, 10);
        const newNama = document.getElementById('editSCNama').value.trim();
        const newHarga= document.getElementById('editSCHarga').value.trim();
        const newJml  = document.getElementById('editSCJumlah').value.trim();
        const newSup  = document.getElementById('editSCSupplier').value.trim();

        if (!newNama) {
            Swal.fire('Oops!', 'Nama barang wajib diisi!', 'warning');
            return;
        }

        const id   = getAntrianId();
        const list = JSON.parse(localStorage.getItem('antrianList') || '[]');
        const idx  = list.findIndex(item => item.id === id);

        if (idx !== -1) {
            const scIdx = (list[idx].suku_cadang || []).findIndex(sc => sc.id === scId);
            if (scIdx !== -1) {
                list[idx].suku_cadang[scIdx].nama     = newNama;
                list[idx].suku_cadang[scIdx].harga    = newHarga;
                list[idx].suku_cadang[scIdx].jumlah   = newJml;
                list[idx].suku_cadang[scIdx].supplier = newSup;

                const now = new Date();
                list[idx].updated_at = now.toLocaleDateString('id-ID', {
                    day: '2-digit', month: 'long', year: 'numeric',
                }) + ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                saveAntrianList(list);
                document.getElementById('updatedAt').textContent = list[idx].updated_at;
                renderSukuCadang(list[idx].suku_cadang);
            }
        }

        tutupModalEditSC();

        Swal.fire({
            icon : 'success',
            title: 'Berhasil!',
            text : 'Suku cadang berhasil diperbarui.',
            timer: 1500,
            showConfirmButton: false,
        });
    }

    // ── Hapus antrian dari localStorage ──────────────────────────────────────
    function handleHapus() {
        const id = getAntrianId();
        Swal.fire({
            title             : 'Hapus Data?',
            text              : 'Data antrian ini akan dihapus permanen.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#FF4D4D',
            cancelButtonText  : 'Batal',
            confirmButtonText : 'Ya, Hapus!',
        }).then(async (result) => {
            if (!result.isConfirmed) return;
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            if (id !== null) {
                let list = JSON.parse(localStorage.getItem('antrianList') || '[]');
                list = list.filter(item => item.id !== id);
                saveAntrianList(list);
            }
            await Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500, showConfirmButton: false });
            window.location.href = "{{ route('antrian-pengerjaan.index') }}";
        });
    }

    // ── Proses pembayaran ─────────────────────────────────────────────────────
    function handleProsesPembayaran() {
        Swal.fire({ icon: 'info', title: 'Proses Pembayaran', text: 'Fitur ini belum tersedia.' });
    }

    // ── Inisialisasi halaman ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const id = getAntrianId();

        if (id === null) {
            Swal.fire('Error', 'ID antrian tidak ditemukan!', 'error').then(() => {
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            });
            return;
        }

        const antrian = getAntrianById(id);
        if (!antrian) {
            Swal.fire('Error', 'Data antrian tidak ditemukan!', 'error').then(() => {
                window.location.href = "{{ route('antrian-pengerjaan.index') }}";
            });
            return;
        }

        renderDetail(antrian);

        const name = localStorage.getItem('user_name') || 'User';
        const role = localStorage.getItem('user_role') || 'Staff';
        document.querySelectorAll('.user-name-box').forEach(el => el.innerText = name);
        document.querySelectorAll('.user-role-box').forEach(el => el.innerText = role);
        document.querySelectorAll('.user-initial-box').forEach(el => {
            if (!el.id || el.id !== 'createdByInitial') {
                el.innerText = name.charAt(0).toUpperCase();
            }
        });
    });
</script>
@endsection