<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, store } from '@/routes/barang';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface KategoriOption {
    id: number;
    nama: string;
}

defineProps<{
    kategoris: KategoriOption[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Barang', href: index() },
    { title: 'Tambah', href: '#' },
];

const form = useForm({
    kategori_id: '' as number | '',
    nama: '',
    deskripsi: '',
    harga: '' as number | '',
    stok: '' as number | '',
    tanggal_pembelian: '',
});

function submit() {
    form.post(store().url);
}
</script>

<template>
    <Head title="Tambah Barang" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-2xl space-y-6">
            <h1 class="text-2xl font-semibold">Tambah Barang</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Kategori -->
                <div class="space-y-1">
                    <Label for="kategori_id">
                        Kategori <span class="text-destructive">*</span>
                    </Label>
                    <select
                        id="kategori_id"
                        v-model="form.kategori_id"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        :class="{ 'border-destructive': form.errors.kategori_id }"
                    >
                        <option value="">-- Pilih Kategori --</option>
                        <option v-for="k in kategoris" :key="k.id" :value="k.id">
                            {{ k.nama }}
                        </option>
                    </select>
                    <p v-if="form.errors.kategori_id" class="text-destructive text-sm">
                        {{ form.errors.kategori_id }}
                    </p>
                </div>

                <!-- Nama -->
                <div class="space-y-1">
                    <Label for="nama">
                        Nama <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="nama"
                        v-model="form.nama"
                        placeholder="Masukkan nama barang"
                        :class="{ 'border-destructive': form.errors.nama }"
                    />
                    <p v-if="form.errors.nama" class="text-destructive text-sm">
                        {{ form.errors.nama }}
                    </p>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-1">
                    <Label for="deskripsi">
                        Deskripsi <span class="text-destructive">*</span>
                    </Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi barang"
                        rows="3"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-destructive': form.errors.deskripsi }"
                    />
                    <p v-if="form.errors.deskripsi" class="text-destructive text-sm">
                        {{ form.errors.deskripsi }}
                    </p>
                </div>

                <!-- Harga & Stok -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <Label for="harga">
                            Harga (Rp) <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="harga"
                            v-model="form.harga"
                            type="number"
                            min="0"
                            step="1"
                            placeholder="0"
                            :class="{ 'border-destructive': form.errors.harga }"
                        />
                        <p v-if="form.errors.harga" class="text-destructive text-sm">
                            {{ form.errors.harga }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <Label for="stok">
                            Stok <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="stok"
                            v-model="form.stok"
                            type="number"
                            min="0"
                            step="1"
                            placeholder="0"
                            :class="{ 'border-destructive': form.errors.stok }"
                        />
                        <p v-if="form.errors.stok" class="text-destructive text-sm">
                            {{ form.errors.stok }}
                        </p>
                    </div>
                </div>

                <!-- Tanggal Pembelian -->
                <div class="space-y-1">
                    <Label for="tanggal_pembelian">
                        Tanggal Pembelian <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="tanggal_pembelian"
                        v-model="form.tanggal_pembelian"
                        type="date"
                        :class="{ 'border-destructive': form.errors.tanggal_pembelian }"
                    />
                    <p v-if="form.errors.tanggal_pembelian" class="text-destructive text-sm">
                        {{ form.errors.tanggal_pembelian }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-2">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </Button>
                    <Link :href="index().url">
                        <Button type="button" variant="outline">Batal</Button>
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
