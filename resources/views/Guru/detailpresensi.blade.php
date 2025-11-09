@extends('layouts.guru.app')

@section('content')

    <div x-data="{ showModal: false }">

        <div class="flex items-center space-x-4 mb-6">
            <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
            <h2 class="text-3xl font-bold text-slate-800">Nama Mata Pelajaran</h2>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            
            <div class="grid grid-cols-12 gap-4 mb-6 px-4 text-lg font-bold text-blue-600">
                <div class="col-span-2">Pertemuan</div>
                <div class="col-span-5 text-center">Materi</div>
                <div class="col-span-3 text-center">Waktu Absensi</div>
                <div class="col-span-2 text-right">Detail</div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 rounded-2xl py-4 px-6">
                    <div class="col-span-2">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-slate-900 font-bold rounded-lg text-lg">1</span>
                    </div>
                    <div class="col-span-5 text-center font-medium text-slate-900">Nama Materi</div>
                    <div class="col-span-3 flex items-center justify-center space-x-2 font-medium text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        <span>08:00-9:30</span>
                    </div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.presensi_mapel_detail') }}" class="text-blue-700 hover:text-blue-900">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-700">
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 rounded-2xl py-4 px-6">
                    <div class="col-span-2">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-slate-900 font-bold rounded-lg text-lg">2</span>
                    </div>
                    <div class="col-span-5 text-center font-medium text-slate-900">Nama Materi</div>
                    <div class="col-span-3 flex items-center justify-center space-x-2 font-medium text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        <span>08:00-9:30</span>
                    </div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.presensi_mapel_detail') }}" class="text-blue-700 hover:text-blue-900">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-700">
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 rounded-2xl py-4 px-6">
                    <div class="col-span-2">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-slate-900 font-bold rounded-lg text-lg">3</span>
                    </div>
                    <div class="col-span-5 text-center font-medium text-slate-900">Nama Materi</div>
                    <div class="col-span-3 flex items-center justify-center space-x-2 font-medium text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        <span>08:00-9:30</span>
                    </div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.presensi_mapel_detail') }}" class="text-blue-700 hover:text-blue-900">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-700">
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 rounded-2xl py-4 px-6">
                    <div class="col-span-2">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-slate-900 font-bold rounded-lg text-lg">4</span>
                    </div>
                    <div class="col-span-5 text-center font-medium text-slate-900">Nama Materi</div>
                    <div class="col-span-3 flex items-center justify-center space-x-2 font-medium text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        <span>08:00-9:30</span>
                    </div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.presensi_mapel_detail') }}" class="text-blue-700 hover:text-blue-900">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-700">
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 rounded-2xl py-4 px-6">
                    <div class="col-span-2">
                        <span class="flex items-center justify-center w-10 h-10 bg-blue-100 text-slate-900 font-bold rounded-lg text-lg">5</span>
                    </div>
                    <div class="col-span-5 text-center font-medium text-slate-900">Nama Materi</div>
                    <div class="col-span-3 flex items-center justify-center space-x-2 font-medium text-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                        </svg>
                        <span>08:00-9:30</span>
                    </div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.presensi_mapel_detail') }}" class="text-blue-700 hover:text-blue-900">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-9 h-9 text-blue-700">
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end mt-8">
            <button @click="showModal = true" class="flex items-center space-x-2 bg-blue-400 text-white font-bold px-6 py-3 rounded-full hover:bg-blue-500 transition-colors shadow-md shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Absensi</span>
            </button>
        </div>


        <div
            x-show="showModal"
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/75 transition-opacity"
                @click="showModal = false"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div
                    x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-visible rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-md p-8"
                    @click.stop
                >
                    <h3 class="text-2xl font-bold text-blue-600 mb-6">Membuat Absensi</h3>

                    <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="nama_materi" class="block text-base font-bold text-slate-900 mb-2">Nama Materi</label>
                            <textarea id="nama_materi" name="nama_materi" rows="3" class="w-full border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 resize-none" placeholder="Maksimal 40 Karakter"></textarea>
                        </div>

                        <div class="relative" x-data="datepicker()">
                            <label class="block text-base font-bold text-slate-900 mb-2">Tanggal</label>
                            
                            <div @click="showDatepicker = !showDatepicker" class="w-full border-2 border-blue-300 rounded-xl py-3 px-4 flex items-center justify-between cursor-pointer hover:border-blue-400">
                                <span x-text="formattedDate" class="text-slate-500 font-medium"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 transition-transform" :class="{'rotate-180': showDatepicker}">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="hidden" name="tanggal" x-model="selectedDate">

                            <div 
                                x-show="showDatepicker" 
                                @click.away="showDatepicker = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-10 mt-2 w-[320px] bg-white rounded-3xl shadow-[0_10px_40px_-15px_rgba(0,0,0,0.2)] p-6 right-0"
                            >
                                <div class="flex items-center justify-between mb-6">
                                    <button type="button" @click="prevMonth" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                                    </button>
                                    <div class="text-lg font-bold text-slate-800" x-text="monthNames[month] + ' ' + year"></div>
                                    <button type="button" @click="nextMonth" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-7 text-center mb-2">
                                    <template x-for="(day, index) in dayNames" :key="index">
                                        <div class="text-sm font-medium text-slate-400" x-text="day"></div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-7 text-center gap-y-1">
                                    <template x-for="blank in blankDays" :key="blank">
                                        <div class="p-1"></div>
                                    </template>
                                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                        <div class="p-1">
                                            <div
                                                @click="getDateValue(date)"
                                                x-text="date"
                                                class="w-8 h-8 mx-auto flex items-center justify-center text-sm font-semibold rounded-lg cursor-pointer transition-colors"
                                                :class="{
                                                    'bg-blue-100 text-blue-600 border border-blue-500': isSelectedDate(date),
                                                    'text-slate-700 hover:bg-slate-100': !isSelectedDate(date)
                                                }"
                                            ></div>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="button" @click="showDatepicker = false" class="bg-blue-400 text-white font-bold text-sm px-6 py-2 rounded-xl hover:bg-blue-500 transition-colors">
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-base font-bold text-slate-900 mb-2">Waktu</label>
                            <div class="flex items-center space-x-4">
                                <div>
                                    <div class="text-blue-400 text-xs font-medium mb-1 ml-1">Dibuka</div>
                                    <input type="text" name="waktu_dibuka" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-xl py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="pt-6">
                                    <div class="w-5 h-1.5 bg-blue-600 rounded-full"></div>
                                </div>
                                <div>
                                    <div class="text-blue-400 text-xs font-medium mb-1 ml-1">Ditutup</div>
                                    <input type="text" name="waktu_ditutup" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-xl py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-blue-400 text-white font-bold text-lg py-3.5 rounded-full hover:bg-blue-500 transition-colors shadow-md shadow-blue-200">
                                Buat Absensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div> <script>
        // Mendefinisikan konstanta di luar agar bersih
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const DAY_NAMES = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su']; // Sesuai gambar: Senin pertama

        function datepicker() {
            return {
                showDatepicker: false,
                selectedDate: '', // Format YYYY-MM-DD (untuk backend)
                formattedDate: 'DD/MM/YEAR', // Format DD/MM/YYYY (untuk tampilan)
                month: '',
                year: '',
                no_of_days: [],
                blankDays: [],
                dayNames: DAY_NAMES,
                monthNames: MONTH_NAMES,

                init() {
                    let today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.getNoOfDays();
                },

                // Pengecekan apakah tanggal ini yang dipilih
                isSelectedDate(date) {
                    const d = new Date(this.year, this.month, date);
                    return this.selectedDate === d.toISOString().split('T')[0];
                },

                // Saat tanggal diklik
                getDateValue(date) {
                    let selectedDate = new Date(this.year, this.month, date);
                    // Set nilai hidden input (format YYYY-MM-DD untuk backend)
                    this.selectedDate = selectedDate.toISOString().split('T')[0]; 
                    // Set nilai tampilan (format DD/MM/YYYY)
                    this.formattedDate = ('0' + date).slice(-2) + '/' + ('0' + (this.month + 1)).slice(-2) + '/' + this.year;
                },

                // Menghitung hari dalam sebulan dan hari kosong
                getNoOfDays() {
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    let dayOfWeek = new Date(this.year, this.month).getDay(); // 0=Minggu, 1=Senin, ...
                    
                    // Sesuaikan 'dayOfWeek' agar Senin = 0, Minggu = 6
                    let adjustedDay = (dayOfWeek === 0) ? 6 : dayOfWeek - 1;
                    
                    let blankdaysArray = [];
                    for (var i = 1; i <= adjustedDay; i++) {
                        blankdaysArray.push(i);
                    }

                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }

                    this.blankDays = blankdaysArray;
                    this.no_of_days = daysArray;
                },

                prevMonth() {
                    if (this.month == 0) {
                        this.month = 11;
                        this.year--;
                    } else {
                        this.month--;
                    }
                    this.getNoOfDays();
                },

                nextMonth() {
                    if (this.month == 11) {
                        this.month = 0;
                        this.year++;
                    } else {
                        this.month++;
                    }
                    this.getNoOfDays();
                }
            }
        }

        // Daftarkan komponen datepicker ke Alpine
        document.addEventListener('alpine:init', () => {
            Alpine.data('datepicker', datepicker);
        });
    </script>

    <style>
        /* Sembunyikan elemen x-cloak saat Alpine sedang loading */
        [x-cloak] { 
            display: none !important; 
        }
    </style>

@endsection