<div class="mb-6 bg-gray-950 text-white rounded-2xl p-3 px-5 flex flex-wrap items-center justify-between gap-3 shadow-md border border-purple-500/30 text-xs">
    <div class="flex items-center gap-2.5 flex-wrap">
        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-bold bg-purple-600 text-white text-[10px]">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            LIVE DEMO
        </span>
        <span class="text-gray-300">
            Anda sedang login di portal demo &bull; Proyek Portofolio <strong>Wahyu Idaya</strong> (Programmer)
        </span>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-purple-300 hover:text-white underline font-medium">
            Ganti Akun Demo
        </a>
        <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white px-3 py-1 rounded-lg font-medium inline-flex items-center gap-1.5 transition shadow-sm">
            ← Kembali ke Portofolio
        </a>
    </div>
</div>
