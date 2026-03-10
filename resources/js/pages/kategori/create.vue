<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, store } from '@/routes/kategori';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Kategori', href: index() },
    { title: 'Tambah', href: '#' },
];

const form = useForm({
    nama: '',
    deskripsi: '',
});

function submit() {
    form.post(store().url);
}
</script>

<template>
    <Head title="Tambah Kategori" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 max-w-lg space-y-6">
            <h1 class="text-2xl font-semibold">Tambah Kategori</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Nama -->
                <div class="space-y-1">
                    <Label for="nama">
                        Nama <span class="text-destructive">*</span>
                    </Label>
                    <Input
                        id="nama"
                        v-model="form.nama"
                        placeholder="Masukkan nama kategori"
                        :class="{ 'border-destructive': form.errors.nama }"
                    />
                    <p v-if="form.errors.nama" class="text-destructive text-sm">
                        {{ form.errors.nama }}
                    </p>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-1">
                    <Label for="deskripsi">Deskripsi</Label>
                    <textarea
                        id="deskripsi"
                        v-model="form.deskripsi"
                        placeholder="Masukkan deskripsi (opsional)"
                        rows="4"
                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        :class="{ 'border-destructive': form.errors.deskripsi }"
                    />
                    <p v-if="form.errors.deskripsi" class="text-destructive text-sm">
                        {{ form.errors.deskripsi }}
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
