import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [
    react(),
  ],
  server: {
    cors: true,
  },
  build: {
    outDir: '../BE/public/assets/build',
    emptyOutDir: false,
    manifest: 'manifest.json',
    rollupOptions: {
      input: {
        app: 'resources/js/main.jsx',
      },
    },
  },
});
