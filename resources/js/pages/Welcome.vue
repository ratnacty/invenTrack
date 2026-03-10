<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { login, register, dashboard } from '@/routes';
import { Package, BarChart3, ShieldCheck, Zap } from 'lucide-vue-next';

withDefaults(defineProps<{ canRegister: boolean }>(), { canRegister: true });

const visible = ref(false);
onMounted(() => { setTimeout(() => { visible.value = true; }, 100); });
</script>

<template>
    <Head title="Selamat Datang" />

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 text-white overflow-hidden relative">

        <!-- Background decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute top-1/2 -left-40 w-80 h-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute -bottom-40 right-1/3 w-96 h-96 rounded-full bg-purple-500/10 blur-3xl"></div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-10 flex items-center justify-between px-6 py-5 lg:px-12">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-500 shadow-lg shadow-blue-500/30">
                    <Package class="h-5 w-5 text-white" />
                </div>
                <span class="text-lg font-bold tracking-tight">InvenTrack</span>
            </div>
            <div class="flex items-center gap-3">
                <Link v-if="$page.props.auth.user" :href="dashboard()"
                    class="rounded-lg bg-blue-500 px-5 py-2 text-sm font-medium hover:bg-blue-400 transition-colors shadow-lg shadow-blue-500/30">
                    Dashboard
                </Link>
                <template v-else>
                    <Link :href="login()"
                        class="rounded-lg px-5 py-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                        Log in
                    </Link>
                    <Link v-if="canRegister" :href="register()"
                        class="rounded-lg bg-blue-500 px-5 py-2 text-sm font-medium hover:bg-blue-400 transition-colors shadow-lg shadow-blue-500/30">
                        Daftar
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero -->
        <main class="relative z-10 flex flex-col lg:flex-row items-center justify-between px-6 pt-12 pb-16 gap-12 lg:px-12 lg:pt-16">

            <!-- Left: Text -->
            <div class="flex-1 text-center lg:text-left max-w-xl">
                <!-- Badge -->
                <div
                    class="mb-5 inline-flex items-center gap-2 rounded-full border border-blue-400/30 bg-blue-500/10 px-4 py-1.5 text-sm text-blue-300 backdrop-blur-sm transition-all duration-700"
                    :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    <Zap class="h-3.5 w-3.5" />
                    Sistem Manajemen Inventaris Modern
                </div>

                <!-- Heading -->
                <h1
                    class="text-4xl font-extrabold leading-tight tracking-tight lg:text-5xl transition-all duration-700 delay-100"
                    :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    Kelola Inventaris
                    <span class="bg-linear-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                        Lebih Cerdas
                    </span>
                    &amp; Efisien
                </h1>

                <p
                    class="mt-5 text-base text-white/60 lg:text-lg transition-all duration-700 delay-200"
                    :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    InvenTrack membantu Anda memantau stok, mengelola kategori, dan melacak nilai inventaris secara real-time dalam satu platform yang mudah digunakan.
                </p>
            </div>

            <!-- Right: Mockup -->
            <div
                class="flex-1 w-full max-w-2xl transition-all duration-1000 delay-300"
                :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
                <div class="relative rounded-2xl border border-white/10 bg-white/5 p-1 shadow-2xl backdrop-blur-sm">
                    <!-- Fake browser bar -->
                    <div class="flex items-center gap-1.5 px-4 py-3 border-b border-white/10">
                        <div class="h-3 w-3 rounded-full bg-red-400/70"></div>
                        <div class="h-3 w-3 rounded-full bg-yellow-400/70"></div>
                        <div class="h-3 w-3 rounded-full bg-green-400/70"></div>
                        <div class="ml-4 h-5 flex-1 max-w-xs rounded-full bg-white/10 flex items-center px-3 text-xs text-white/40">
                            inventrack.app/dashboard
                        </div>
                    </div>
                    <!-- Fake dashboard content -->
                    <div class="p-5 space-y-4">
                        <!-- Stat cards row -->
                        <div class="grid grid-cols-4 gap-3">
                            <div class="rounded-xl bg-blue-500/20 border border-blue-400/20 p-3">
                                <p class="text-xs text-blue-300/70">Total Barang</p>
                                <p class="text-2xl font-bold text-blue-300 mt-1">248</p>
                                <p class="text-xs text-blue-300/50 mt-0.5">item</p>
                            </div>
                            <div class="rounded-xl bg-purple-500/20 border border-purple-400/20 p-3">
                                <p class="text-xs text-purple-300/70">Kategori</p>
                                <p class="text-2xl font-bold text-purple-300 mt-1">12</p>
                                <p class="text-xs text-purple-300/50 mt-0.5">aktif</p>
                            </div>
                            <div class="rounded-xl bg-amber-500/20 border border-amber-400/20 p-3">
                                <p class="text-xs text-amber-300/70">Total Stok</p>
                                <p class="text-2xl font-bold text-amber-300 mt-1">1.842</p>
                                <p class="text-xs text-amber-300/50 mt-0.5">unit</p>
                            </div>
                            <div class="rounded-xl bg-rose-500/20 border border-rose-400/20 p-3">
                                <p class="text-xs text-rose-300/70">Nilai</p>
                                <p class="text-lg font-bold text-rose-300 mt-1">Rp 482 jt</p>
                                <p class="text-xs text-rose-300/50 mt-0.5">inventaris</p>
                            </div>
                        </div>
                        <!-- Fake table rows -->
                        <div class="rounded-xl border border-white/10 overflow-hidden">
                            <div class="grid grid-cols-5 gap-2 px-4 py-2 bg-white/5 text-xs text-white/40">
                                <span>Nama</span><span>Kategori</span><span class="text-right">Harga</span><span class="text-right">Stok</span><span>Tgl. Beli</span>
                            </div>
                            <div class="grid grid-cols-5 gap-2 px-4 py-2.5 border-t border-white/5 text-sm items-center">
                                <span class="h-3 rounded bg-white/10 w-4/5"></span>
                                <span class="inline-flex"><span class="rounded-full px-2 py-0.5 text-xs bg-blue-500/20 text-blue-300">Elektronik</span></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-3/4"></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-2/5"></span>
                                <span class="h-3 rounded bg-white/10 w-4/5"></span>
                            </div>
                            <div class="grid grid-cols-5 gap-2 px-4 py-2.5 border-t border-white/5 text-sm items-center">
                                <span class="h-3 rounded bg-white/10 w-3/5"></span>
                                <span class="inline-flex"><span class="rounded-full px-2 py-0.5 text-xs bg-purple-500/20 text-purple-300">Furnitur</span></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-3/4"></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-2/5"></span>
                                <span class="h-3 rounded bg-white/10 w-4/5"></span>
                            </div>
                            <div class="grid grid-cols-5 gap-2 px-4 py-2.5 border-t border-white/5 text-sm items-center">
                                <span class="h-3 rounded bg-white/10 w-4/6"></span>
                                <span class="inline-flex"><span class="rounded-full px-2 py-0.5 text-xs bg-emerald-500/20 text-emerald-300">Alat Tulis</span></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-3/4"></span>
                                <span class="h-3 rounded bg-white/10 ml-auto w-2/5"></span>
                                <span class="h-3 rounded bg-white/10 w-4/5"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mx-auto mt-2 h-8 w-2/3 rounded-full bg-blue-500/20 blur-xl"></div>
            </div>
        </main>

        <!-- Features -->
        <section
            class="relative z-10 border-t border-white/10 px-6 py-16 lg:px-12 transition-all duration-1000 delay-700"
            :class="visible ? 'opacity-100' : 'opacity-0'">
            <div class="mx-auto max-w-4xl grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm hover:bg-white/8 transition-colors">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/20">
                        <Package class="h-5 w-5 text-blue-400" />
                    </div>
                    <h3 class="font-semibold">Kelola Barang</h3>
                    <p class="mt-1.5 text-sm text-white/50">Tambah, edit, dan hapus data barang dengan mudah. Filter dan cari berdasarkan kategori.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm hover:bg-white/8 transition-colors">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/20">
                        <BarChart3 class="h-5 w-5 text-purple-400" />
                    </div>
                    <h3 class="font-semibold">Pantau Stok</h3>
                    <p class="mt-1.5 text-sm text-white/50">Dapatkan peringatan stok rendah dan pantau nilai inventaris secara real-time.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm hover:bg-white/8 transition-colors">
                    <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/20">
                        <ShieldCheck class="h-5 w-5 text-emerald-400" />
                    </div>
                    <h3 class="font-semibold">Aman &amp; Andal</h3>
                    <p class="mt-1.5 text-sm text-white/50">Data tersimpan aman dengan autentikasi pengguna dan kontrol akses yang ketat.</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-white/10 px-6 py-6 text-center text-sm text-white/30">
            &copy; {{ new Date().getFullYear() }} InvenTrack. Sistem Manajemen Inventaris.
        </footer>

    </div>
</template>
