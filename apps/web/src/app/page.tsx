'use client';

import Link from 'next/link';
import { motion, Variants } from 'framer-motion';
import { Camera, FileCheck2, WifiOff, ArrowRight, ShieldCheck } from 'lucide-react';

export default function Home() {
  const containerVariants: Variants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.15,
      },
    },
  };

  const itemVariants: Variants = {
    hidden: { opacity: 0, y: 40 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.8, ease: [0.16, 1, 0.3, 1] }, // Custom smooth ease
    },
  };

  return (
    <main className="min-h-screen bg-slate-50 text-slate-900 overflow-x-hidden selection:bg-slate-900 selection:text-white pb-32">
      <div className="max-w-[1440px] mx-auto px-6 sm:px-12 lg:px-24 pt-32">
        {/* HERO SECTION */}
        <motion.section
          className="max-w-5xl mb-32"
          variants={containerVariants}
          initial="hidden"
          animate="visible"
        >
          <motion.div variants={itemVariants} className="mb-8">
            <div className="inline-flex items-center space-x-3 bg-white border border-slate-200 rounded-full px-5 py-2.5 text-sm font-bold tracking-wide text-slate-800 shadow-sm">
              <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span className="uppercase">Aegis Enterprise HSE</span>
            </div>
          </motion.div>

          <motion.h1
            variants={itemVariants}
            className="text-6xl sm:text-7xl lg:text-[6rem] font-extrabold tracking-tighter leading-[1.05] mb-10 text-slate-900"
          >
            Predictive Safety. <br />
            <span className="text-slate-400">Zero Compromise.</span>
          </motion.h1>

          <motion.div variants={itemVariants} className="max-w-2xl mb-14">
            <p className="text-xl sm:text-2xl text-slate-600 font-medium leading-relaxed tracking-tight">
              Sistem HSE intelijen yang dirancang untuk kompleksitas lapangan industri berat. Mengubah data insiden mentah menjadi kepatuhan prediktif.
            </p>
          </motion.div>

          <motion.div variants={itemVariants} className="flex flex-wrap items-center gap-6">
            <Link
              href="/login"
              className="inline-flex items-center justify-center px-10 py-5 text-lg font-bold rounded-full text-white bg-slate-900 hover:bg-slate-800 transition-transform hover:-translate-y-1 shadow-sm"
            >
              Request Demo
              <ArrowRight className="ml-3 w-5 h-5" />
            </Link>
            <Link
              href="#architecture"
              className="inline-flex items-center justify-center px-10 py-5 text-lg font-bold rounded-full text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 transition-transform hover:-translate-y-1 shadow-sm"
            >
              Explore Architecture
            </Link>
          </motion.div>
        </motion.section>

        {/* CORE MODULES SECTION (Bento Grid) */}
        <motion.section
          id="architecture"
          className="mb-32"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          <motion.div variants={itemVariants} className="mb-12">
            <h2 className="text-4xl font-extrabold tracking-tight text-slate-900">Core Architecture</h2>
          </motion.div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {/* Module 1 */}
            <motion.div
              variants={itemVariants}
              className="bg-white rounded-3xl p-10 shadow-sm flex flex-col h-full"
            >
              <div className="w-16 h-16 bg-slate-50 text-slate-900 flex items-center justify-center rounded-2xl mb-8 border border-slate-100">
                <Camera className="w-8 h-8" />
              </div>
              <h3 className="text-2xl font-bold tracking-tight mb-4">AI-Powered Triage</h3>
              <p className="text-slate-600 text-lg leading-relaxed font-medium mt-auto">
                Deteksi bahaya otomatis dari foto lapangan. Algoritma vision kami secara instan mengenali potensi risiko, mempercepat pelaporan.
              </p>
            </motion.div>

            {/* Module 2 */}
            <motion.div
              variants={itemVariants}
              className="bg-white rounded-3xl p-10 shadow-sm flex flex-col h-full md:col-span-2"
            >
              <div className="w-16 h-16 bg-slate-50 text-slate-900 flex items-center justify-center rounded-2xl mb-8 border border-slate-100">
                <FileCheck2 className="w-8 h-8" />
              </div>
              <h3 className="text-2xl font-bold tracking-tight mb-4">Smart PTW Engine</h3>
              <p className="text-slate-600 text-lg leading-relaxed font-medium mt-auto max-w-2xl">
                Alur persetujuan Permit to Work digital dengan deteksi benturan (clash detection). Mencegah pekerjaan berisiko tinggi yang tumpang tindih secara otomatis sebelum izin diterbitkan.
              </p>
            </motion.div>

            {/* Module 3 */}
            <motion.div
              variants={itemVariants}
              className="bg-slate-900 text-white rounded-3xl p-10 shadow-sm flex flex-col h-full md:col-span-3"
            >
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-8 h-full">
                <div className="max-w-3xl">
                  <div className="w-16 h-16 bg-slate-800 text-white flex items-center justify-center rounded-2xl mb-8">
                    <WifiOff className="w-8 h-8" />
                  </div>
                  <h3 className="text-3xl font-extrabold tracking-tight mb-4">Offline-First Sync</h3>
                  <p className="text-slate-300 text-xl leading-relaxed font-medium">
                    Tetap operasional di area kilang atau konstruksi terpencil tanpa sinyal. Arsitektur lokal sinkronisasi ganda memastikan tidak ada data inspeksi yang hilang saat koneksi terputus.
                  </p>
                </div>
                <div className="hidden lg:flex items-center justify-center p-8 bg-slate-800 rounded-2xl">
                  <div className="flex items-center space-x-3 bg-slate-900 rounded-full px-6 py-3 text-sm font-bold tracking-widest text-white uppercase border border-slate-700">
                    <span className="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sync Queue Active</span>
                  </div>
                </div>
              </div>
            </motion.div>
          </div>
        </motion.section>

        {/* THE "WHY US" SECTION */}
        <motion.section
          className="bg-white rounded-3xl p-12 lg:p-20 shadow-sm"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          <div className="max-w-4xl">
            <motion.div variants={itemVariants} className="mb-8">
              <div className="w-16 h-16 bg-slate-50 text-slate-900 flex items-center justify-center rounded-2xl mb-8 border border-slate-100">
                <ShieldCheck className="w-8 h-8" />
              </div>
              <h2 className="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-[1.1] mb-8">
                Built by Field Managers,<br/>For Field Ops.
              </h2>
            </motion.div>
            <motion.div variants={itemVariants}>
              <p className="text-xl sm:text-2xl text-slate-600 font-medium leading-relaxed">
                Kami memahami bahwa keselamatan tidak bisa ditawar. Arsitektur Aegis dibangun berdasarkan standar sertifikasi K3 dan realitas manajemen situs proyek, menghilangkan birokrasi kertas dan memfokuskan tim pada mitigasi risiko.
              </p>
            </motion.div>
          </div>
        </motion.section>
      </div>
    </main>
  );
}
