<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Transaction
        </h2>
    </x-slot>
    <div>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form method="post" action="{{ route('transactions.store') }}">
                    @csrf
                    <div class="shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <label for="title" class="block font-medium text-sm text-gray-700">Jenis</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio" name="type" value="pemasukan">
                                    <span class="ml-2">Pemasukan</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" class="form-radio" name="type" value="pengeluaran">
                                    <span class="ml-2">Pengeluaran</span>
                                </label>
                                @error('type')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-5">
                                <div class="form-group">
                                    <label for="category" class="block font-medium text-sm text-gray-700">Category</label>
                                    <select name="category_id" id="category" class="form-input rounded-md shadow-sm mt-1 block w-full"">
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="mt-5">
                                <label for="nominal" class="block font-medium text-sm text-gray-700">Nominal</label>
                                <input type="text" name="nominal" id="nominal" type="text"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    value="{{ old('nominal', '') }}" />
                                @error('nominal')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
                                <div class="form-input rounded-md shadow-sm mt-1 block w-full mt-5">
                                    <div class="mb-5 ui-datepicker">
                                        <label for="datepicker" class="block font-medium text-sm text-gray-700">Select Date</label>
                                        <div class="relative">
                                            <input type="hidden" name="date" x-ref="date" id="inputDate">
                                            <input
                                                type="text"
                                                readonly
                                                x-model="datepickerValue"
                                                @click="showDatepicker = !showDatepicker"
                                                @keydown.escape="showDatepicker = false"
                                                class="w-full pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                                                placeholder="Select date">

                                                <div class="absolute top-0 right-0 px-3 py-2">
                                                    <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>

                                                <div
                                                    class="bg-white mt-12 rounded-lg shadow p-4 absolute top-0 left-0"
                                                    style="width: 17rem"
                                                    x-show.transition="showDatepicker"
                                                    @click.away="showDatepicker = false">

                                                    <div class="flex justify-between items-center mb-2">
                                                        <div>
                                                            <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                                            <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                                        </div>
                                                        <div>
                                                            <button
                                                                type="button"
                                                                class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                                                                :class="{'cursor-not-allowed opacity-25': month == 0 }"
                                                                :disabled="month == 0 ? true : false"
                                                                @click="month--; getNoOfDays()">
                                                                <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                                </svg>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                                                                :class="{'cursor-not-allowed opacity-25': month == 11 }"
                                                                :disabled="month == 11 ? true : false"
                                                                @click="month++; getNoOfDays()">
                                                                <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap mb-3 -mx-1">
                                                        <template x-for="(day, index) in DAYS" :key="index">
                                                            <div style="width: 14.26%" class="px-1">
                                                                <div
                                                                    x-text="day"
                                                                    class="text-gray-800 font-medium text-center text-xs"></div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <div class="flex flex-wrap -mx-1">
                                                        <template x-for="blankday in blankdays">
                                                            <div
                                                                style="width: 14.28%"
                                                                class="text-center border p-1 border-transparent text-sm"
                                                            ></div>
                                                        </template>
                                                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                                            <div style="width: 14.28%" class="px-1 mb-1">
                                                                <div
                                                                    @click="getDateValue(date)"
                                                                    x-text="date"
                                                                    class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100"
                                                                    :class="{'bg-blue-500 text-white': isToday(date) == true, 'text-gray-700 hover:bg-blue-200': isToday(date) == false }"
                                                                ></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-5">
                                <label for="catatan" class="block font-medium text-sm text-gray-700">Catatan</label>
                                <input type="text" name="note" id="catatan" type="text"
                                    class="form-input rounded-md shadow-sm mt-1 block w-full"
                                    value="{{ old('catatan', '') }}" />
                                @error('catatan')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    .ui-datepicker { z-index: 10000 !important; }
</style>
<script>
          const MONTH_NAMES = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          const DAYS = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

          function app() {
              return {
                  showDatepicker: false,
                  datepickerValue: '',

                  month: '',
                  year: '',
                  no_of_days: [],
                  blankdays: [],
                  days: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],

                  initDate() {
                      let today = new Date();
                      this.month = today.getMonth();
                      this.year = today.getFullYear();
                      this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
                  },

                  isToday(date) {
                      const today = new Date();
                      const d = new Date(this.year, this.month, date);

                      return today.toDateString() === d.toDateString() ? true : false;
                  },

                  getDateValue(date) {
                      let selectedDate = new Date(this.year, this.month, date);
                      this.datepickerValue = selectedDate.toDateString();

                      this.$refs.date.value = selectedDate.getFullYear() +"-"+ ('0'+ selectedDate.getMonth()).slice(-2) +"-"+ ('0' + selectedDate.getDate()).slice(-2);

                      console.log(this.$refs.date.value);

                      this.showDatepicker = false;
                  },

                  getNoOfDays() {
                      let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

                      // find where to start calendar day of week
                      let dayOfWeek = new Date(this.year, this.month).getDay();
                      let blankdaysArray = [];
                      for ( var i=1; i <= dayOfWeek; i++) {
                          blankdaysArray.push(i);
                      }

                      let daysArray = [];
                      for ( var i=1; i <= daysInMonth; i++) {
                          daysArray.push(i);
                      }

                      this.blankdays = blankdaysArray;
                      this.no_of_days = daysArray;
                  }
              }
          }
      </script>
