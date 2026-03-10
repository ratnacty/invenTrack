<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index as kategoriIndex } from '@/routes/kategori';
import { index as barangIndex } from '@/routes/barang';
import { Package, Tag, Users, Layers, Wallet, TrendingUp, AlertTriangle } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

interface KategoriOption { id: number; nama: string }
interface RecentBarang {
    id: number; nama: string; harga: number; stok: number;
    kategori: KategoriOption | null; created_at: string;
}
interface StokRendah { id: number; nama: string; stok: number; kategori: KategoriOption | null }

const props = defineProps<{
    stats: {
        totalBarang: number; totalKategori: number;
        totalUser: number; totalStok: number; totalNilai: number;
    };
    recentBarangs: RecentBarang[];
    stokRendah: StokRendah[];
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: dashboard() }];

function formatCurrency(value: number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
}
function formatNumber(value: number) {
    return new Intl.NumberFormat('id-ID').format(value);
}
function formatDate(value: string) {
    return new Date(value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div>
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <p class="text-sm text-muted-foreground mt-0.5">Selamat datang di sistem inventaris</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">

                <!-- Total Barang -->
                <Link :href="barangIndex().url"
                    class="relative overflow-hidden rounded-2xl p-5 hover:scale-[1.02] transition-transform cursor-pointer"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <div class="absolute right-3 top-3 opacity-20">
                        <Package class="h-12 w-12 text-white" />
                    </div>
                    <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Total Barang</p>
                    <p class="text-4xl font-extrabold text-white mt-1">{{ formatNumber(stats.totalBarang) }}</p>
                    <p class="text-xs text-blue-200 mt-1">item tercatat</p>
                </Link>

                <!-- Total Kategori -->
                <Link :href="kategoriIndex().url"
                    class="relative overflow-hidden rounded-2xl p-5 hover:scale-[1.02] transition-transform cursor-pointer"
                    style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
                    <div class="absolute right-3 top-3 opacity-20">
                        <Tag class="h-12 w-12 text-white" />
                    </div>
                    <p class="text-xs font-medium text-purple-100 uppercase tracking-wide">Kategori</p>
                    <p class="text-4xl font-extrabold text-white mt-1">{{ formatNumber(stats.totalKategori) }}</p>
                    <p class="text-xs text-purple-200 mt-1">kategori aktif</p>
                </Link>

                <!-- Total Pengguna -->
                <div class="relative overflow-hidden rounded-2xl p-5"
                    style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <div class="absolute right-3 top-3 opacity-20">
                        <Users class="h-12 w-12 text-white" />
                    </div>
                    <p class="text-xs font-medium text-emerald-100 uppercase tracking-wide">Pengguna</p>
                    <p class="text-4xl font-extrabold text-white mt-1">{{ formatNumber(stats.totalUser) }}</p>
                    <p class="text-xs text-emerald-200 mt-1">akun terdaftar</p>
                </div>

                <!-- Total Stok -->
                <Link :href="barangIndex().url"
                    class="relative overflow-hidden rounded-2xl p-5 hover:scale-[1.02] transition-transform cursor-pointer"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
                    <div class="absolute right-3 top-3 opacity-20">
                        <Layers class="h-12 w-12 text-white" />
                    </div>
                    <p class="text-xs font-medium text-amber-100 uppercase tracking-wide">Total Stok</p>
                    <p class="text-4xl font-extrabold text-white mt-1">{{ formatNumber(stats.totalStok) }}</p>
                    <p class="text-xs text-amber-200 mt-1">unit tersedia</p>
                </Link>

                <!-- Nilai Inventaris -->
                <Link :href="barangIndex().url"
                    class="relative overflow-hidden rounded-2xl p-5 hover:scale-[1.02] transition-transform cursor-pointer col-span-2 lg:col-span-1"
                    style="background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%);">
                    <div class="absolute right-3 top-3 opacity-20">
                        <Wallet class="h-12 w-12 text-white" />
                    </div>
                    <p class="text-xs font-medium text-rose-100 uppercase tracking-wide">Nilai Inventaris</p>
                    <p class="text-lg font-extrabold text-white mt-1 break-all leading-tight">{{ formatCurrency(stats.totalNilai) }}</p>
                    <p class="text-xs text-rose-200 mt-1">total aset</p>
                </Link>

            </div>

            <!-- Content Row -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <!-- Barang Terbaru -->
                <div class="rounded-2xl border bg-card overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-5 py-4"
                        style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
                        <div class="flex items-center gap-2">
                            <div class="rounded-lg bg-blue-500 p-1.5">
                                <TrendingUp class="h-4 w-4 text-white" />
                            </div>
                            <h2 class="font-semibold text-blue-900 dark:text-blue-100">Barang Terbaru</h2>
                        </div>
                        <Link :href="barangIndex().url"
                            class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">
                            Lihat semua →
                        </Link>
                    </div>
                    <div v-if="recentBarangs.length === 0" class="px-5 py-10 text-center text-sm text-muted-foreground">
                        Belum ada data barang.
                    </div>
                    <ul v-else class="divide-y">
                        <li v-for="(item, i) in recentBarangs" :key="item.id"
                            class="flex items-center justify-between px-5 py-3.5 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white shrink-0"
                                    :style="`background: hsl(${210 + i * 30}, 70%, 55%)`">
                                    {{ item.nama.charAt(0).toUpperCase() }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium leading-none">{{ item.nama }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">
                                        {{ item.kategori?.nama ?? '-' }} · {{ formatDate(item.created_at) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-3">
                                <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ formatCurrency(item.harga) }}</p>
                                <p class="text-xs text-muted-foreground">Stok: {{ item.stok }}</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Stok Rendah -->
                <div class="rounded-2xl border bg-card overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-5 py-4"
                        :class="stokRendah.length > 0
                            ? 'bg-linear-to-r from-orange-50 to-red-50 dark:from-orange-950/40 dark:to-red-950/40'
                            : 'bg-linear-to-r from-emerald-50 to-green-50 dark:from-emerald-950/40 dark:to-green-950/40'">
                        <div class="flex items-center gap-2">
                            <div class="rounded-lg p-1.5"
                                :class="stokRendah.length > 0 ? 'bg-orange-500' : 'bg-emerald-500'">
                                <AlertTriangle class="h-4 w-4 text-white" />
                            </div>
                            <h2 class="font-semibold"
                                :class="stokRendah.length > 0 ? 'text-orange-900 dark:text-orange-100' : 'text-emerald-900 dark:text-emerald-100'">
                                Stok Rendah
                            </h2>
                            <span v-if="stokRendah.length > 0"
                                class="inline-flex items-center rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">
                                {{ stokRendah.length }}
                            </span>
                        </div>
                        <Link :href="barangIndex().url"
                            class="text-xs font-medium hover:underline"
                            :class="stokRendah.length > 0 ? 'text-orange-600 hover:text-orange-800' : 'text-emerald-600 hover:text-emerald-800'">
                            Kelola →
                        </Link>
                    </div>
                    <div v-if="stokRendah.length === 0" class="px-5 py-10 text-center">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 mb-3">
                            <Package class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">Semua stok mencukupi!</p>
                        <p class="text-xs text-muted-foreground mt-1">Tidak ada barang dengan stok rendah.</p>
                    </div>
                    <ul v-else class="divide-y">
                        <li v-for="item in stokRendah" :key="item.id"
                            class="flex items-center justify-between px-5 py-3.5 hover:bg-orange-50/50 dark:hover:bg-orange-950/20 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full shrink-0"
                                    :class="item.stok === 0
                                        ? 'bg-red-100 dark:bg-red-900/30'
                                        : 'bg-orange-100 dark:bg-orange-900/30'">
                                    <Package class="h-4 w-4"
                                        :class="item.stok === 0 ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400'" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium leading-none">{{ item.nama }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">{{ item.kategori?.nama ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold shrink-0 ml-3"
                                :class="item.stok === 0
                                    ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                    : 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'">
                                {{ item.stok === 0 ? 'Habis' : `Sisa ${item.stok}` }}
                            </span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
