@extends('layout.app')

@section('content')
<div class="flex justify-center py-44 px-60">
    <div class="grid gap-2 w-full">
        <div class="bg-slate-800 rounded-lg p-4">
            <div class="my-2 mb-0">
                <div class="">
                    <ion-icon name="id-card-outline" class="text-white p-2 size-14 rounded"></ion-icon>
                </div>
                <div class="flex justify-between">
                    <div class="grid p-2 self-center">
                        <div class="">
                            <h1 class="text-[3rem] text-white font-bold">{{ $user->name }}</h1>
                        </div>
                        <div class="">
                            <h1 class="text-white italic">{{ $user->email }}</h1>
                        </div>
                        <div class="">
                            <h1 class="text-white italic">{{ $user->no_telp }}</h1>
                        </div>
                    </div>
                    <div class="w-36 h-36">
                        @if ($user->gambar)
                            <img
                                src="{{ asset('storage/' . $user->gambar) }}"
                                alt="Profile Picture"
                                class="h-full w-full rounded-full object-cover"
                            />
                        @else
                            <div 
                                class="h-full w-full rounded-full bg-gray-500 flex items-center justify-center text-white text-3xl font-bold"
                                style="font-family: Arial, sans-serif;"
                            >
                                {{ strtoupper(collect(explode(' ', $user->name))->map(fn($word) => $word[0])->join('')) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>            
        </div>

        <!-- Card Keamanan dan Privasi -->
        <div class="bg-slate-800 rounded-md p-4">
            <span class="text-white text-3xl font-bold mb-6">Histori Donasi :)</span>
            <div class="mt-6">
                <!-- Histori Transaksi -->
                <div class="bg-slate-700 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-white text-lg">Lihat histori transaksi</span>
                        <ion-icon name="chevron-down-outline" class="text-white size-8 cursor-pointer" onclick="toggleDropdown()"></ion-icon>
                    </div>
                    <div id="dropdown-histori" class="hidden mt-4">
                        @forelse ($donasi as $donasiItem)
                        <div class="p-3 bg-slate-900 rounded mt-2">
                            <span class="text-white text-sm">{{ $donasiItem->waktu_donasi->format('d M Y, H:i') }}</span>
                            <div class="text-white">
                                Jumlah: Rp {{ number_format($donasiItem->jumlah, 2, ',', '.') }}
                            </div>
                            <div class="text-gray-400 text-sm italic">{{ $donasiItem->notes }}</div>
                        </div>
                        @empty
                        <div class="text-gray-400 text-sm mt-2">
                            Belum ada transaksi.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-md p-4">
            <span class="text-white text-3xl font-bold mb-6">Keamanan dan Privasi</span>
            <div class="mt-6">
                <!-- Edit Profil -->
                <div class="flex justify-between items-center hover:bg-slate-900 hover:rounded p-2">
                    <a href="{{ route('profile.edit') }}" class="flex gap-2 items-center">
                        <ion-icon name="pencil-outline" class="bg-slate-400 p-2 size-5 rounded"></ion-icon>
                        <span class="text-white text-lg">Edit Profil</span>
                    </a>
                    <ion-icon name="chevron-forward-outline" class="text-white size-8"></ion-icon>
                </div>

                <!-- Ubah Kata Sandi -->
                <!-- <div class="flex justify-between items-center hover:bg-slate-900 hover:rounded p-2">
                    <a href="{{ route('password.edit') }}" class="flex gap-2 items-center">
                        <ion-icon name="lock-open-outline" class="bg-slate-400 p-2 size-5 rounded"></ion-icon>
                        <span class="text-white text-lg">Ubah Kata Sandi</span>
                    </a>
                    <ion-icon name="chevron-forward-outline" class="text-white size-8"></ion-icon>
                </div> -->
            </div>
        </div>
    </div>
</div>
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown-histori');
        dropdown.classList.toggle('hidden');
    }
</script>
@stop
