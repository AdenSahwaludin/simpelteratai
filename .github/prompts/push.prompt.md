---
agent: agent
model: Auto (copilot)
---

## Task Definition

Push perubahan kode ke GitHub dengan **commit message yang relevan**, jelas, dan mengikuti best practice. Agent harus mengidentifikasi perubahan, menyusun commit message yang tepat, lalu menghasilkan perintah Git lengkap untuk melakukan `add .`, `commit`, dan `push`.

## Specific Requirements

1. Agent harus:

    - Menanyakan atau membaca perubahan yang diberikan user.
    - Mengidentifikasi file yang berubah (melalui penjelasan user atau output `git status`).
    - Menentukan jenis commit:
        - `feat`, `fix`, `docs`, `refactor`, `style`, `chore`, dll.
    - Membuat commit message yang ringkas, jelas, dan sesuai konteks.
    - Menghasilkan perintah lengkap:
        - `git add`
        - `git commit -m "<pesan>"`
        - `git push origin <branch>`

2. Commit harus:

    - Menjelaskan **apa** yang berubah.
    - Tidak boleh terlalu umum seperti “update” atau “perbaikan”.
    - Menggunakan Bahasa Indonesia atau Inggris yang tetap formal dan jelas.

3. Jika diperlukan, agent harus:
    - Menanyakan branch yang dipakai bila belum diketahui.
    - Mengingatkan untuk tidak meng-commit file sensitif seperti `.env`, `node_modules`, `vendor`, `storage`, dan build folder.

## Constraints

-   Commit message wajib menggunakan standar konvensi seperti:
    -   `feat: ...`
    -   `fix: ...`
    -   `docs: ...`
    -   `refactor: ...`
    -   `style: ...`
    -   `chore: ...`
-   Tidak boleh membuat commit yang tidak relevan dengan perubahan.
-   Tidak boleh melakukan push tanpa konfirmasi branch (jika tidak diberikan oleh user).
-   Tidak boleh membuat command yang berpotensi merusak repository (misalnya forced push), kecuali diminta dengan jelas oleh user.
-   Pastikan file sensitif tidak ikut di-add.

## Success Criteria

Agent dianggap berhasil apabila:

-   Commit message yang dibuat **tepat**, **deskriptif**, dan **sesuai best practice**.
-   Perintah git yang dihasilkan **benar**, **run-ready**, dan **aman** dieksekusi.
-   Agent membantu user menyelesaikan proses push dari awal sampai akhir tanpa error.
-   Semua langkah (add → commit → push) tersusun jelas.

## Output Format (Saat User Mengaktifkan Prompt Ini)

Ketika user berkata:  
**"pushkan ke github dengan commit yang sesuai"**,  
agent harus menghasilkan struktur jawaban seperti:

1. Ringkasan perubahan (berdasarkan input user).
2. 2–3 opsi commit message.
3. Perintah git lengkap, contoh:

```bash
git add <file1> <file2> ...
git commit -m "<commit message>"
git push origin <branch>
```
