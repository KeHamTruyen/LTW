import '../../vendor/tabler/dist/css/tabler.min.css';
import '../../vendor/tabler/dist/js/tabler-theme.min.js';
import '../../vendor/tabler/dist/js/tabler.min.js';
import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';

function HelloReact({ name }: { name: string }) {
  return (
    <div className="container">
      <h1>Hello, {name}!</h1>
    </div>
  );
}

function mount() {
  const el = document.getElementById('react-root');
  if (el) {
    const name = el.getAttribute('data-name') || 'React';
    createRoot(el).render(<HelloReact name={name} />);
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mount);
} else {
  mount();
}
