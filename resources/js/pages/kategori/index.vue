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
import { index, store, edit, destroy } from '@/routes/kategori';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface Kategori {
    id: number;
    nama: string;
    deskripsi: string | null;
    barangs_count: number;
}

interface PaginationLink { url: string | null; label: string; active: boolean }

interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginationLink[];
}

const props = defineProps<{
    kategoris: Paginated<Kategori>;
    filters: { search?: string };
    summary: { totalKategori: number; totalBarang: number };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Kategori', href: index() },
];

// Search
const search = ref(props.filters.search ?? '');
let timeout: ReturnType<typeof setTimeout>;
watch(search, (val) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(index().url, { search: val || undefined }, { preserveState: true, replace: true });
    }, 300);
});

// Modal Tambah
const showCreate = ref(false);
const createForm = useForm({ nama: '', deskripsi: '' });

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
    router.delete(destroy({ kategori: confirmDeleteId.value }).url, {
        onFinish: () => { confirmDeleteId.value = null; confirmDeleteNama.value = ''; },
    });
}
</script>

<template>
    <Head title="Kategori" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Kategori</h1>
                    <p class="text-sm text-muted-foreground">Kelola kategori barang inventaris</p>
                </div>
                <Button @click="openCreate">+ Tambah Kategori</Button>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl border bg-card p-4 border-t-4 border-t-purple-500">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide">Total Kategori</p>
                    <p class="text-3xl font-bold mt-1 text-purple-600 dark:text-purple-400">{{ summary.totalKategori }}</p>
                    <p class="text-xs text-muted-foreground mt-1">
                        {{ filters.search ? `hasil "${filters.search}"` : 'semua kategori' }}
                    </p>
                </div>
                <div class="rounded-xl border bg-card p-4 border-t-4 border-t-blue-500">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide">Total Barang</p>
                    <p class="text-3xl font-bold mt-1 text-blue-600 dark:text-blue-400">{{ summary.totalBarang }}</p>
                    <p class="text-xs text-muted-foreground mt-1">dalam kategori yang ditampilkan</p>
                </div>
            </div>

            <!-- Search -->
            <Input v-model="search" placeholder="Cari nama kategori..." class="max-w-sm" />

            <!-- Table -->
            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">No</th>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">Nama</th>
                            <th class="px-4 py-3 text-left font-medium text-muted-foreground">Deskripsi</th>
                            <th class="px-4 py-3 text-right font-medium text-muted-foreground">Barang</th>
                            <th class="px-4 py-3 text-center font-medium text-muted-foreground w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="kategoris.data.length === 0">
                            <td colspan="5" class="px-4 py-12 text-center text-muted-foreground">
                                Tidak ada data kategori.
                            </td>
                        </tr>
                        <tr v-for="(item, i) in kategoris.data" :key="item.id"
                            class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 text-muted-foreground text-xs">
                                {{ (kategoris.current_page - 1) * kategoris.per_page + i + 1 }}
                            </td>
                            <td class="px-4 py-3 font-medium">{{ item.nama }}</td>
                            <td class="px-4 py-3 text-muted-foreground">{{ item.deskripsi ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium">
                                    {{ item.barangs_count }} item
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon" class="h-8 w-8">
                                            <MoreHorizontal class="h-4 w-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem as-child>
                                            <Link :href="edit({ kategori: item.id }).url"
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
                <span>Total {{ kategoris.total }} kategori</span>
                <div class="flex gap-1 flex-wrap">
                    <template v-for="link in kategoris.links" :key="link.label">
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

        <!-- Modal Tambah -->
        <Dialog v-model:open="showCreate">
            <DialogContent class="max-w-md">
                <DialogHeader>
                    <DialogTitle>Tambah Kategori</DialogTitle>
                </DialogHeader>
                <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                    <div class="space-y-1">
                        <Label for="nama">Nama <span class="text-destructive">*</span></Label>
                        <Input id="nama" v-model="createForm.nama" placeholder="Masukkan nama kategori"
                            :class="{ 'border-destructive': createForm.errors.nama }" autofocus />
                        <p v-if="createForm.errors.nama" class="text-destructive text-sm">{{ createForm.errors.nama }}</p>
                    </div>
                    <div class="space-y-1">
                        <Label for="deskripsi">Deskripsi</Label>
                        <textarea id="deskripsi" v-model="createForm.deskripsi"
                            placeholder="Masukkan deskripsi (opsional)" rows="3"
                            class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            :class="{ 'border-destructive': createForm.errors.deskripsi }" />
                        <p v-if="createForm.errors.deskripsi" class="text-destructive text-sm">{{ createForm.errors.deskripsi }}</p>
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
                    <DialogTitle>Hapus Kategori</DialogTitle>
                </DialogHeader>
                <p class="text-sm text-muted-foreground">
                    Yakin ingin menghapus kategori <strong>{{ confirmDeleteNama }}</strong>?
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
