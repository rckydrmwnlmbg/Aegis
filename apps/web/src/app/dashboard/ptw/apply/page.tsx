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
  Footprints,
  Briefcase,
  MapPin,
  CalendarClock,
  UserCheck,
  ClipboardCheck,
  Stamp
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
    <div className="min-h-screen bg-slate-50 p-4 md:p-8">
      <div className="max-w-6xl mx-auto">
        <div className="mb-8 flex items-center gap-4">
          <div className="bg-slate-900 p-3 rounded-2xl shadow-sm">
            <ShieldAlert className="w-8 h-8 text-white" />
          </div>
          <div>
            <h1 className="text-3xl font-extrabold text-slate-900 tracking-tight">Permit to Work (PTW) Form</h1>
            <p className="text-slate-500 mt-1 font-medium">HSE Enterprise Standard Document • Complete all mandatory fields.</p>
          </div>
        </div>

        {error && (
          <div className="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 shadow-sm">
            <ShieldAlert className="w-5 h-5 flex-shrink-0" />
            <span className="font-bold">{error}</span>
          </div>
        )}

        {/* BENTO BOX CONTAINER */}
        <div className="bg-white rounded-3xl shadow-sm p-6 md:p-10">

          <form className="grid grid-cols-1 md:grid-cols-2 gap-6">

            {/* SECTION 1 - GENERAL INFO */}
            <div className="col-span-1 md:col-span-2 bg-slate-50 rounded-2xl p-6 border border-slate-100">
              <div className="mb-6 flex items-center gap-3">
                <span className="bg-slate-900 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">1</span>
                <h2 className="text-xl font-extrabold text-slate-800 uppercase tracking-wide">General Information</h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label htmlFor="title" className="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-xs">
                    <Briefcase className="w-4 h-4" /> Job Title
                  </label>
                  <input
                    type="text"
                    id="title"
                    name="title"
                    required
                    value={formData.title}
                    onChange={handleChange}
                    className="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all shadow-sm"
                    placeholder="e.g., Welding on Main Pipe"
                  />
                </div>
                <div>
                  <label htmlFor="location" className="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-xs">
                    <MapPin className="w-4 h-4" /> Location / Area
                  </label>
                  <input
                    type="text"
                    id="location"
                    name="location"
                    required
                    value={formData.location}
                    onChange={handleChange}
                    className="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all shadow-sm"
                    placeholder="e.g., Sector 4, Plant B"
                  />
                </div>
                <div>
                  <label htmlFor="valid_from" className="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-xs">
                    <CalendarClock className="w-4 h-4" /> Valid From
                  </label>
                  <input
                    type="datetime-local"
                    id="valid_from"
                    name="valid_from"
                    required
                    value={formData.valid_from}
                    onChange={handleChange}
                    className="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all shadow-sm"
                  />
                </div>
                <div>
                  <label htmlFor="valid_until" className="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-xs">
                    <CalendarClock className="w-4 h-4" /> Valid Until
                  </label>
                  <input
                    type="datetime-local"
                    id="valid_until"
                    name="valid_until"
                    required
                    value={formData.valid_until}
                    onChange={handleChange}
                    className="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all shadow-sm"
                  />
                </div>
              </div>
            </div>

            {/* SECTION 2 - PERMIT CATEGORY */}
            <div className="col-span-1 md:col-span-2 bg-slate-50 rounded-2xl p-6 border border-slate-100">
              <div className="mb-6 flex items-center gap-3">
                <span className="bg-slate-900 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">2</span>
                <h2 className="text-xl font-extrabold text-slate-800 uppercase tracking-wide">Permit Category</h2>
              </div>
              <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                {permitCategories.map((cat) => {
                  const Icon = cat.icon;
                  const isSelected = formData.permit_category === cat.value;
                  return (
                    <label
                      key={cat.value}
                      className={`cursor-pointer flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all ${
                        isSelected
                          ? 'border-slate-900 bg-slate-900 text-white shadow-md'
                          : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-100 shadow-sm'
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
                      <span className="text-sm font-extrabold text-center leading-tight">{cat.label}</span>
                    </label>
                  );
                })}
              </div>
            </div>

            {/* SECTION 3 - HAZARDS (JSA) */}
            <div className="col-span-1 bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col">
              <div className="mb-6 flex items-center gap-3">
                <span className="bg-slate-900 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">3</span>
                <h2 className="text-xl font-extrabold text-slate-800 uppercase tracking-wide">Hazard ID (JSA)</h2>
              </div>
              <div className="grid grid-cols-1 gap-3 mb-6 flex-grow">
                {hazardList.map((hazard) => (
                  <label key={hazard} className="flex items-center gap-4 p-3.5 rounded-xl border border-slate-200 bg-white cursor-pointer hover:bg-slate-50 transition-colors shadow-sm group">
                    <input
                      type="checkbox"
                      checked={formData.hazards.includes(hazard)}
                      onChange={() => handleCheckboxChange('hazards', hazard)}
                      className="w-5 h-5 text-slate-900 border-slate-300 rounded focus:ring-slate-900"
                    />
                    <span className="font-bold text-slate-700 group-hover:text-slate-900">{hazard}</span>
                  </label>
                ))}
              </div>
              <div className="mt-auto">
                <label htmlFor="risk_mitigation" className="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider text-xs">Risk Mitigation Plan</label>
                <textarea
                  id="risk_mitigation"
                  name="risk_mitigation"
                  rows={4}
                  value={formData.risk_mitigation}
                  onChange={handleChange}
                  className="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all shadow-sm"
                  placeholder="Detail strict steps taken to mitigate risks..."
                />
              </div>
            </div>

            {/* SECTION 4 - PPE */}
            <div className="col-span-1 bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col">
              <div className="mb-6 flex items-center gap-3">
                <span className="bg-slate-900 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">4</span>
                <h2 className="text-xl font-extrabold text-slate-800 uppercase tracking-wide">Mandatory PPE</h2>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-grow">
                {ppeList.map((ppe) => {
                  const Icon = ppe.icon;
                  const isChecked = formData.ppe.includes(ppe.value);
                  return (
                    <label key={ppe.value} className={`cursor-pointer flex items-center gap-3 p-4 rounded-xl border transition-all shadow-sm ${
                      isChecked
                        ? 'border-slate-900 bg-white ring-2 ring-slate-900'
                        : 'border-slate-200 bg-white hover:bg-slate-50'
                    }`}>
                      <input
                        type="checkbox"
                        checked={isChecked}
                        onChange={() => handleCheckboxChange('ppe', ppe.value)}
                        className="sr-only"
                      />
                      <div className={`p-2.5 rounded-lg ${isChecked ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-500'}`}>
                        <Icon className="w-5 h-5" />
                      </div>
                      <span className={`font-extrabold text-sm ${isChecked ? 'text-slate-900' : 'text-slate-600'}`}>{ppe.value}</span>
                    </label>
                  );
                })}
              </div>
            </div>

            {/* SECTION 5 - AUTHORIZATION */}
            <div className="col-span-1 md:col-span-2 bg-slate-900 rounded-2xl p-6 shadow-md text-white">
              <div className="mb-6 flex items-center gap-3">
                <span className="bg-white text-slate-900 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-sm">5</span>
                <h2 className="text-xl font-extrabold uppercase tracking-wide">Authorization Chain</h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label htmlFor="applicant_name" className="flex items-center gap-2 text-sm font-bold text-slate-300 mb-2 uppercase tracking-wider text-xs">
                    <UserCheck className="w-4 h-4" /> Applicant Name
                  </label>
                  <select
                    id="applicant_name"
                    name="applicant_name"
                    value={formData.applicant_name}
                    onChange={handleChange}
                    className="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white font-medium focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Applicant</option>
                    <option value="john_doe">John Doe</option>
                    <option value="jane_smith">Jane Smith</option>
                  </select>
                </div>
                <div>
                  <label htmlFor="safety_officer" className="flex items-center gap-2 text-sm font-bold text-slate-300 mb-2 uppercase tracking-wider text-xs">
                    <ClipboardCheck className="w-4 h-4" /> Safety Officer Reviewer
                  </label>
                  <select
                    id="safety_officer"
                    name="safety_officer"
                    value={formData.safety_officer}
                    onChange={handleChange}
                    className="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white font-medium focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Safety Officer</option>
                    <option value="michael_scott">Michael Scott</option>
                    <option value="pam_beesly">Pam Beesly</option>
                  </select>
                </div>
                <div>
                  <label htmlFor="site_manager" className="flex items-center gap-2 text-sm font-bold text-slate-300 mb-2 uppercase tracking-wider text-xs">
                    <Stamp className="w-4 h-4" /> Site Manager Approver
                  </label>
                  <select
                    id="site_manager"
                    name="site_manager"
                    value={formData.site_manager}
                    onChange={handleChange}
                    className="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white font-medium focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent transition-all appearance-none"
                  >
                    <option value="">Select Site Manager</option>
                    <option value="dwight_schrute">Dwight Schrute</option>
                    <option value="jim_halpert">Jim Halpert</option>
                  </select>
                </div>
              </div>
            </div>

            {/* ACTION BUTTONS */}
            <div className="col-span-1 md:col-span-2 pt-6 mt-4 flex flex-col sm:flex-row justify-end items-center gap-4">
               <button
                type="button"
                onClick={(e) => handleSubmit(e, true)}
                disabled={loading}
                className="w-full sm:w-auto px-8 py-3.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all font-extrabold focus:outline-none focus:ring-2 focus:ring-slate-300"
              >
                Save Draft
              </button>
              <button
                type="button"
                onClick={(e) => handleSubmit(e, false)}
                disabled={loading}
                className="w-full sm:w-auto px-8 py-3.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-all font-extrabold shadow-lg shadow-slate-900/30 flex items-center justify-center min-w-[200px] focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 uppercase tracking-wide text-sm"
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
