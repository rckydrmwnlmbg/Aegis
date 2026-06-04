import React from 'react';

interface SkeletonProps {
  className?: string;
}

export const Skeleton: React.FC<SkeletonProps> = ({ className = '' }) => {
  return (
    <div
      className={`animate-pulse bg-white/40 backdrop-blur-md rounded-[2rem] border border-white/30 ${className}`}
    ></div>
  );
};
