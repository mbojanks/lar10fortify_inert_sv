<script lang="ts">
import { Card, CardBody, El, FormInput, CardFooter, CardActions, Button } from "yesvelte";
import { router } from '@inertiajs/svelte';
import type { ConfirmPasswordErrors } from "$lib/../types";
import i18n from "$lib/i18n";
export let locale: string = '';

let form: HTMLFormElement;

const onSubmit = () => {
    router.post(`${locale ? '/' + locale : ''}/user/confirm-password`, values);
};

const onReset = () => {
    form?.reset();
};
export let errors: ConfirmPasswordErrors = {};
let values = {
    password: null
};
</script>
<Card title={$i18n.t('server:auth.pwdconfirm')} mb="3">
    <!-- ERRORS FROM RESPONSE SHOULD BE DISPLAYED HERE -->
	<form method="POST" action="#" bind:this={form} on:submit|preventDefault={onSubmit}>
		<CardBody>
			<El row>
                <FormInput colMd="auto" label={$i18n.t('server:auth.passwordonform')} type="password" name="password" bind:value={values.password} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
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
