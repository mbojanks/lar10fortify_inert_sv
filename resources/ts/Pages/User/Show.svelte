<script context="module">
    import { inertia } from '@inertiajs/svelte';
    //export { default as layout } from '../Layout.svelte';
</script>
<script lang="ts">
    import i18n from '$lib/i18n';

    //export let user: User;
    export let message: string | undefined;
    //export let locale: string = '';
</script>
<svelte:head>
    <title>{$i18n.t('server:home.welcome')}</title>
</svelte:head>

{#if message}
    <p>{message}</p>
{/if}
<h1>{$i18n.t('server:home.welcome')}</h1>
