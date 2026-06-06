'use client';

import Link from 'next/link';
import { motion, Variants } from 'framer-motion';

export default function Home() {
  const containerVariants: Variants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1,
      },
    },
  };

  const itemVariants: Variants = {
    hidden: { opacity: 0, y: 30 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.8, ease: "easeOut" },
    },
  };

  return (
    <main className="min-h-screen bg-white text-slate-900 overflow-hidden selection:bg-slate-900 selection:text-white">
      <div className="max-w-[1440px] mx-auto px-6 sm:px-12 lg:px-24 pt-32 pb-24 min-h-screen flex flex-col justify-center">
        {/* Hero Section */}
        <motion.section
          className="max-w-5xl"
          variants={containerVariants}
          initial="hidden"
          animate="visible"
        >
          <motion.div variants={itemVariants} className="mb-8">
            <div className="inline-flex items-center space-x-3 bg-slate-100 rounded-full px-4 py-2 text-sm font-semibold tracking-tight text-slate-800">
              <span className="w-2.5 h-2.5 rounded-full bg-slate-900 animate-pulse"></span>
              <span>Next Generation HSE Platform</span>
            </div>
          </motion.div>

          <motion.h1
            variants={itemVariants}
            className="text-7xl sm:text-8xl lg:text-[7.5rem] font-black tracking-tighter leading-[0.95] mb-8"
          >
            AEGIS
            <br />
            <span className="text-slate-400">INTELLIGENCE.</span>
          </motion.h1>

          <motion.div variants={itemVariants} className="max-w-xl mb-16 ml-2">
            <p className="text-2xl text-slate-600 font-medium leading-snug tracking-tight">
              Enterprise safety system engineered for total visibility and predictive compliance.
            </p>
          </motion.div>

          <motion.div variants={itemVariants}>
            <Link
              href="/login"
              className="inline-flex items-center justify-center px-10 py-5 text-lg font-bold rounded-full text-white bg-slate-900 hover:bg-slate-800 transition-transform hover:-translate-y-1"
            >
              Access Platform
            </Link>
          </motion.div>
        </motion.section>

        {/* Features Section */}
        <motion.section
          className="grid grid-cols-1 md:grid-cols-3 gap-12 mt-32 border-t border-slate-200 pt-16"
          variants={containerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, margin: "-100px" }}
        >
          {/* Feature 1 */}
          <motion.div variants={itemVariants} className="space-y-4">
            <div className="w-12 h-12 bg-slate-900 text-white flex items-center justify-center rounded-2xl mb-6">
              <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 className="text-2xl font-bold tracking-tight">Smart Capture Triage</h3>
            <p className="text-slate-600 text-lg leading-relaxed font-medium">
              AI-assisted incident reporting. Capture hazards with photos and let the system auto-categorize.
            </p>
          </motion.div>

          {/* Feature 2 */}
          <motion.div variants={itemVariants} className="space-y-4">
            <div className="w-12 h-12 bg-slate-900 text-white flex items-center justify-center rounded-2xl mb-6">
              <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 className="text-2xl font-bold tracking-tight">Digital Permit to Work</h3>
            <p className="text-slate-600 text-lg leading-relaxed font-medium">
              Fully digital PTW workflows with automated clash detection and remote approvals.
            </p>
          </motion.div>

          {/* Feature 3 */}
          <motion.div variants={itemVariants} className="space-y-4">
            <div className="w-12 h-12 bg-slate-900 text-white flex items-center justify-center rounded-2xl mb-6 relative">
              <div className="absolute top-[-4px] right-[-4px] w-3 h-3 bg-white rounded-full border-2 border-slate-900"></div>
              <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 className="text-2xl font-bold tracking-tight">Offline-First Inspections</h3>
            <p className="text-slate-600 text-lg leading-relaxed font-medium">
              True offline-first capability ensures you never lose data in remote sites.
            </p>
          </motion.div>
        </motion.section>
      </div>
    </main>
  );
}
