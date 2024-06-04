<script lang="ts">
import { Card, CardBody, El, FormInput, CardFooter, CardActions, Button } from "yesvelte";
import { router, inertia } from '@inertiajs/svelte';
import type {LoginErrors} from "$lib/../types";
import i18n from "$lib/i18n";
export let locale: string = '';
export let emailfield: string = 'email';
export let status: string;

let form: HTMLFormElement;
let loc: string;
$: loc = (locale ? "/" + locale : "");

const onSubmit = () => {
    router.post(`${locale ? '/' + locale : ''}/forgot-password`, values);
};

const onReset = () => {
    form?.reset();
};
export let errors: LoginErrors = {};
let values = {
    [emailfield]: ''
};
</script>
<Card title={$i18n.t('server:auth.forgotpwd')} mb="3">
    {#if status}
        <p>{status}</p>
    {/if}
	<form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
		<CardBody>
			<El row>
                <FormInput label={$i18n.t('server:auth.email')} name="email" bind:value={values.email} state={errors.email ? 'invalid' : 'valid'} hint={errors.email}></FormInput>
            </El>
        </CardBody>
        <CardFooter>
			<CardActions d="flex" gap="2">
				<Button type="reset" on:click={onReset}>{$i18n.t('server:auth.reset')}</Button>
				<Button color="primary" type="submit">{$i18n.t('server:auth.submit')}</Button>
			</CardActions>
		</CardFooter>
    </form>
</Card>
