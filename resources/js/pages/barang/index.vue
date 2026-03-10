<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Trash2 } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import {
    DropdownMenu, DropdownMenuContent,
    DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { index, store, edit, destroy } from '@/routes/barang';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface KategoriOption { id: number; nama: string }

interface Barang {
    id: number; nama: string; deskripsi: string | null;
    harga: number; stok: number; tanggal_pembelian: string;
    kategori: KategoriOption | null;
}

interface PaginationLink { url: string | null; label: string; active: boolean }

interface Paginated<T> {
    data: T[]; current_page: number; last_page: number;
    per_page: number; total: number; links: PaginationLink[];
}

const props = defineProps<{
    barangs: Paginated<Barang>;
    kategoris: KategoriOption[];
    filters: { search?: string; kategori_id?: string };
    summary: { totalBarang: number; totalStok: number; totalNilai: number };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Barang', href: index() },
];

// Filters
const search = ref(props.filters.search ?? '');
const kategoriFilter = ref(props.filters.kategori_id ?? '');
let timeout: ReturnType<typeof setTimeout>;

function applyFilters() {
    router.get(index().url, {
        search: search.value || undefined,
        kategori_id: kategoriFilter.value || undefined,
    }, { preserveState: true, replace: true });
}

watch(search, () => { clearTimeout(timeout); timeout = setTimeout(applyFilters, 300); });
watch(kategoriFilter, applyFilters);

// Format
function formatCurrency(value: number) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
}
function formatDate(value: string) {
    return new Date(value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

const CATEGORY_COLORS = [
    'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
    'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
    'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
    'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
    'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
    'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
    'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
    'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300',
    'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
];

function kategoriColor(id: number | undefined) {
    if (!id) return 'bg-muted text-muted-foreground';
    return CATEGORY_COLORS[id % CATEGORY_COLORS.length];
}

// Modal Tambah
const showCreate = ref(false);
const createForm = useForm({
    kategori_id: '' as number | '',
    nama: '', deskripsi: '',
    harga: '' as number | '',
    stok: '' as number | '',
    tanggal_pembelian: '',
});

function openCreate() {
    createForm.reset();
    createForm.clearErrors();
    showCreate.value = true;
}

function submitCreate() {
    createForm.post(store().url, {
        onSuccess: () => { showCreate.value = false; },
    });
}

// Hapus
const confirmDeleteId = ref<number | null>(null);
const confirmDeleteNama = ref('');

function openDelete(id: number, nama: string) {
    confirmDeleteId.value = id;
    confirmDeleteNama.value = nama;
}

function handleDelete() {
    if (confirmDeleteId.value === null) return;
    router.delete(destroy({ barang: confirmDeleteId.value }).url, {
        onFinish: () => { confirmDeleteId.value = null; confirmDeleteNama.value = ''; },
    });
}
</script>

<template>
    <Head title="Barang" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Barang</h1>
                    <p class="text-sm text-muted-foreground">Kelola data barang inventaris</p>
                </div>
                <Button @click="openCreate">+ Tambah Barang</Button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-3 gap-4">
                <div class="rounded-xl border bg-card p-4 border-t-4 border-t-blue-500">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide">Total Barang</p>
                    <p class="text-3xl font-bold mt-1 text-blue-600 dark:text-blue-400">{{ summary.totalBarang }}</p>
                    <p class="text-xs text-muted-foreground mt-1">item ditemukan</p>
                </div>
                <div class="rounded-xl border bg-card p-4 border-t-4 border-t-amber-500">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide">Total Stok</p>
                    <p class="text-3xl font-bold mt-1 text-amber-600 dark:text-amber-400">{{ summary.totalStok }}</p>
                    <p class="text-xs text-muted-foreground mt-1">unit tersedia</p>
                </div>
                <div class="rounded-xl border bg-card p-4 border-t-4 border-t-rose-500">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide">Total Nilai</p>
                    <p class="text-xl font-bold mt-1 break-all text-rose-600 dark:text-rose-400">{{ formatCurrency(summary.totalNilai) }}</p>
                    <p class="text-xs text-muted-foreground mt-1">nilai inventaris</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <Input v-model="search" placeholder="Cari nama barang..." class="flex-1 min-w-48" />
                <select v-model="kategoriFilter"
                    class="rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="">Semua Kategori</option>
                    <option v-for="k in kategoris" :key="k.id" :value="String(k.id)">{{ k.nama }}</option>
                </select>
            </div>

            <!-- Table -->
            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">No</th>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">Nama</th>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">Kategori</th>
                            <th class="px-4 py-3 text-right font-medium text-muted-foreground">Harga</th>
                            <th class="px-4 py-3 text-right font-medium text-muted-foreground">Stok</th>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">Tgl. Beli</th>
                            <th class="px-4 py-3 w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="barangs.data.length === 0">
                            <td colspan="7" class="px-4 py-12 text-center text-muted-foreground">
                                Tidak ada data barang.
                            </td>
                        </tr>
                        <tr v-for="(item, i) in barangs.data" :key="item.id"
                            class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 text-muted-foreground text-xs">
                                {{ (barangs.current_page - 1) * barangs.per_page + i + 1 }}
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ item.nama }}</p>
                                <p v-if="item.deskripsi" class="text-xs text-muted-foreground truncate max-w-48">
                                    {{ item.deskripsi }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="kategoriColor(item.kategori?.id)">
                                    {{ item.kategori?.nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium">{{ formatCurrency(item.harga) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold"
                                    :class="item.stok === 0 ? 'text-destructive' : item.stok <= 5 ? 'text-orange-500' : ''">
                                    {{ item.stok }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">{{ formatDate(item.tanggal_pembelian) }}</td>
                            <td class="px-4 py-3 text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem as-child>
                                            <Link :href="edit({ barang: item.id }).url"
                                                class="flex items-center gap-2 cursor-pointer">
                                                <Pencil class="h-4 w-4" />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem
                                            class="flex items-center gap-2 text-destructive focus:text-destructive cursor-pointer"
                                            @click="openDelete(item.id, item.nama)">
                                            <Trash2 class="h-4 w-4" />
                                            Hapus
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between text-sm text-muted-foreground">
                <span>Total {{ barangs.total }} barang</span>
                <div class="flex gap-1 flex-wrap">
                    <template v-for="link in barangs.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url" preserve-state>
                            <Button variant="outline" size="sm"
                                :class="{ 'bg-primary text-primary-foreground hover:bg-primary/90': link.active }"
                                v-html="link.label" />
                        </Link>
                        <Button v-else variant="outline" size="sm" disabled v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Barang -->
        <Dialog v-model:open="showCreate">
            <DialogContent class="max-w-lg">
                <DialogHeader>
                    <DialogTitle>Tambah Barang</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                    <div class="space-y-1">
                        <Label for="c-kategori">Kategori <span class="text-destructive">*</span></Label>
                        <select id="c-kategori" v-model="createForm.kategori_id"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            :class="{ 'border-destructive': createForm.errors.kategori_id }">
                            <option value="">-- Pilih Kategori --</option>
                            <option v-for="k in kategoris" :key="k.id" :value="k.id">{{ k.nama }}</option>
                        </select>
                        <p v-if="createForm.errors.kategori_id" class="text-destructive text-sm">{{ createForm.errors.kategori_id }}</p>
                    </div>
                    <div class="space-y-1">
                        <Label for="c-nama">Nama <span class="text-destructive">*</span></Label>
                        <Input id="c-nama" v-model="createForm.nama" placeholder="Masukkan nama barang"
                            :class="{ 'border-destructive': createForm.errors.nama }" />
                        <p v-if="createForm.errors.nama" class="text-destructive text-sm">{{ createForm.errors.nama }}</p>
                    </div>
                    <div class="space-y-1">
                        <Label for="c-deskripsi">Deskripsi <span class="text-destructive">*</span></Label>
                        <textarea id="c-deskripsi" v-model="createForm.deskripsi" placeholder="Masukkan deskripsi" rows="2"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            :class="{ 'border-destructive': createForm.errors.deskripsi }" />
                        <p v-if="createForm.errors.deskripsi" class="text-destructive text-sm">{{ createForm.errors.deskripsi }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <Label for="c-harga">Harga (Rp) <span class="text-destructive">*</span></Label>
                            <Input id="c-harga" v-model="createForm.harga" type="number" min="0" placeholder="0"
                                :class="{ 'border-destructive': createForm.errors.harga }" />
                            <p v-if="createForm.errors.harga" class="text-destructive text-sm">{{ createForm.errors.harga }}</p>
                        </div>
                        <div class="space-y-1">
                            <Label for="c-stok">Stok <span class="text-destructive">*</span></Label>
                            <Input id="c-stok" v-model="createForm.stok" type="number" min="0" placeholder="0"
                                :class="{ 'border-destructive': createForm.errors.stok }" />
                            <p v-if="createForm.errors.stok" class="text-destructive text-sm">{{ createForm.errors.stok }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <Label for="c-tgl">Tanggal Pembelian <span class="text-destructive">*</span></Label>
                        <Input id="c-tgl" v-model="createForm.tanggal_pembelian" type="date"
                            :class="{ 'border-destructive': createForm.errors.tanggal_pembelian }" />
                        <p v-if="createForm.errors.tanggal_pembelian" class="text-destructive text-sm">{{ createForm.errors.tanggal_pembelian }}</p>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="showCreate = false">Batal</Button>
                        <Button type="submit" :disabled="createForm.processing">
                            {{ createForm.processing ? 'Menyimpan...' : 'Simpan' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Modal Hapus -->
        <Dialog :open="confirmDeleteId !== null" @update:open="(v) => { if (!v) confirmDeleteId = null }">
            <DialogContent class="max-w-sm">
                <DialogHeader>
                    <DialogTitle>Hapus Barang</DialogTitle>
                </DialogHeader>
                <p class="text-sm text-muted-foreground">
                    Yakin ingin menghapus barang <strong>{{ confirmDeleteNama }}</strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <DialogFooter>
                    <Button variant="outline" @click="confirmDeleteId = null">Batal</Button>
                    <Button variant="destructive" @click="handleDelete">Hapus</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
