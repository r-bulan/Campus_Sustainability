import React from 'react';
import { createRoot } from 'react-dom/client';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import Categories from './pages/Categories';

const queryClient = new QueryClient();

const el = document.getElementById('categories-root');
if (el) {
  const root = createRoot(el);
  root.render(
    <React.StrictMode>
      <QueryClientProvider client={queryClient}>
        <Categories />
      </QueryClientProvider>
    </React.StrictMode>
  );
}
