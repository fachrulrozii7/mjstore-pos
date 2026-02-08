@extends('layouts.app')

@section('content')
<div class="space-y-12" x-data="{ showUserModal: false }">
    
    <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Karyawan MJ Store</h2>
                <p class="text-gray-500 font-medium mt-1">Total terdapat {{ $users->count() }} staf yang terdaftar di sistem</p>
            </div>
            <button @click="showUserModal = true" class="group flex items-center gap-3 bg-blue-600 text-white px-8 py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:bg-gray-900 hover:shadow-xl hover:shadow-blue-200 transition-all duration-300 active:scale-95">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Karyawan
            </button>
        </div>

        <div class="overflow-hidden border border-gray-50 rounded-[2rem]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Identitas Karyawan</th>
                        <th class="py-6 px-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Email</th>
                        <th class="py-6 px-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Jabatan</th>
                        <th class="py-6 px-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="py-6 px-6 text-xs font-black text-gray-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="group hover:bg-blue-50/30 transition-all duration-300">
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex-shrink-0 flex items-center justify-center text-gray-700 font-black text-sm shadow-sm border border-white group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white transition-all duration-500">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 leading-none mb-1">{{ $user->name }}</p>
                                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">ID: #00{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <span class="text-sm font-semibold text-gray-600">{{ $user->email }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $user->role == 'root' ? 'bg-gray-900 text-white' : 'bg-blue-100 text-blue-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            @if($user->is_active == '1')
                            <span class="inline-flex items-center gap-1.5 text-green-600 font-black text-xs uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-red-400 font-black text-xs uppercase tracking-wider">
                                <span class="w-2 h-2 rounded-full bg-red-300"></span>
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="py-5 px-6 text-center">
                            <button class="p-2 hover:bg-white rounded-xl transition-all shadow-none hover:shadow-sm text-gray-400 hover:text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Hak Akses Sistem</h2>
                <p class="text-gray-500 font-medium mt-1">Kelola batasan menu untuk setiap level pengguna</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-2xl">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach(['cashier', 'manager'] as $r)
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                
                <div class="relative p-8 bg-white rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col h-full">
                    <form action="{{ route('users.permissions') }}" method="POST" class="flex flex-col h-full">
                        @csrf
                        <input type="hidden" name="role" value="{{ $r }}">
                        
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-blue-700 flex items-center justify-center text-white shadow-lg">
                                <span class="font-black text-lg">{{ strtoupper(substr($r, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="font-black text-xl text-gray-900 capitalize">{{ $r }}</h3>
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Access Level</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-3 flex-1">
                            @foreach($availableMenus as $menu)
                            <label x-data="{ checked: {{ isset($permissions[$r]) && in_array($menu, $permissions[$r]->menu_key) ? 'true' : 'false' }} }" 
                                :class="checked ? 'bg-blue-50 border-blue-200 ring-2 ring-blue-100' : 'bg-gray-50 border-transparent hover:border-gray-200'"
                                class="group/item flex items-center justify-between p-4 rounded-2xl cursor-pointer border transition-all duration-300">
                                
                                <div class="flex items-center gap-4">
                                    <div :class="checked ? 'bg-green-600 text-white' : 'bg-white text-gray-400 shadow-inner'" class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors duration-300">
                                        @if($menu == 'dashboard')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        @elseif($menu == 'pos')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold tracking-wide transition-colors duration-300" :class="checked ? 'text-blue-900' : 'text-gray-500'">{{ strtoupper($menu) }}</span>
                                </div>

                                <div class="relative">
                                    <input type="checkbox" name="menu_keys[]" value="{{ $menu }}" 
                                        @change="checked = $el.checked"
                                        {{ isset($permissions[$r]) && in_array($menu, $permissions[$r]->menu_key) ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-green-600 peer-checked:bg-green-600 flex items-center justify-center transition-all">
                                        <svg class="w-4 h-4 text-white scale-0 peer-checked:scale-100 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full mt-8 bg-gray-500 text-white py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-200 transition-all duration-300 active:scale-95">
                            Update Permissions
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection