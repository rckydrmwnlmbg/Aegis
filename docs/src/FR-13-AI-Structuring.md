# FR-13: AI Structuring Agents (Background Parsing)
**Module:** AI & Intelligence
**Related Schemas:** ai_runs, ai_prompts, incidents, hazard_observations, jsa_documents
## 1. Module Overview
Modul "Pekerja Kasar" AI di balik layar. Bertugas memproses data mentah yang diunggah dari lapangan (transkrip suara yang kotor, teks berantakan) dan menstrukturisasinya menjadi format JSON baku (5W1H, Matriks Risiko, dll) yang siap dimasukkan ke dalam *database* transaksional.
## 2. Actors & Roles
 * **System (Queue Worker):** Berjalan otomatis tanpa antarmuka (*headless*).
 * **Human Validator (Officer/Safetyman):** Tidak berinteraksi langsung dengan proses ini, namun bertugas me- *review* hasil akhirnya (Draft Laporan).
## 3. Business Logic & Rules
 1. **Asynchronous Isolation (Mandatory):**
   * Tidak boleh ada pemanggilan API LLM (OpenAI/Anthropic) yang berjalan secara sinkronus pada *request* HTTP dari *client*.
   * Semua tugas harus masuk ke dalam antrean (Redis/RabbitMQ) dengan *retry logic* maksimal 3 kali jika terjadi *timeout* dari pihak *vendor* AI.
 2. **Intent Routing:**
   * Sebelum menstrukturisasi, agen Router harus berjalan lebih dulu untuk mengecek apakah *payload* suara/teks ini adalah Insiden, Bahaya, atau *Near Miss*.
 3. **Strict JSON Enforcement:**
   * Sistem wajib menolak (atau mengarahkan ke *Fallback Queue*) jika LLM mengembalikan respons yang gagal di- *parse* menggunakan fungsi json_decode().
 4. **Confidence Scoring:**
   * AI harus memberikan metrik ai_confidence_score (0-100). Jika skor < 70 (karena suara terlalu bising atau tidak jelas), laporan akan diberi penanda merah di *Dashboard* agar manusia meninjaunya secara ekstra hati-hati.
## 4. UI/UX Flows
 * **Tidak ada UI langsung.** Modul ini beroperasi murni di *background*. UX yang terlihat hanyalah perubahan status *Sync Queue* di HP pengguna dari Processing menjadi Draft Ready.
## 5. API & Integration Requirements
 * **Internal Workers:** Menggunakan *Laravel Jobs* (misal: ProcessIncidentAudioJob).
 * **External Integration:** HTTP Client calls ke API penyedia AI (OpenAI/Anthropic) menggunakan *endpoint Chat Completions*.
## 6. AI Agent Engineering Directives
 * **Backend (Laravel Horizon):** Set parameter timeout pada Job menjadi minimal 120 detik, mengingat proses API LLM bisa memakan waktu cukup lama dibandingkan *query database* biasa.
 * **Data Traceability:** Sebelum memasukkan data JSON ke tabel operasional (seperti incidents), pastikan seluruh struktur JSON mentah (*Raw Payload*) tersimpan di tabel ai_runs untuk keperluan *audit trail* dan pemecahan masalah (*debugging*).
