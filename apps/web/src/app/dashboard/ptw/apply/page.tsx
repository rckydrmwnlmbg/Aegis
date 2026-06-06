'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import api from '../../../../lib/api';
import { isAxiosError } from 'axios';

export default function ApplyPTW() {
  const router = useRouter();
  const [formData, setFormData] = useState({
    title: '',
    work_scope: '',
    valid_from: '',
    valid_until: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const payload = {
        id: crypto.randomUUID(),
        ...formData,
      };

      await api.post('/v1/ptw', payload);
      router.push('/dashboard/ptw');
    } catch (err: unknown) {
      if (isAxiosError(err)) {
        setError(err.response?.data?.error?.message || 'Failed to submit application.');
      } else {
        setError('Failed to submit application.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50/50 via-purple-50/50 to-pink-50/50 p-8">
      <div className="max-w-3xl mx-auto">
        <h1 className="text-3xl font-bold text-gray-800 mb-8 drop-shadow-sm">Permit to Work Application</h1>

        <div className="bg-white/60 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_32px_rgba(0,0,0,0.05)] border border-white/40 p-8">
          {error && (
            <div className="mb-6 p-4 rounded-3xl bg-hse-red/10 border border-hse-red/20 text-hse-red">
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="title" className="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
              <input
                type="text"
                id="title"
                name="title"
                required
                value={formData.title}
                onChange={handleChange}
                className="w-full bg-white/50 border border-white/60 rounded-3xl px-6 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/80 transition-all shadow-sm"
                placeholder="Enter job title"
              />
            </div>

            <div>
              <label htmlFor="work_scope" className="block text-sm font-medium text-gray-700 mb-2">Work Scope</label>
              <textarea
                id="work_scope"
                name="work_scope"
                rows={4}
                required
                value={formData.work_scope}
                onChange={handleChange}
                className="w-full bg-white/50 border border-white/60 rounded-[2rem] px-6 py-4 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/80 transition-all shadow-sm"
                placeholder="Describe the scope of work"
              />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label htmlFor="valid_from" className="block text-sm font-medium text-gray-700 mb-2">Valid From</label>
                <input
                  type="date"
                  id="valid_from"
                  name="valid_from"
                  required
                  value={formData.valid_from}
                  onChange={handleChange}
                  className="w-full bg-white/50 border border-white/60 rounded-3xl px-6 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/80 transition-all shadow-sm"
                />
              </div>

              <div>
                <label htmlFor="valid_until" className="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                <input
                  type="date"
                  id="valid_until"
                  name="valid_until"
                  required
                  value={formData.valid_until}
                  onChange={handleChange}
                  className="w-full bg-white/50 border border-white/60 rounded-3xl px-6 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/80 transition-all shadow-sm"
                />
              </div>
            </div>

            <div className="pt-6 flex justify-end space-x-4">
              <button
                type="button"
                onClick={() => router.push('/dashboard/ptw')}
                className="px-6 py-3 bg-gray-500/10 text-gray-700 rounded-3xl backdrop-blur-md hover:bg-gray-500/20 transition-all font-semibold shadow-sm"
              >
                Cancel
              </button>
              <button
                type="submit"
                disabled={loading}
                className="px-6 py-3 bg-blue-600/90 text-white rounded-3xl backdrop-blur-md hover:bg-blue-600 transition-all font-semibold shadow-sm flex items-center justify-center min-w-[120px]"
              >
                {loading ? (
                  <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                ) : (
                  'Submit Application'
                )}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
