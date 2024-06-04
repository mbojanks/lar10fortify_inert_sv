<script lang="ts">
import { Card, CardBody, El, FormInput, CardFooter, CardActions, Button } from "yesvelte";
import { router } from '@inertiajs/svelte';
import type { ResetPasswordErrors } from "$lib/../types";
import i18n from "$lib/i18n";
export let locale: string = '';
export let emailfield: string = 'email';
export let token: string;

let form: HTMLFormElement;

const onSubmit = () => {
    router.post(`${locale ? '/' + locale : ''}/reset-password`, values);
};

const onReset = () => {
    form?.reset();
};
export let errors: ResetPasswordErrors = {};
let values = {
    [emailfield]: '',
    password: null,
    password_confirmation: null,
    token
};
</script>
<Card title={$i18n.t('server:auth.pwdreset')} mb="3">
	<form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
		<CardBody>
			<El row>
                <FormInput label={$i18n.t('server:auth.email')} name={emailfield} bind:value={values.email} state={errors.email ? 'invalid' : 'valid'} hint={errors.email}></FormInput>
                <FormInput label={$i18n.t('server:auth.passwordnew')} type="password" name="password" bind:value={values.password} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
                <FormInput label={$i18n.t('server:auth.passwordconfirm')} type="password" name="password_confirmation" bind:value={values.password_confirmation} state={errors.password_confirmation ? 'invalid' : 'valid'} hint={errors.password_confirmation}></FormInput>
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
