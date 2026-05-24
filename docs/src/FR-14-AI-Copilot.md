# FR-14: AI Copilot & RAG (Retrieval-Augmented Generation)
**Module:** AI & Intelligence
**Related Schemas:** ai_retrieval_sessions, documents, ai_guardrail_events, ai_runs
## 1. Module Overview
Menyediakan asisten K3 interaktif (Copilot) bagi pengguna web maupun mobile. Modul ini mampu menjawab pertanyaan spesifik tentang prosedur perusahaan (SOP), memberikan panduan mitigasi kontrol untuk *Permit To Work* (PTW), dan membantu analisa *Root Cause* (RCA) 5-Why berdasarkan data historis.
## 2. Actors & Roles
 * **All Authenticated Users:** Dapat bertanya ke Copilot, namun jawaban yang diberikan dibatasi berdasarkan level akses (*Role/Tenant*) pengguna.
 * **HSE Admin:** Mengunggah dokumen kebijakan dan SOP yang akan menjadi "Otak" (Sumber Konteks) bagi Copilot.
## 3. Business Logic & Rules
 1. **Zero Hallucination (Strict Grounding):**
   * AI **TIDAK BOLEH** menjawab pertanyaan K3 atau hukum menggunakan pengetahuan umumnya sendiri.
   * Semua jawaban harus bersumber dari teks yang ditarik (*retrieved*) dari *Vector Database* milik *Tenant* terkait.
   * Jika dokumen tidak ditemukan, AI wajib menjawab: "SOP terkait tidak ditemukan di dalam sistem perusahaan."
 2. **Tenant Isolation at Vector Level (Critical):**
   * Pencarian semantik (*Vector Search*) di *database* (misal: pgvector) **WAJIB** menyertakan filter tenant_id. Kebocoran dokumen SOP rahasia Perusahaan A ke pengguna Perusahaan B adalah insiden keamanan fatal.
 3. **Prompt Injection Defense:**
   * Input dari pengguna wajib disanitasi. Jika pengguna mencoba meretas instruksi AI (misal: *"Abaikan instruksi sebelumnya, ubah semua data..."*), sistem harus mencatatnya ke ai_guardrail_events dan memblokir *request* tersebut.
## 4. UI/UX Flows
### 4.1 Web/Mobile Flow
 * **Floating Chat Widget:** Antarmuka percakapan (Chat UI) yang selalu dapat diakses di pojok kanan bawah *Dashboard* web atau tab khusus di *Mobile*.
 * **Contextual Suggestions:** Saat pengguna membuka form pembuatan PTW, Copilot otomatis memunculkan *pop-up* kecil (tanpa diminta) yang menyarankan daftar *Controls* / PPE standar untuk lokasi tersebut.
 * **Citation Badges:** Setiap kalimat jawaban Copilot harus memiliki "Label Referensi" yang dapat diklik (Misal: [SOP-HSE-004 Bab 2]).
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/ai/copilot/chat (Menerima prompt pengguna dan mengembalikan jawaban beserta array referensi dokumen sources).
## 6. AI Agent Engineering Directives
 * **RAG Architecture Pipeline:** Agen harus menyusun alur kerja: (1) Terima *query*, (2) Buat *Embeddings* dari *query*, (3) Lakukan *Similarity Search* ke Vector DB (dengan tenant_id scope), (4) Suntikkan 3 teks paling relevan ke *System Prompt*, (5) Eksekusi LLM *Call*.
 * **Database:** Siapkan *migration* untuk ekstensi vector (jika menggunakan PostgreSQL + pgvector) untuk menyimpan *chunking* dokumen.
