'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Cookies from 'js-cookie';
import axios from 'axios';
import api from '@/lib/api';
import { motion } from 'framer-motion';

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const response = await api.post('/api/v1/auth/login', {
        email,
        password,
      });

      const token = response.data.data?.token || response.data?.token;

      if (token) {
        Cookies.set('token', token, { expires: 7 });
        router.push('/dashboard');
      } else {
        setError('Invalid response from server.');
      }
    } catch (err: unknown) {
      if (axios.isAxiosError(err)) {
        setError(err.response?.data?.error?.message || err.message || "Login failed");
      } else if (err instanceof Error) {
        setError(err.message);
      } else {
        setError("Login failed");
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex selection:bg-slate-900 selection:text-white">
      {/* Left side - Branding (Dark) */}
      <div className="hidden lg:flex lg:w-1/2 bg-slate-950 flex-col justify-between p-16 relative overflow-hidden">
        {/* Subtle geometric overlay instead of gradient */}
        <div className="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white to-transparent pointer-events-none"></div>

        <div className="relative z-10">
          <div className="inline-flex items-center space-x-3 mb-8">
            <span className="w-3 h-3 bg-white rounded-full"></span>
            <span className="text-white font-bold tracking-widest text-sm uppercase">Aegis Systems</span>
          </div>
        </div>

        <div className="relative z-10">
          <h1 className="text-6xl font-black text-white tracking-tighter leading-tight mb-6">
            ENTERPRISE<br/>SECURITY<br/>PROTOCOL.
          </h1>
          <p className="text-slate-400 text-xl font-medium max-w-md">
            Authorized access only. Secure connection established.
          </p>
        </div>
      </div>

      {/* Right side - Login Form (White) */}
      <div className="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 sm:p-16">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, ease: 'easeOut' }}
          className="w-full max-w-md"
        >
          <div className="mb-12">
            <h2 className="text-4xl font-black text-slate-900 tracking-tight mb-2">Sign In</h2>
            <p className="text-slate-500 font-medium text-lg">Enter your credentials to access the platform.</p>
          </div>

          {error && (
            <div className="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r mb-8">
              <p className="font-medium text-sm">{error}</p>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label className="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wide">
                Email Address
              </label>
              <input
                type="email"
                required
                className="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-slate-900 font-medium transition-all"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                disabled={loading}
                placeholder="name@company.com"
              />
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wide">
                Password
              </label>
              <input
                type="password"
                required
                className="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent text-slate-900 font-medium transition-all"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                disabled={loading}
                placeholder="••••••••"
              />
            </div>

            <div className="pt-4">
              <button
                type="submit"
                disabled={loading}
                className="w-full bg-slate-900 text-white font-bold py-4 px-6 rounded-xl hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 disabled:opacity-50 transition-all hover:-translate-y-0.5"
              >
                {loading ? 'Authenticating...' : 'Sign In'}
              </button>
            </div>
          </form>
        </motion.div>
      </div>
    </div>
  );
}
