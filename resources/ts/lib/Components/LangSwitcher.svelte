<script lang="ts">
    import { El, Select } from "yesvelte";
    import { router } from '@inertiajs/svelte';

    export let locale: string = '';
    export let locales: string[] = [];
    export let fullreload: boolean = false;

    let value: string = locale;

    const onSelect = () => {
        let url = window.location.href.replace('/' + locale, '/' + value);
        if (!fullreload)
            router.get(url, {}, { preserveState: true });
        else
            window.location.href = url;
        //$i18n.changeLanguage(value); done in layout
    };
</script>
<Select colMd="1" items={locales} bind:value={value} on:click={onSelect}/>
