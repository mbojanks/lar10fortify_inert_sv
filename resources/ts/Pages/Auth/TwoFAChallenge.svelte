<script lang="ts">
    import i18n from '$lib/i18n';
    import { useForm } from '@inertiajs/svelte';
    import { onMount } from 'svelte';
    import { tick } from 'svelte';
    import { Card, CardBody, El, FormInput, CardFooter, CardActions, Button } from 'yesvelte';

    export let locale: string = '';
    $: loc = (locale ? locale + '.' : '');

    let recovery: boolean = false;
    $: if (!recovery) {document.querySelector("input[name='code']")?.parentElement?.focus()} else {document.querySelector("input[name='recovery']")?.parentElement?.focus()}

    const form = useForm({
        code: '',
        recovery_code: ''
    });

    const submit = () => {
        $form.post(route(`${loc}two-factor.login`));
    };

    const toggleRecovery = async () => {
        recovery = !recovery;

        await tick();

        if (recovery) {
            //recoveryCodeInput.focus();
            document.querySelector<HTMLElement>("input[name='recovery_code']")?.focus();
            $form.code = '';
        } else {
            //codeInput.focus();
            document.querySelector<HTMLElement>("input[name='code']")?.focus();
            $form.recovery_code = '';
        }
    };

    onMount(() => {
        document.querySelector<HTMLElement>("input[name='code']")?.focus();
        console.log("id",document.querySelector<HTMLElement>("input[name='code']")?.id);
    });

    const onClick = () => {
        console.log("clicked id",document.querySelector<HTMLElement>("input[name='code']")?.id);
    }
</script>
<Card title={$i18n.t('server:auth.2FAchallengeTitle')} mb="3">
	<form method="POST" action="#" on:submit|preventDefault={submit}>
        <CardBody>
			<El row>
            {#if recovery}
                <El>{$i18n.t('server:auth.2FAchallengeRecoveryDesc')}</El>
                <FormInput label={$i18n.t('server:auth.2FArecoveryCode')} name="recovery_code" bind:value={$form.recovery_code} state={$form.errors.recovery_code ? 'invalid' : 'valid'} hint={$form.errors.recovery_code}></FormInput>
            {:else}
                <El>{$i18n.t('server:auth.2FAchallengeCodeDesc')}</El>
                <FormInput label={$i18n.t('server:auth.2FAcode')} name="code" bind:value={$form.code} state={$form.errors.code ? 'invalid' : 'valid'} hint={$form.errors.code}></FormInput>
            {/if}
            </El>
        </CardBody>
        <CardFooter>
			<CardActions d="flex" gap="2">
                {#if recovery}
                <Button link color="primary" on:click={toggleRecovery}>{$i18n.t('server:auth.2FAchallengeUseCode')}</Button>
				<Button color="secondary" type="submit">{$i18n.t('server:auth.2FArecoveryCode')}</Button>
                {:else}
                <Button link color="primary" on:click={toggleRecovery}>{$i18n.t('server:auth.2FAchallengeUseRecovery')}</Button>
				<Button color="primary" type="submit">{$i18n.t('server:auth.2FAcode')}</Button>
                {/if}
			</CardActions>
		</CardFooter>
    </form>
</Card>
