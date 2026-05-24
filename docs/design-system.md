# Design System & UI/UX Guidelines
**Project:** Aegis AI EHS Platform
**Target AI Agents:** Frontend Coders (Flutter & Next.js)
## 1. Core Philosophy
Desain Aegis harus mengutamakan fungsi di atas estetika (*Utilitarian First*). Pengguna di lapangan mungkin menggunakan sarung tangan, berada di bawah terik matahari, atau dalam kondisi panik. UI harus memiliki kontras tinggi, tombol besar (mudah ditekan), dan minim teks yang tidak perlu.
## 2. Color Palette (Tailwind & Flutter Colors)
Gunakan kode warna ini secara ketat. Jangan biarkan AI Agent menebak warna abu-abu.
 * **Primary (Brand):** Navy Blue #0F172A (Untuk header, tombol utama).
 * **Background:** Light Gray #F8FAFC (Agar kontras dengan form putih).
 * **Surface:** White #FFFFFF (Untuk Card, Modal, Bottom Sheet).
 * **Text Primary:** Slate 900 #0F172A
 * **Text Secondary:** Slate 500 #64748B
**Safety Semantic Colors (Wajib untuk Status/Badge):**
 * **Danger/High Risk/Incident:** Red #DC2626
 * **Warning/Hazard/Medium Risk:** Amber #D97706
 * **Safe/Approved/Low Risk:** Emerald #16A34A
 * **Info/Processing/AI Draft:** Blue #2563EB
## 3. Typography
 * **Web (Next.js):** Inter (sans-serif). Bersih dan sangat terbaca untuk tabel data padat.
 * **Mobile (Flutter):** Roboto (Android) / San Francisco (iOS) bawaan sistem untuk performa maksimal.
 * **Base Size:** 16px untuk teks tubuh (*body text*). Jangan gunakan ukuran di bawah 12px.
## 4. Mobile Component Standards (Flutter)
 1. **Buttons:** Minimal tinggi 48dp (aturan jempol/ *thumb-friendly*).
 2. **Forms:** Selalu gunakan label di luar input (*Outside Label*). Jangan gunakan *placeholder* sebagai label karena akan hilang saat pengguna mulai mengetik.
 3. **Bottom Navigation:** Maksimal 4 tab utama (Home, Inspeksi, JSA, Profil).
 4. **Offline States:** Jika data belum tersinkronisasi, berikan *badge* kecil bergaris miring pada ikon awan di pojok kanan atas layar/kartu data.
## 5. Web Component Standards (Next.js / Tailwind)
 1. **Data Tables:** Harus selalu mendukung *horizontal scroll* dan memiliki fitur *Sticky Header*.
 2. **Modals:** Gunakan *slide-over* (panel dari kanan) untuk form panjang (seperti membuat CAPA), gunakan *center modal* hanya untuk konfirmasi (Ya/Tidak).
