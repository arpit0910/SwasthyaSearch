import { build } from 'esbuild';

await build({
  entryPoints: ['resources/js/arogio.jsx'],
  bundle: true,
  minify: true,
  outfile: 'public/build/arogio.js',
  target: ['es2020'],
  define: { 'process.env.NODE_ENV': '"production"' },
  legalComments: 'eof',
});
