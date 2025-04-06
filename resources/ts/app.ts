import './bootstrap';
import { createInertiaApp } from '@inertiajs/svelte';
import Layout from './Pages/Layout.svelte';
import type { PageProps } from './types/index';

createInertiaApp({
  resolve: (name: string) => {
    const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
    let page: any = pages[`./Pages/${name}.svelte`];
    return { default: page.default, layout: page.layout || Layout };
  },
  setup({ el, App, props }:{el: any, App: any, props: PageProps}) {
    new App({ target: el, props });
  },
  progress: {
      color: '#da532c',
  },
})
