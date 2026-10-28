<?php

namespace App\Http\Controllers\StafProdi;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\StafProdi\ActivityLogController;

class FaqController extends Controller
{
    /**
     * Tampilkan halaman list FAQ
     */
    public function index()
    {
        try {
            $faqList = Faq::ordered()->get();

            return view('dashboard.staf-prodi.faq', compact('faqList'));

        } catch (\Exception $e) {
            return view('dashboard.staf-prodi.faq', [
                'faqList' => collect([]),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tampilkan form tambah FAQ
     */
    public function create()
    {
        return view('dashboard.staf-prodi.tambah_faq');
    }

    /**
     * Simpan FAQ baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pertanyaan' => 'required|string|max:200',
                'jawaban' => 'required|string',
                'urutan' => 'nullable|integer|min:0',
            ]);

            Faq::create([
                'id_faq' => (string) \Illuminate\Support\Str::uuid(),
                'pertanyaan' => $validated['pertanyaan'],
                'jawaban' => $validated['jawaban'],
                'urutan' => $validated['urutan'] ?? 0,
            ]);

            ActivityLogController::saveLog(
                'Tambah FAQ', 
                "Pertanyaan: " . $validated['pertanyaan']
            );

            return redirect()->route('staf-prodi.faq')
                ->with('success', 'FAQ berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()))->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan FAQ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan form edit FAQ
     */
    public function edit($id_faq)
    {
        try {
            $faq = Faq::findOrFail($id_faq);

            return view('dashboard.staf-prodi.edit_faq', compact('faq'));

        } catch (\Exception $e) {
            return redirect()->route('staf-prodi.faq')
                ->with('error', 'FAQ tidak ditemukan');
        }
    }

    /**
     * Update FAQ
     */
    public function update(Request $request, $id_faq)
    {
        try {
            $faq = Faq::findOrFail($id_faq);

            $validated = $request->validate([
                'pertanyaan' => 'required|string|max:200',
                'jawaban' => 'required|string',
                'urutan' => 'nullable|integer|min:0',
            ]);

            $faq->update([
                'pertanyaan' => $validated['pertanyaan'],
                'jawaban' => $validated['jawaban'],
                'urutan' => $validated['urutan'] ?? 0,
            ]);

            ActivityLogController::saveLog(
                'Update FAQ', 
                "Mengubah FAQ ID: {$id_faq} | Pertanyaan: " . $validated['pertanyaan']
            );

            return redirect()->route('staf-prodi.faq')
                ->with('success', 'FAQ berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui FAQ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus FAQ
     */
    public function destroy($id_faq)
    {
        try {
            $faq = Faq::findOrFail($id_faq);
            $faq->delete();

            ActivityLogController::saveLog(
                'Hapus FAQ', 
                "Menghapus FAQ: " . $faq->pertanyaan
            );

            return redirect()->route('staf-prodi.faq')
                ->with('success', 'FAQ berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus FAQ: ' . $e->getMessage());
        }
    }
}