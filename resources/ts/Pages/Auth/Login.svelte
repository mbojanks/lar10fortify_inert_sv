<script lang="ts">
import { Card, CardBody, El, FormInput, CardFooter, CardActions, Button } from "yesvelte";
import { router, inertia } from '@inertiajs/svelte';
import type {LoginErrors} from "$lib/../types";
import i18n from "$lib/i18n";
export let locale: string = '';

let loc: string;
$: loc = locale ? '/' + locale : '';

const onSubmit = () => {
    router.post(`${loc}/login`, values);
};
export let errors: LoginErrors = {};
let values = {
    email: null,
    password: null,
};
</script>
<Card title={$i18n.t('server:auth.login')} mb="3">
	<form method="POST" action="#" on:submit|preventDefault={onSubmit}>
		<CardBody>
			<El row>
                <FormInput label={$i18n.t('server:auth.email')} name="email" bind:value={values.email} state={errors.email ? 'invalid' : 'valid'} hint={errors.email}></FormInput>
                <FormInput label={$i18n.t('server:auth.passwordonform')} name="password" type="password" bind:value={values.password} state={errors.password ? 'invalid' : 'valid'} hint={errors.password}></FormInput>
            </El>
        </CardBody>
        <CardFooter>
            <a class="y-button-link y-button-color-primary" use:inertia href={`${loc}/forgot-password`}>{$i18n.t('server:auth.forgotpwd')}</a>
			<CardActions d="flex" gap="2">
				<Button type="reset">{$i18n.t('server:auth.reset')}</Button>
				<Button color="primary" type="submit">{$i18n.t('server:auth.submit')}</Button>
			</CardActions>
		</CardFooter>
    </form>
</Card>
