<script lang="ts">
    import { Card, CardBody, CardFooter, CardActions } from "yesvelte";
    import { router } from '@inertiajs/svelte';

    import { FormInput, Button, Fieldset } from "yesvelte";
    import AutoCompleteFromServer from "$lib/Components/AutoCompleteFromServer.svelte";
    import FormImgSelect from '$lib/Components/FormImgSelect.svelte';


    import i18n from "$lib/i18n";

    export let submitUrl: string;
    export let config: any;
    export let dataUrl: string;
    export let formData: any;
    export let title: string;

    let values: any;
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
</script>
<Card {title} mb="3">
    <form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
        <CardBody>
            <Fieldset>
                <slot {values} {errors}></slot>
            </Fieldset>
        </CardBody>
        <CardFooter>
            <CardActions d="flex" gap="2">
                <Button type="reset" on:click={onReset}>{$i18n.t('server:auth.reset')}</Button>
                <Button color="primary" type="submit">{$i18n.t('server:auth.submit')}</Button>
            </CardActions>
        </CardFooter>
    </form>
</Card>
