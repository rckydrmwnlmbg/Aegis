'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import api from '../../../../lib/api';
import { isAxiosError } from 'axios';
import {
  Flame,
  Snowflake,
  BoxSelect,
  ArrowUpToLine,
  Zap,
  HardHat,
  Glasses,
  Ear,
  Wind,
  ShieldAlert,
  Footprints
} from 'lucide-react';

export default function ApplyPTW() {
  const router = useRouter();

  const [formData, setFormData] = useState({
    title: '',
    location: '',
    valid_from: '',
    valid_until: '',
    permit_category: '',
    hazards: [] as string[],
    risk_mitigation: '',
    ppe: [] as string[],
    applicant_name: '',
    safety_officer: '',
    site_manager: ''
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleCheckboxChange = (name: 'hazards' | 'ppe', value: string) => {
    setFormData((prev) => {
      const list = prev[name];
      if (list.includes(value)) {
        return { ...prev, [name]: list.filter((i) => i !== value) };
      } else {
        return { ...prev, [name]: [...list, value] };
      }
    });
  };

  const handleSubmit = async (e: React.FormEvent, isDraft: boolean = false) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const payload = {
        id: crypto.randomUUID(),
        ...formData,
        status: isDraft ? 'draft' : 'pending',
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

  const permitCategories = [
    { value: 'hot_work', label: 'Hot Work', icon: Flame },
    { value: 'cold_work', label: 'Cold Work', icon: Snowflake },
    { value: 'confined_space', label: 'Confined Space', icon: BoxSelect },
    { value: 'working_at_height', label: 'Working at Height', icon: ArrowUpToLine },
    { value: 'electrical_loto', label: 'Electrical/LOTO', icon: Zap },
  ];

  const hazardList = [
    'Flammable Gas',
    'High Voltage',
    'Toxic Chemical',
    'Extreme Height',
    'Moving Machinery',
  ];

  const ppeList = [
    { value: 'Safety Helmet', icon: HardHat },
    { value: 'Safety Glasses', icon: Glasses },
    { value: 'Ear Protection', icon: Ear },
    { value: 'Respirator', icon: Wind },
    { value: 'Full Body Harness', icon: ShieldAlert },
    { value: 'Safety Boots', icon: Footprints },
  ];

  return (
    <div className="min-h-screen bg-slate-50 p-6 md:p-8">
      <div className="max-w-5xl mx-auto">
        <div className="mb-8">
          <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Permit to Work Application</h1>
          <p className="text-slate-500 mt-2">Complete the form below to apply for a new work permit.</p>
        </div>

        <div className="bg-white rounded-3xl shadow-sm p-6 md:p-10 border border-slate-200/60">
          {error && (
            <div className="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3">
              <ShieldAlert className="w-5 h-5" />
              <span className="font-medium">{error}</span>
            </div>
          )}

          <form className="space-y-10">
            {/* SECTION 1 - GENERAL INFO */}
            <section>
              <div className="mb-6 pb-2 border-b border-slate-100">
                <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                  <span className="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                  General Information
                </h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label htmlFor="title" className="block text-sm font-semibold text-slate-700 mb-2">Job Title</label>
                  <input
                    type="text"
                    id="title"
                    name="title"
                    required
                    value={formData.title}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"
                    placeholder="E.g., Welding on Main Pipe"
                  />
                </div>
                <div>
                  <label htmlFor="location" className="block text-sm font-semibold text-slate-700 mb-2">Location / Area</label>
                  <input
                    type="text"
                    id="location"
                    name="location"
                    required
                    value={formData.location}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"
                    placeholder="E.g., Sector 4, Plant B"
                  />
                </div>
                <div>
                  <label htmlFor="valid_from" className="block text-sm font-semibold text-slate-700 mb-2">Valid From</label>
                  <input
                    type="datetime-local"
                    id="valid_from"
                    name="valid_from"
                    required
                    value={formData.valid_from}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"
                  />
                </div>
                <div>
                  <label htmlFor="valid_until" className="block text-sm font-semibold text-slate-700 mb-2">Valid Until</label>
                  <input
                    type="datetime-local"
                    id="valid_until"
                    name="valid_until"
                    required
                    value={formData.valid_until}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"
                  />
                </div>
              </div>
            </section>

            {/* SECTION 2 - PERMIT CATEGORY */}
            <section>
              <div className="mb-6 pb-2 border-b border-slate-100">
                <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                   <span className="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                  Permit Category
                </h2>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                {permitCategories.map((cat) => {
                  const Icon = cat.icon;
                  const isSelected = formData.permit_category === cat.value;
                  return (
                    <label
                      key={cat.value}
                      className={`cursor-pointer flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all ${
                        isSelected
                          ? 'border-slate-900 bg-slate-900 text-white'
                          : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50'
                      }`}
                    >
                      <input
                        type="radio"
                        name="permit_category"
                        value={cat.value}
                        checked={isSelected}
                        onChange={handleChange}
                        className="sr-only"
                      />
                      <Icon className="w-8 h-8 mb-3" />
                      <span className="text-sm font-semibold text-center leading-tight">{cat.label}</span>
                    </label>
                  );
                })}
              </div>
            </section>

            {/* SECTION 3 - HAZARDS (JSA) */}
            <section>
              <div className="mb-6 pb-2 border-b border-slate-100">
                <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                   <span className="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                  Hazard Identification (JSA)
                </h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                {hazardList.map((hazard) => (
                  <label key={hazard} className="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                    <input
                      type="checkbox"
                      checked={formData.hazards.includes(hazard)}
                      onChange={() => handleCheckboxChange('hazards', hazard)}
                      className="w-5 h-5 text-slate-900 border-slate-300 rounded focus:ring-slate-900"
                    />
                    <span className="font-medium text-slate-700">{hazard}</span>
                  </label>
                ))}
              </div>
              <div>
                <label htmlFor="risk_mitigation" className="block text-sm font-semibold text-slate-700 mb-2">Risk Mitigation Plan</label>
                <textarea
                  id="risk_mitigation"
                  name="risk_mitigation"
                  rows={4}
                  value={formData.risk_mitigation}
                  onChange={handleChange}
                  className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"
                  placeholder="Detail the steps taken to mitigate the identified risks..."
                />
              </div>
            </section>

            {/* SECTION 4 - PPE */}
            <section>
              <div className="mb-6 pb-2 border-b border-slate-100">
                <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                   <span className="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                  Mandatory PPE
                </h2>
              </div>
              <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                {ppeList.map((ppe) => {
                  const Icon = ppe.icon;
                  const isChecked = formData.ppe.includes(ppe.value);
                  return (
                    <label key={ppe.value} className={`cursor-pointer flex items-center gap-3 p-4 rounded-xl border transition-all ${
                      isChecked
                        ? 'border-slate-900 bg-slate-50 ring-1 ring-slate-900'
                        : 'border-slate-200 bg-white hover:bg-slate-50'
                    }`}>
                      <input
                        type="checkbox"
                        checked={isChecked}
                        onChange={() => handleCheckboxChange('ppe', ppe.value)}
                        className="sr-only"
                      />
                      <div className={`p-2 rounded-lg ${isChecked ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-500'}`}>
                        <Icon className="w-5 h-5" />
                      </div>
                      <span className={`font-semibold ${isChecked ? 'text-slate-900' : 'text-slate-600'}`}>{ppe.value}</span>
                    </label>
                  );
                })}
              </div>
            </section>

            {/* SECTION 5 - AUTHORIZATION */}
            <section>
              <div className="mb-6 pb-2 border-b border-slate-100">
                <h2 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                   <span className="bg-slate-900 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">5</span>
                  Authorization Chain
                </h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label htmlFor="applicant_name" className="block text-sm font-semibold text-slate-700 mb-2">Applicant Name</label>
                  <select
                    id="applicant_name"
                    name="applicant_name"
                    value={formData.applicant_name}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Applicant</option>
                    <option value="john_doe">John Doe</option>
                    <option value="jane_smith">Jane Smith</option>
                  </select>
                </div>
                <div>
                  <label htmlFor="safety_officer" className="block text-sm font-semibold text-slate-700 mb-2">Safety Officer Reviewer</label>
                  <select
                    id="safety_officer"
                    name="safety_officer"
                    value={formData.safety_officer}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Safety Officer</option>
                    <option value="michael_scott">Michael Scott</option>
                    <option value="pam_beesly">Pam Beesly</option>
                  </select>
                </div>
                <div>
                  <label htmlFor="site_manager" className="block text-sm font-semibold text-slate-700 mb-2">Site Manager Approver</label>
                  <select
                    id="site_manager"
                    name="site_manager"
                    value={formData.site_manager}
                    onChange={handleChange}
                    className="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Site Manager</option>
                    <option value="dwight_schrute">Dwight Schrute</option>
                    <option value="jim_halpert">Jim Halpert</option>
                  </select>
                </div>
              </div>
            </section>

            {/* ACTION BUTTONS */}
            <div className="pt-8 mt-8 border-t border-slate-200 flex flex-col sm:flex-row justify-end items-center gap-4">
               <button
                type="button"
                onClick={(e) => handleSubmit(e, true)}
                disabled={loading}
                className="w-full sm:w-auto px-8 py-3.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all font-bold focus:outline-none focus:ring-2 focus:ring-slate-200"
              >
                Save Draft
              </button>
              <button
                type="button"
                onClick={(e) => handleSubmit(e, false)}
                disabled={loading}
                className="w-full sm:w-auto px-8 py-3.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-all font-bold shadow-md shadow-slate-900/20 flex items-center justify-center min-w-[200px] focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2"
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
