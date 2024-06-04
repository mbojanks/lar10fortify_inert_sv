<script lang="ts">
    import { Card, CardBody, CardFooter, CardActions, CardHeader, CardTitle } from "yesvelte";
    import { router } from '@inertiajs/svelte';

    import {  Button } from "yesvelte";

    import i18n from "$lib/i18n";
    import { onMount } from "svelte";

    export let submitUrl: string;
    export let config: any = undefined;
    export let dataUrl: string | undefined = undefined;
    export let formData: any = undefined;
    export let title: string;
    export let description: string;

    export let values: any;
    let errors: any;
    let form: HTMLFormElement | undefined;

    const onSubmit = () => {
        //console.log('values', values);
        router.post(`${submitUrl}`, {...values, "_method":"PUT"}, {
            forceFormData: true, // no need for this from Inertiajs 0.8
        });
    };
    const onReset = () => {
        form?.reset();
    }

    const refreshData = () => {
        if (dataUrl) {
            fetch(dataUrl).then((response) => response.json()).then((json) => {
                formData = json;
            }).catch((err: any) => {
                errors.data = err;
            });
        }
    };

    onMount(() => {
        if (!formData) {
            refreshData();
        }
    });
</script>
<Card mb="3">
    <CardHeader>
        <CardTitle>{title}</CardTitle>
        {description}
    </CardHeader>
    <form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
        <CardBody>
            <slot {values}>
                {#if config}
                    {#each config as confitem}
                        <svelte:component this={confitem.component} />
                    {/each}
                {/if}
            </slot>
        </CardBody>
        <CardFooter>
            <CardActions d="flex" gap="2">
                <slot name="commands">
                    <Button type="reset" on:click={onReset}>{$i18n.t('server:auth.reset')}</Button>
                    <Button color="primary" type="submit">{$i18n.t('server:auth.submit')}</Button>
                </slot>
            </CardActions>
        </CardFooter>
    </form>
</Card>
