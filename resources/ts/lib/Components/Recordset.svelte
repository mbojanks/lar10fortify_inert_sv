<script lang="ts">
    import { router, Link } from '@inertiajs/svelte';
    import { onMount } from "svelte";
    export let dataUrl: string | undefined = undefined;
    export let formData: any = undefined;

    onMount(() => {
        refresh();
    });

    const refresh = () => {
        if (dataUrl) {
            router.get(dataUrl);
        }
    };

</script>
<Link>Test: { JSON.stringify(formData) }</Link>
