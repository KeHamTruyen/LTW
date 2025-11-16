import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
// No Node.js path helpers needed; use relative paths in config

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
        app: 'resources/js/main.tsx',
      },
    },
  },
});
