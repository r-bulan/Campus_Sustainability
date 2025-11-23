import React from 'react';
import { createRoot } from 'react-dom/client';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import Dashboard from './pages/Dashboard';

const queryClient = new QueryClient();

const el = document.getElementById('dashboard-root');
if (el) {
  const root = createRoot(el);
  root.render(
    <React.StrictMode>
      <QueryClientProvider client={queryClient}>
        <Dashboard />
      </QueryClientProvider>
    </React.StrictMode>
  );
}
